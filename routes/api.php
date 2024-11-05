<?php

use App\Models\Post;
use App\Models\User;
use App\Models\veto;
use App\Models\CoverPic;
use App\Models\Discussion;
use Illuminate\Http\Request;
use App\Models\PicturesBusiness;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VetoController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CoverPicController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\UserPicturesController;
use App\Http\Controllers\SheltterGroomerController;
use App\Http\Controllers\PicturesBusinessController;

 Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'index']);




//crud posts
Route::apiResource('posts',PostController::class)->middleware('auth:sanctum');
Route::post('/posts/{post}', [PostController::class, 'update']);

// routes/api.php
//Route::patch('/posts/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');



//Route::middleware('auth:sanctum')->get('/posts/{type}', [PostController::class, 'getPostsByType']);



/* Route::middleware('auth:sanctum')->group(function () {
Route::post('/posts/{post}/reactions', [ReactionController::class, 'addReaction']);
Route::delete('/posts/{post}/reactions', [ReactionController::class, 'removeReaction']);
}); */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/posts/{postId}/reactions', [ReactionController::class, 'store'])->name('reactions.store');
    Route::put('/reactions/{reactionId}', [ReactionController::class, 'update'])->name('reactions.update');
    Route::delete('/reactions/{reactionId}', [ReactionController::class, 'destroy'])->name('reactions.destroy');

});

//Route::apiResource('/posts/{post}/reactions',ReactionController::class)->middleware('auth:sanctum');

//Auth
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');

//crud user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/user/{user}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::post('/user/update', [UserController::class, 'update'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/user', [UserController::class, 'update']);
    Route::put('/user', [UserController::class, 'update']);
});

Route::apiResource('user',UserController::class)->middleware('auth:sanctum');
//Route::post('user', [UserController::class, 'completeProfil'])->middleware('auth:sanctum');
Route::apiResource('user',UserController::class);


//crud pets
Route::apiResource('pets',PetController::class)->middleware('auth:sanctum');
Route::post('/pets/{pet}', [PetController::class, 'update'])->middleware('auth:sanctum');


//crud seller
Route::apiResource('sellers',SellerController::class)->middleware('auth:sanctum');
Route::put('/sellers/{seller}', [SellerController::class, 'update']);

//crud product
Route::apiResource('products',ProductController::class)->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
});

//crud SheltterGroomer
Route::apiResource('shelttergroomers',SheltterGroomerController::class)->middleware('auth:sanctum');

//crud Veto
Route::apiResource('veto',VetoController::class)->middleware('auth:sanctum');

//discussion
Route::apiResource('discussion',DiscussionController::class)->middleware('auth:sanctum');
Route::post('/discussion/{discussion}', [DiscussionController::class, 'update'])->middleware('auth:sanctum');

//coverPic
Route::apiResource('coverPic',CoverPicController::class)->middleware('auth:sanctum');


//picture user folder
Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/photos', [UserPicturesController::class, 'uploadPhotos']);
    Route::get('users/photos', [UserPicturesController::class, 'getPhotos']);
    Route::delete('users/photos/{userPhoto}', [UserPicturesController::class, 'destroy']);
});


Route::prefix('sellers')->group(function () {
    Route::post('{sellerId}/pictures', [PicturesBusinessController::class, 'addPictures']);
    Route::get('{sellerId}/pictures', [PicturesBusinessController::class, 'getPictures']);
    Route::delete('pictures/{pictureId}', [PicturesBusinessController::class, 'deletePicture']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pictures/{entityId}/{entityType}', [PicturesBusinessController::class, 'getPictures']);
    Route::post('/pictures/{entityId}/{entityType}', [PicturesBusinessController::class, 'addPictures']);
    Route::delete('/pictures/{pictureId}', [PicturesBusinessController::class, 'deletePicture']);
});
