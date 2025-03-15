<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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

        if ($request->input('two_factor_code') !== $user->two_factor_code) {
            throw ValidationException::withMessages([
                'two_factor_code' => __('The two factor code you have entered does not match'),
            ]);
        }

        $user->resetTwoFactorCode();

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
        Log::info('2FA resend requested for user', ['user_id' => $user?->id, 'email' => $user?->email]);

        if (!$user) {
            Log::error('2FA resend failed: No authenticated user');
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' =>  __('userNotification.sessionExpired')]);
        }

        try {
            $user->generateTwoFactorCode();
            Log::info('2FA code generated', ['user_id' => $user->id, 'code' => $user->two_factor_code]);

            $user->notify(new TwoFactorCode());
            Log::info('2FA notification sent', ['user_id' => $user->id]);

            return redirect()->back()->withMessage('The two factor code has been sent again');
        } catch (\Exception $e) {
            Log::error('2FA notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
