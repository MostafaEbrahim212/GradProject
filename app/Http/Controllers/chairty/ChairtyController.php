<?php

namespace App\Http\Controllers\chairty;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChairtyProfileRequest;
use App\Http\Resources\ChairtyResource;
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
}
