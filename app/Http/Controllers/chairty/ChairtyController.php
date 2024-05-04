<?php

namespace App\Http\Controllers\chairty;

use App\Http\Controllers\Controller;
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
}
