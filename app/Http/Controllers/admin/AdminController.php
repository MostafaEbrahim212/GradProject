<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\ChairtyResource;
use App\Models\Admin;
use App\Models\Chairty_Request;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login');
    }
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $admin = Admin::where('email', $validated['email'])->first();
        if (!$admin || !Hash::check($validated['password'], $admin->password)) {
            return res_data([], 'Invalid credentials', 401);
        }
        $token = $admin->createToken('auth_token')->plainTextToken;
        return res_data(['token' => $token], 'User logged in successfully', 200);
    }
    public function info(Request $request)
    {
        $admin = Auth::guard('admins')->user();
        return res_data($admin, 'Admin info', 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return res_data([], 'Logged out successfully', 200);
    }

    public function users(Request $request)
    {
        $users = User::with('profile', 'chairites_permessions')->get();
        if (!$users) {
            return res_data([], 'No users found', 404);
        }
        return res_data($users, 'Users list', 200);
    }

    public function user(Request $request, $id)
    {
        $user = User::with('profile', 'chairites_permessions')->find($id);
        if (!$user) {
            return res_data([], 'User not found', 404);
        }
        return res_data($user, 'User info', 200);
    }
    public function toggle_active(Request $request, $id)
    {
        $user = User::find($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return res_data($user, 'User active status updated', 200);
    }
    public function requests(Request $request)
    {
        $requests = Chairty_Request::with('user')->get();
        if (!$requests) {
            return res_data([], 'No requests found', 404);
        }
        return res_data($requests, 'Requests list', 200);
    }
    public function accept_request(Request $request, $id)
    {

        $user = User::find($id);
        $user->is_chairty = true;
        $user->request_status = 'accepted';
        $user->chairites_permessions()->create([
            'user_id' => $user->id,
            'can_create' => true,
            'can_read' => true,
            'can_update' => true,
            'can_delete' => true,
        ]);
        $user->save();
        // delete the request
        $user->chairty_info()->create([
            'name' => $user->chairty_request->chairty_name,
            'address' => $user->chairty_request->chairty_address,
            'chairty_type' => $user->chairty_request->chairty_type,
            'financial_license' => $user->chairty_request->financial_license,
            'ad_number' => $user->chairty_request->ad_number,
        ]);
        Chairty_Request::where('user_id', $id)->delete();
        return res_data($user, 'User accepted as charity', 200);
    }
    public function reject_request(Request $request, $id)
    {
        $user = User::find($id);
        $user->is_chairty = false;
        $user->request_status = 'rejected';
        $user->save();
        // delete the request
        Chairty_Request::where('user_id', $id)->delete();
        return res_data($user, 'User rejected as charity', 200);
    }
    public function chairites(Request $request)
    {
        $users = User::where('is_chairty', true)->withOnly('chairty_info')->get();
        $chairties_infos = $users->map(function ($user) {
            return $user->chairty_info;
        });
        if (!$chairties_infos) {
            return res_data([], 'No chairties found', 404);
        }
        return res_data(ChairtyResource::collection($chairties_infos), 'Charities list', 200);
    }
    public function chairty(Request $request, $id)
    {
        $chairty = User::where('is_chairty', true)->withOnly('chairty_info')->find($id);
        if (!$chairty) {
            return res_data([], 'Charity not found', 404);
        }
        return res_data(new ChairtyResource($chairty->chairty_info), 'Charity info', 200);
    }
}
