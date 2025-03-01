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
            Log::debug('No 2FA session found', [
                'session' => session()->all(),
                'url' => request()->url()
            ]);

            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => __('Please login first.')]);
        }

        if (!auth()->check()) {
            Log::debug('User not authenticated', [
                'session' => session()->all(),
                'url' => request()->url()
            ]);

            session()->forget('auth.2fa');
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => __('Your session has expired. Please login again.')]);
        }

        return view('auth.twoFactor');
    }

    public function store(Request $request)
    {
        if (!session()->has('auth.2fa') || !auth()->check()) {
            Log::debug('Invalid session state in store', [
                'has_2fa' => session()->has('auth.2fa'),
                'is_authenticated' => auth()->check(),
                'session' => session()->all()
            ]);

            session()->forget('auth.2fa');
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => __('Your session has expired. Please login again.')]);
        }

        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => 'Your session has expired. Please login again.']);
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

        if (!$user) {
            return redirect()->route('login', app()->getLocale())
                ->withErrors(['email' => 'Your session has expired. Please login again.']);
        }

        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The two factor code has been sent again');
    }
}
