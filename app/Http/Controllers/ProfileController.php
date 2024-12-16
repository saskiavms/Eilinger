<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view(auth()->user()->is_admin ? 'admin.profile-edit' : 'user.profile-edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->save();
            $request->user()->sendEmailVerificationNotification();
        } else {
            $request->user()->save();
        }

        Session::flash('success', __('profile.profile_updated'));

        return redirect()->route(
            auth()->user()->is_admin ? 'admin_dashboard' : 'user_dashboard',
            app()->getLocale()
        );
    }
}
