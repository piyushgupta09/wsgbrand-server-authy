<?php

namespace Fpaipl\Authy\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Fpaipl\Authy\Models\Profile;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user()->profile;
        return view('authy::pages.myprofile', [
            'profile' => $profile,
        ]);
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'uuid' => 'required|exists:users,uuid',
        ]);

        $user = User::find(auth()->user()->id);
        $user->addMediaFromRequest('image')->toMediaCollection(User::MEDIA_COLLECTION_NAME);
        return redirect()->back()->with('success', 'Profile image updated successfully');
    }

    public function removeProfileImage(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:users,uuid',
        ]);

        $user = User::find(auth()->user()->id);
        $user->clearMediaCollection(User::MEDIA_COLLECTION_NAME);
        return redirect()->back()->with('success', 'Profile image removed successfully');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|numeric|digits:10',
            'password' => 'sometimes|nullable|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $user->name = $request->name;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        if ($user->profile) {
            $user->profile->contacts = $request->mobile;
            $user->profile->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

}
