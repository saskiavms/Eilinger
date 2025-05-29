<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web']);
        $this->middleware(['auth'])->except(['index']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'valid' => session()->has('auth.2fa') && auth()->check()
            ]);
        }

        if (!session()->has('auth.2fa')) {
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => __('userNotification.sessionExpired')]);
        }

        if (!auth()->check()) {
            session()->forget('auth.2fa');
            return redirect()->route('login', app()->getLocale())
            ->withErrors(['email' =>  __('userNotification.sessionExpired')]);
        }

        return view('auth.twoFactor');
    }

    public function store(Request $request)
    {
        if (!session()->has('auth.2fa') || !auth()->check()) {
            session()->forget('auth.2fa');
            return redirect()->route('login', app()->getLocale())
            ->withErrors(['email' =>  __('userNotification.sessionExpired')]);
        }

        // Rate limiting for 2FA attempts - 5 attempts per minute per user
        $key = 'two-factor-attempts:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'two_factor_code' => __('Too many attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]),
            ]);
        }

        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' =>  __('userNotification.sessionExpired')]);
        }

        if ($user->two_factor_expires_at < now()) {
            $user->resetTwoFactorCode();
            throw ValidationException::withMessages([
                'two_factor_code' => __('The two factor code has expired. Please request a new one.'),
            ]);
        }

        if ((int) $request->input('two_factor_code') !== (int) $user->two_factor_code) {
            RateLimiter::hit($key, 60); // Record failed attempt, available for 60 seconds
            throw ValidationException::withMessages([
                'two_factor_code' => __('The two factor code you have entered does not match'),
            ]);
        }

        // Clear rate limit on successful verification
        RateLimiter::clear($key);
        $user->resetTwoFactorCode();

        // Regenerate session ID to prevent session fixation
        $request->session()->regenerate();

        if ($user->is_admin) {
            return redirect()->route('admin_dashboard', app()->getLocale());
        } else {
            return redirect()->route('user_dashboard', app()->getLocale());
        }
    }

    public function resend()
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' =>  __('userNotification.sessionExpired')]);
        }

        // Rate limiting for 2FA code resends - 3 per 5 minutes per user
        $key = 'two-factor-resend:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->back()->withErrors([
                'two_factor_code' => __('Too many resend requests. Please wait :minutes minutes before requesting again.', 
                    ['minutes' => ceil($seconds / 60)])
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The two factor code has been sent again');
    }
}
