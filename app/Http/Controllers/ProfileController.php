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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $emailChanged = false;
            $oldEmail = $user->email;

            // Fill user data
            $user->fill($request->validated());

            // Check if email was changed
            // if ($user->isDirty('email')) {
            //     $emailChanged = true;
            //     $user->email_verified_at = null;
            // }

            $user->save();

            $message = __('Profile updated successfully!');
            if ($emailChanged) {
                $message = __('Profile updated successfully!');
            }

            return Redirect::route('profile.edit')->with([
                'status' => 'profile-updated',
                'message' => $message,
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with([
                'status' => 'profile-error',
                'message' => __('Failed to update profile. Please try again.'),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with([
                'status' => 'account-deleted',
                'message' => __('Account deleted successfully.'),
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with([
                'status' => 'delete-error',
                'message' => __('Failed to delete account. Please try again.'),
                'type' => 'error'
            ]);
        }
    }
}