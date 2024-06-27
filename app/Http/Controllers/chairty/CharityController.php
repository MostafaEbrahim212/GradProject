<?php

namespace App\Http\Controllers\charity;

use App\Http\Controllers\Controller;
use App\Http\Requests\CharityProfileRequest;
use App\Http\Resources\CharityResource;
use App\Models\Fundraisers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login');
    }
    public function Profile(Request $request)
    {
        $charity = Auth::user()->charity_info;
        return res_data(new CharityResource($charity), 'Charity info', 200);
    }
    public function updateProfile(CharityProfileRequest $request)
    {
        $charity = Auth::user()->charity_info;
        $validated = $request->validated();
        Auth::user()->charity_info->update($validated);
        return res_data(new CharityResource($charity), 'charity info updated', 200);
    }
    public function add_fundraiser(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'goal' => 'required|numeric',
            'raised' => 'required|numeric',
            'end_date' => 'required|date',
            'account_number' => 'required|string',
            'is_active' => 'required|boolean',
            'category_id' => 'exists:fundraisers_categories,id',
        ]);
        $data['user_id'] = Auth::id();
        $fundraiser = Fundraisers::create($data);
        return res_data($fundraiser, 'Fundraiser added', 200);
    }
    public function fundraisers(Request $request)
    {
        $fundraisers = Fundraisers::where('user_id', Auth::id())->get();
        if ($fundraisers->isEmpty()) {
            return res_data([], 'No fundraisers found', 404);
        }
        return res_data($fundraisers, 'Fundraisers list', 200);
    }
    public function update_fundraiser(Request $request, $id)
    {
        $fundraiser = Fundraisers::where('user_id', Auth::id())->find($id);
        if (!$fundraiser) {
            return res_data([], 'Fundraiser not found', 404);
        }
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'goal' => 'required|numeric',
            'raised' => 'required|numeric',
            'end_date' => 'required|date',
            'account_number' => 'required|string',
            'is_active' => 'required|boolean',
            'category_id' => 'exists:fundraisers_categories,id',
        ]);
        $fundraiser->update($data);
        return res_data($fundraiser, 'Fundraiser updated', 200);
    }
    public function delete_fundraiser(Request $request, $id)
    {
        $fundraiser = Fundraisers::where('user_id', Auth::id())->find($id);
        if (!$fundraiser) {
            return res_data([], 'Fundraiser not found', 404);
        }
        $fundraiser->delete();
        return res_data([], 'Fundraiser deleted', 200);
    }

    public function transactions(Request $request)
    {
        $user = Auth::user();
        if ($user->is_charity != 1) {
            return res_data('error', 'You are not allowed to access this resource', 403);
        }
        $fundraiserIds = Fundraisers::where('user_id', $user->id)->pluck('id');
        $transactions = Transaction::whereIn('fundraiser_id', $fundraiserIds)->get();
        return res_data($transactions, 'Transactions related to your fundraisers', 200);
    }


}
