<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminsController;
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

Route::middleware('auth:api')->group(function (){
    Route::post('user/store', [UserController::class, 'store']);

    Route::get('user/all', [UserController::class,'index']);
    Route::get('user/{id}', [UserController::class,'show']);
    
    Route::delete('user/{id}',[UserController::class,'destroy']);
    
    Route::put('user/{id}', [UserController::class,'update']);
    Route::patch('user/change_pass/{id}', [UserController::class,'changePassword']);
});


// Route::controller(UserController::class)->group(function () {
//     Route::post('user/store', 'store');
    
//     Route::get('user/all', 'index');
//     Route::get('user/{id}', 'show');
    
//     Route::delete('user/{id}','destroy');
    
//     Route::put('user/{id}','update');
//     Route::patch('user/change_pass/{id}','changePassword');
// });


Route::post('admin/reg', [AdminsController::class, 'reg']); 
Route::post('admin/login', [AdminsController::class, 'login']); 

// Route::controller(AdminController::class)->group(function () {
//     Route::post('admin/reg', 'reg'); 
//     Route::post('admin/login', 'login'); 
// });


