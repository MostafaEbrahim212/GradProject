<?php

namespace App\Http\Controllers\user;


use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register', 'login']);
    }
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        //make a token
        $token = $user->createToken('auth_token')->plainTextToken;
        return res_data(['token' => $token], 'User registered successfully', 201);

    }
    public function login(UserLoginRequest $request)
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
        $imageName = Str::random(10) . '.' . $validated['picture']->getClientOriginalExtension();
        Storage::disk('public')->put('images/users/' . $imageName, $validated['picture']->get());
        $validated['picture'] = $imageName;
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
}
