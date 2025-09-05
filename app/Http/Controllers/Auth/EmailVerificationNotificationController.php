<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(app()->getLocale().RouteServiceProvider::HOME);
        }

        $context = [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email,
            'ip' => $request->ip(),
        ];
        if (app()->runningInConsole()) {
            try {
                fwrite(STDOUT, '[verify] resend ' . json_encode($context) . PHP_EOL);
            } catch (\Throwable $ignore) {
            }
        } else {
            Log::info('Verification resend requested', $context);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
