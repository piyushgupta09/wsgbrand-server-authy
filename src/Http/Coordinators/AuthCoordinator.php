<?php

namespace Fpaipl\Authy\Http\Coordinators;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Responder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Fpaipl\Authy\Http\Resources\UserResource;
use Fpaipl\Brandy\Http\Resources\PartyResource;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Requests\LoginUserRequest;
use Fpaipl\Authy\Http\Resources\ProfileResource;
use Fpaipl\Authy\Http\Requests\RegisterUserRequest;
use Fpaipl\Authy\Http\Requests\SendLoginOtpRequest;
use Fpaipl\Brandy\Http\Resources\UserPartyResource;

/**
 * Class AuthCoordinator
 * Handles authentication and user-related activities
 * register
 * sendLoginOtp
 * login
 * logout
 * emailVerification
 * verifyOtp
 * updateUser
 * updateNewPassword
 * userProfile
 */
class AuthCoordinator extends Coordinator
{
    /**
     * Register a new user
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->username,
            'password' => Hash::make($request->password),
            'otpcode' => rand(1000, 9999),
            'type' => $request->type,
        ]);

        $user->sendOTPNotification($user->otpcode);

        return Responder::ok('Registration successful', [
            'user' => new UserResource($user),
            'token' => $user->createToken($request->device)->plainTextToken,
        ], 201);
    }

    /**
     * Send login OTP to user
     *
     * @param SendLoginOtpRequest $request
     * @return JsonResponse
     */
    public function sendLoginOtp(SendLoginOtpRequest $request)
    {
        try {
            $user = User::where('email', $request->username)->first();
            $user->otpcode = rand(1000, 9999); // Generate OTP
            $user->save();
            $user->sendOTPNotification($user->otpcode);
            return Responder::ok('Login otp sent');
        } catch (\Throwable $th) {
            return Responder::ok('failed', 'Oops! something went wrong');
        }
    }

    /**
     * Handle user login
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $loginApproved = false;

        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->username)
                  ->orWhere('mobile', $request->username);
        })->first();
        

        if (!$user->active) {
            return Responder::error(['username' => ['Your account is not active. Please contact support.']], 403);
        } elseif ($request->has('password') && !empty($request->password)) {
            if (!Hash::check($request->password, $user->password)) {
                return Responder::error(['password' => ['The provided credentials are incorrect.']], 422);
            } else {
                $loginApproved = true;
            }
        } elseif ($request->has('otpcode') && !empty($request->otpcode)) {
            if ($request->otpcode != $user->otpcode) {
                return Responder::error(['otpcode' => ['The provided OTP is incorrect.']], 422);
            } else {
                $loginApproved = true;
            }
        }

        if ($loginApproved) {
            return Responder::ok('Login successful', [
                'user' => new UserResource($user),
                'token' => $user->createToken($request->device)->plainTextToken,
            ]);
        }

        return Responder::error(['username' => ['The provided credentials are incorrect.']], 422);
        
    }

    /**
     * Logout the current user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();

        // Return success response
        return Responder::ok('Logged out successfully');
    }

    /**
     * Send email verification OTP
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function emailVerification(Request $request)
    {
        $user = $request->user();

        $user->otpcode = rand(1000, 9999); // Generate OTP
        $user->save();

        $user->sendOTPNotification($user->otpcode);

        // Return success response
        return Responder::ok('Email verification OTP sent');
    }

    /**
     * Verify email using OTP
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|int|min:1000|max:9999',
        ]);

        $user = $request->user();

        if ($request->otp == $user->otpcode) {
            $user->email_verified_at = Carbon::now();
            $user->otpcode = null; // reset the OTP
            $user->save();
            
            // Remember to run queue:work
            event(new Verified($user));

            // Return success response
            return Responder::ok('Email verified successfully');
        } else {
            // Return failure response
            return Responder::error(['otp' => ['The provided OTP is incorrect.']], 422);
        }
    }

    /**
     * Update user details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
            ]);
            $user->addMediaFromRequest('image')->toMediaCollection('profile');
        }

        return Responder::ok('User updated successfully', [
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update the user's password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return Responder::ok('Password updated successfully');
    }

    /**
     * Get the current user's profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userProfile(Request $request)
    {
        $user = $request->user();
        // $user->load('addresses', 'profile');
        $user->load('profile');

        return Responder::ok(null, [
            'user' => new UserResource($user),
            'party' => $user->hasParty() ? new UserPartyResource($user->party) : null,
            'profile' => new ProfileResource($user->profile),
        ]);
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find(auth()->user()->id);
        $user->addMediaFromRequest('image')->toMediaCollection(User::MEDIA_COLLECTION_NAME);
        return Responder::ok('Profile Image Uploaded', new ProfileResource($user->profile));
    }

    public function validateSession(Request $request)
    {
        $user = $request->user();
        $status = $user ? 'ok' : 'error';
        $message = $user ? 'Session is valid' : 'Session is invalid';
        return Responder::send($status, $message, null, $user ? 200 : 401);
    }
}
