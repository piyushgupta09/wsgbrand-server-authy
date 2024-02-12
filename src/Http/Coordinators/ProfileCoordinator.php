<?php

namespace Fpaipl\Authy\Http\Coordinators;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Fpaipl\Authy\Http\Resources\UserResource;
use Fpaipl\Panel\Http\Coordinators\Coordinator;

class ProfileCoordinator extends Coordinator
{
    /**
     * Get a profile of logined user
     */
    public function index()
    {
        Cache::forget('profile');
        $user = Cache::remember('profile', 24 * 60 * 60, function () {
            return User::with('profile')->findOrFail(auth()->user()->id);
        });

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        $user->profile?->update([
            'contacts' => $request->contacts,
        ]);

        return new UserResource($user->profile);
    }
}