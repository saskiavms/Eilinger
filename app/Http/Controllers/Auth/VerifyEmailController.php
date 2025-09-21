<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        $user = User::findOrFail($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(app()->getLocale().RouteServiceProvider::DASH.'?verified=1');
        }

        // Verify the hash matches the user's email
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response('Invalid verification link.', 403);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(app()->getLocale().RouteServiceProvider::DASH.'?verified=1');
    }
}
