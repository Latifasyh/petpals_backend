<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SheltterGroomerController;


 Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'index']);




//crud posts
Route::apiResource('posts',PostController::class);

//Auth
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

//crud user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/user', [UserController::class, 'update']);
    Route::put('/user', [UserController::class, 'update']);
});

Route::apiResource('user',UserController::class)->middleware('auth:sanctum');
//Route::post('user', [UserController::class, 'completeProfil'])->middleware('auth:sanctum');
Route::apiResource('user',UserController::class);


//crud pets
Route::apiResource('pets',PetController::class)->middleware('auth:sanctum');

//crud seller
Route::apiResource('sellers',SellerController::class)->middleware('auth:sanctum');
//crud product
Route::apiResource('products',ProductController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
});

//crud SheltterGroomer
Route::apiResource('shelttergroomers',SheltterGroomerController::class)->middleware('auth:sanctum');
