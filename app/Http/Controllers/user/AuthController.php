<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Charity_Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\userRequests\UpdatePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\userRequests\UserProfileRequest;
use App\Http\Resources\CharityResource;
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
        $this->middleware('auth:sanctum')->except(['register', 'login']);
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
    public function request_charity(Charity_Request $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if ($user->request_be_charity == 1) {
            return res_data([], 'You already sent a request', 400);
        }
        $user->charity_request()->createOrFirst($validated);
        $user->request_be_charity = 1;
        $user->request_status = 'pending';
        $user->save();
        return res_data([$validated], 'Request sent successfully', 200);
    }
    public function checkRecommendation()
    {
        $user = Auth::user();
        if ($user->has_recommendation == 1) {
            return res_data([], 'You already sent a recommendation', 200);
        } else {
            return res_data([], 'You did not send a recommendation', 400);
        }
    }
    public function recommendation(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'age' => 'required|integer',
            'education_level' => 'required|in:bachelor,master,phd',
            'previous_donation_type' => 'required|in:food,books,electronics,clothes',
            'previous_volunteeer' => 'required|boolean',
            'personal_interests' => 'required|in:sports,health,education,agriculture,environment',
            'profession' => 'required|in:engineer,doctor,teacher,lawyer,scientist,artist',
        ]);
        if ($user->has_recommendation == 1) {
            return res_data([], 'You already sent a recommendation', 400);
        }
        $user->has_recommendation = 1;
        $user->recomendation()->create($validated);
        $user->save();
        return res_data([$validated], 'Recommendation sent successfully', 200);
    }
    public function charities()
    {
        try {
            $user = Auth::user();
            $charities = User::where('is_charity', 1)
                ->with(['charity_info', 'fundraisers'])
                ->get();
            if ($charities->isEmpty()) {
                return res_data([], 'No charities found', 404);
            }
            $charities_infos = $charities->map(function ($charity) {
                return [
                    'charity_info' => $charity->charity_info ? new CharityResource($charity->charity_info) : null,
                    'fundraisers' => $charity->fundraisers,
                ];
            });
            return res_data($charities_infos, 'All charities', 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching charities: ' . $e->getMessage());
            return res_data([], 'Server error', 500);
        }
    }




    public function searchCharity(Request $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validate([
                'search' => 'required|string'
            ]);
            $searchTerm = strtolower($validated['search']);

            $charities = User::where('is_charity', 1)
                ->leftJoin('charity__infos', 'users.id', '=', 'charity__infos.user_id')
                ->whereRaw('LOWER(charity__infos.name) like ?', ['%' . $searchTerm . '%'])
                ->with(['charity_info', 'fundraisers'])
                ->select('users.*')
                ->paginate(10);
            $charities->getCollection()->transform(function ($charity) {
                return [
                    'charity_info' => new CharityResource($charity->charity_info),
                    'fundraisers' => $charity->fundraisers,
                ];
            });
            return res_data($charities, 'All charities', 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching charities: ' . $e->getMessage());
            return res_data([$e->getMessage()], 'Server error', 500);
        }
    }

    public function charity($id)
    {
        $charity = User::where('is_charity', 1)->with('charity_info', 'fundraisers')->find($id);
        if (!$charity) {
            return res_data([], 'charity not found', 404);
        }
        return res_data([
            new CharityResource($charity->charity_info),
        ], 'charity info', 200);
    }
}


