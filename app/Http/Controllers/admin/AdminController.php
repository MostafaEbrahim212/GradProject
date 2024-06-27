<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FundraiserCategoryRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\CharityResource;
use App\Models\Admin;
use App\Models\Charity_Request;
use App\Models\Fundraisers;
use App\Models\FundraisersCategories;
use App\Models\Transaction;
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
        $requests = Charity_Request::with('user')->get();
        if (!$requests) {
            return res_data([], 'No requests found', 404);
        }
        return res_data($requests, 'Requests list', 200);
    }
    public function accept_request(Request $request, $id)
    {
        $request = Charity_Request::find($id);
        if (!$request) {
            return res_data([], 'Request not found', 404);
        }
        $user = User::find($request->user_id);
        $user->is_charity = true;
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
        $user->charity_info()->create([
            'name' => $user->charity_request->charity_name,
            'address' => $user->charity_request->charity_address,
            'charity_type' => $user->charity_request->charity_type,
            'financial_license' => $user->charity_request->financial_license,
            'financial_license_image' => $user->charity_request->financial_license_image,
            'ad_number' => $user->charity_request->ad_number,
        ]);
        Charity_Request::where('user_id', $id)->delete();
        return res_data($user, 'User accepted as charity', 200);
    }
    public function reject_request(Request $request, $id)
    {
        $user = User::find($id);
        $user->is_charity = false;
        $user->request_status = 'rejected';
        $user->save();
        // delete the request
        Charity_Request::where('user_id', $id)->delete();
        return res_data($user, 'User rejected as charity', 200);
    }
    public function charities(Request $request)
    {
        $users = User::where('is_charity', true)->withOnly('charity_info')->get();
        $charities_infos = $users->map(function ($user) {
            return $user->charity_info;
        });
        if (!$charities_infos) {
            return res_data([], 'No charities found', 404);
        }
        return res_data(CharityResource::collection($charities_infos), 'Charities list', 200);
    }
    public function charity(Request $request, $id)
    {
        $charity = User::where('is_charity', true)->withOnly('charity_info')->find($id);
        if (!$charity) {
            return res_data([], 'Charity not found', 404);
        }
        return res_data(new CharityResource($charity->charity_info), 'Charity info', 200);
    }
    public function add_fundraiser_category(FundraiserCategoryRequest $request)
    {
        $validated = $request->validated();
        $slug = strtolower(str_replace(' ', '-', $validated['name']));
        $validated['slug'] = $slug;
        $category = FundraisersCategories::create($validated);
        return res_data($category, 'Category added successfully', 200);
    }
    public function update_fundraiser_category(FundraiserCategoryRequest $request, $id)
    {
        $validated = $request->validated();
        $slug = strtolower(str_replace(' ', '-', $validated['name']));
        $validated['slug'] = $slug;
        $category = FundraisersCategories::find($id);
        $category->update($validated);
        return res_data($category, 'Category updated successfully', 200);
    }
    public function fundraisers_categories(Request $request)
    {
        $categories = FundraisersCategories::with('children')->get();
        if (!$categories) {
            return res_data([], 'No categories found', 404);
        }
        return res_data($categories, 'Fundraisers categories list', 200);
    }
    public function fundraisers(Request $request)
    {
        $fundraisers = Fundraisers::get();
        if (!$fundraisers) {
            return res_data([], 'No fundraisers found', 404);
        }
        return res_data($fundraisers, 'Fundraisers list', 200);
    }


    public function transactions(Request $request)
    {
        $transactions = Transaction::get();
        if (!$transactions) {
            return res_data([], 'No transactions found', 404);
        }
        return res_data($transactions, 'Transactions list', 200);
    }
}

