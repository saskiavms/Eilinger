<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Login;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::debug('Login attempt started', [
            'email' => $request->email
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        Login::create([
            'user_id' => auth()->user()->id,
            'ip_address' => $request->getClientIp(),
        ]);

        session(['auth.2fa' => true]);

        Log::debug('2FA process started', [
            'user_id' => auth()->user()->id,
            'session' => session()->all(),
            'has_2fa_session' => session()->has('auth.2fa')
        ]);

        $request->user()->generateTwoFactorCode();
        $request->user()->notify(new TwoFactorCode());

        return redirect()->route('verify.index', app()->getLocale());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Session::flush();

        Auth::logout();

        return redirect('/');
    }
}
