<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



require __DIR__ . '/CustomRoute/user.php';
require __DIR__ . '/CustomRoute/admin.php';
require __DIR__ . '/CustomRoute/charity.php';
require __DIR__ . '/CustomRoute/recomendation_system.php';
