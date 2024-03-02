<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(UserController::class)->group(function () {
    Route::post('user/store', 'store');
    
    Route::get('user/all', 'index');
    Route::get('user/{id}', 'show');
    
    Route::delete('user/{id}','destroy');
    
    Route::put('user/{id}','update');
    Route::patch('user/change_pass/{id}','changePassword');
});