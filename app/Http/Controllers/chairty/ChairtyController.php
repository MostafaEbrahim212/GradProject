<?php

namespace App\Http\Controllers\chairty;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChairtyProfileRequest;
use App\Http\Resources\ChairtyResource;
use App\Models\Fundraisers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChairtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('login');
    }
    public function profile(Request $request)
    {
        $chairty = Auth::user()->chairty_info;
        return res_data(new ChairtyResource($chairty), 'Chairty info', 200);
    }
    public function updateProfile(ChairtyProfileRequest $request)
    {
        $chairty = Auth::user()->chairty_info;
        $validated = $request->validated();
        Auth::user()->chairty_info->update($validated);
        return res_data(new ChairtyResource($chairty), 'Chairty info updated', 200);
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
}
