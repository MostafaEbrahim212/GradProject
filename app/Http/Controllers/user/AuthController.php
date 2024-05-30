<?php

namespace App\Http\Controllers\user;


use App\Http\Controllers\Controller;
use App\Http\Requests\Chairty_Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\userRequests\UpdatePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\userRequests\UserProfileRequest;
use App\Http\Resources\UserResource;
use App\Traits\imgTrait;
use Illuminate\Http\Request;
use App\Http\Requests\userRequests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends Controller
{
    use imgTrait;



    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register', 'login', 'getImage']);
    }


    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;
        return res_data(['token' => $token], 'User registered successfully', 201);

    }


    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return res_data([], 'Invalid credentials', 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return res_data(['token' => $token], 'User logged in successfully', 200);
    }


    public function userInfo(Request $request)
    {
        $user = Auth::user();
        return res_data(new UserResource($user), 'User info', 200);
    }


    public function CreateOrUpdateProfile(UserProfileRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        if ($request->hasFile('picture')) {
            $validated['picture'] = $this->uploadImage($request, 'users');
            if ($user->profile && $user->profile->picture) {
                Storage::delete('public/images/users/' . $user->profile->picture);
            }
        }
        if ($user->profile) {
            $user->profile->update($validated);
        } else {
            $user->profile()->create($validated);
        }
        return res_data([
            'user' => new UserResource($user)
        ], 'Profile created/updated successfully', 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return res_data([], 'User logged out successfully', 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        if (!Hash::check($validated['old_password'], $user->password)) {
            return res_data([], 'Invalid old password', 401);
        }
        $user->update(['password' => Hash::make($validated['password'])]);
        return res_data([], 'Password updated successfully', 200);
    }

    public function getImage($image)
    {
        return response()->file(storage_path('app/public/images/users/' . $image));
    }
    public function refreshAccessToken()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return res_data([
            'token' => $token
        ], 'refresh Token Successfuly');
    }

    public function request_charity(Chairty_Request $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if ($user->request_be_chairty == 1) {
            return res_data([], 'You already sent a request', 400);
        }
        $user->chairty_request()->createOrFirst($validated);
        $user->request_be_chairty = 1;
        $user->request_status = 'pending';
        $user->save();
        return res_data([$validated], 'Request sent successfully', 200);
    }



    public function sayhi(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'message' => 'required|string'
        ]);
        if ($validated['message'] == 'hi') {
            $user->has_recommendation = 1;
            $user->save();
            return res_data('', 'you said hi thank you ', 200);
        } else {
            return response()->json(['message' => "wrong message you should say hi not {$validated['message']}"], 403);
        }
    }
}


