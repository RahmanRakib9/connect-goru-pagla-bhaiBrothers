<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form, pre-filled with the currently logged-in user's data.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Save the profile changes submitted from the edit form.
     *
     * After saving, we redirect back to the profile page with a success flash message.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Copy the validated form fields onto the user model (name, email, etc.)
        $request->user()->fill($request->validated());

        // If the user changed their email address, reset their email verification status.
        // The new address is unverified until they click the confirmation link.
        // isDirty('email') returns true when the email value has been changed but not yet saved.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Permanently delete the logged-in user's account.
     *
     * Requires the user to confirm their password first to prevent accidents.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the submitted password matches the account's current password.
        // 'userDeletion' is the error bag name so the error appears in the right place on the form.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $userToDelete = $request->user();

        // Log the user out before deleting, so their session is invalidated cleanly
        Auth::logout();

        $userToDelete->delete();

        // Destroy the session data and generate a new CSRF token for safety
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
