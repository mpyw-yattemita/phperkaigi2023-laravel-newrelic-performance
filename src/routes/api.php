<?php

use App\Http\Controllers\Update;
use App\Http\Controllers\Validate;
use App\Http\Controllers\View;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/view', View::class);
Route::post('/update', Update::class);
Route::post('/validate', Validate::class);
