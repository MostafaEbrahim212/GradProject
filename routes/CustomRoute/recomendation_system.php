<?php
use App\Http\Controllers\recomendtaion_system_controller;


route::get('all_recomendations', [recomendtaion_system_controller::class, 'all_recomendations']);
