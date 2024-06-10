<?php

namespace App\Http\Controllers;

use App\Models\recommendation_table;
use Illuminate\Http\Request;

class recomendtaion_system_controller extends Controller
{
    public function all_recomendations(Request $request)
    {
        $recomendations = recommendation_table::all();
        return res_data($recomendations, 'All recomendations', 200);
    }
}
