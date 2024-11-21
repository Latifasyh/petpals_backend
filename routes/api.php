<?php

use App\Models\Post;
use App\Models\User;
use App\Models\veto;
use App\Models\Product;
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
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CoverPicController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\UserPicturesController;
use App\Http\Controllers\ProfessionTypesController;
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
    Route::get('/posts/{postId}/reactions', [ReactionController::class, 'index'])->name('reactions.index');
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
/* Route::apiResource('sellers',SellerController::class)->middleware('auth:sanctum');
Route::put('/sellers/{seller}', [SellerController::class, 'update']); */

//crud product
Route::apiResource('products',ProductController::class)->middleware('auth:sanctum');
//Route::post('/products/{product}', [ProductController::class, 'update']);

//update product
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products/{product}', [ProductController::class, 'update']);

});

//crud services
Route::apiResource('services',ServiceController::class)->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/services/{service}', [ServiceController::class, 'update']);
    Route::get('services/filter/{ville}', [ServiceController::class, 'filterByVille']);
});



//crud SheltterGroomer
//Route::apiResource('shelttergroomers',SheltterGroomerController::class)->middleware('auth:sanctum');

//crud Veto
//Route::apiResource('veto',VetoController::class)->middleware('auth:sanctum');

//discussion
Route::apiResource('discussion',DiscussionController::class)->middleware('auth:sanctum');
Route::post('/discussion/{discussion}', [DiscussionController::class, 'update'])->middleware('auth:sanctum');

//coverPic
//Route::apiResource('coverPic',CoverPicController::class)->middleware('auth:sanctum');


//picture user folder
Route::middleware('auth:sanctum')->group(function () {
    Route::post('users/photos', [UserPicturesController::class, 'uploadPhotos']);
    Route::get('users/photos', [UserPicturesController::class, 'getPhotos']);
    Route::delete('users/photos/{userPhoto}', [UserPicturesController::class, 'destroy']);
});


/* Route::prefix('sellers')->group(function () {
    Route::post('{sellerId}/pictures', [PicturesBusinessController::class, 'addPictures']);
    Route::get('{sellerId}/pictures', [PicturesBusinessController::class, 'getPictures']);
    Route::delete('pictures/{pictureId}', [PicturesBusinessController::class, 'deletePicture']);
}); */


/* Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pictures/{entityType}', [PicturesBusinessController::class, 'getPictures']);
    Route::post('/pictures/{entityType}', [PicturesBusinessController::class, 'addPictures']);
    Route::delete('/pictures/{pictureId}', [PicturesBusinessController::class, 'deletePicture']);
}); */

/* Route::middleware('auth:sanctum')->group(function () {
    Route::get('/professionTypes/{entityType}/pictures', [PicturesBusinessController::class, 'getPictures']);
    Route::post('/professionTypes/{entityType}/pictures', [PicturesBusinessController::class, 'addPictures']);
    Route::delete('/pictures/{pictureId}', [PicturesBusinessController::class, 'deletePicture']);
}); */



/* Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pictures/{professionTypes}/{professionTypesId}', [PicturesBusinessController::class, 'getUserPicturesByBusiness']);
    Route::post('/pictures/{professionTypesId}', [PicturesBusinessController::class, 'uploadPicturesForBusiness']);
    Route::delete('/pictures/{pictureId}', [PicturesBusinessController::class, 'deletePictureById']);
    Route::delete('/pictures/{professionTypes}/{professionTypesId}', [PicturesBusinessController::class, 'deleteAllPicturesByBusiness']);
});

Route::post('pictures/{businessType}/{businessId}', [PicturesBusinessController::class, 'uploadPicturesForBusiness'])
    ->middleware('auth:sanctum');


Route::get('/professionTypes/type/{type}', [ProfessionTypesController::class, 'getByType']);

Route::apiResource('professionTypes',ProfessionTypesController::class)->middleware('auth:sanctum');
*/
Route::middleware('auth:sanctum')->group(function () {
    // Profession Types Routes
    Route::get('/profession-types', [ProfessionTypesController::class, 'index']); // Obtenir tous les types de profession
    Route::get('/profession-types/{id}', [ProfessionTypesController::class, 'show']); // Obtenir un type de profession spécifique
    Route::get('/profession-types/type/{type}', [ProfessionTypesController::class, 'getByType']); // Obtenir les professions par type (seller, veto, sheltter)
    Route::post('/profession-types', [ProfessionTypesController::class, 'store']); // Créer un type de profession
    Route::put('/profession-types/{id}', [ProfessionTypesController::class, 'update']); // Mettre à jour un type de profession
    Route::delete('/profession-types/{id}', [ProfessionTypesController::class, 'destroy']); // Supprimer un type de profession


    // Business Pictures Routes
     Route::get('/pictures-business/{professionTypes}/{professionTypesId}', [PicturesBusinessController::class, 'getUserPicturesByBusiness']);
        // Obtenir les photos d'un type de profession d'un utilisateur spécifique

     Route::post('/pictures-business/{professionTypes}', [PicturesBusinessController::class, 'uploadPicturesForBusiness']);
        // Télécharger des photos pour une entreprise spécifique
     Route::post('/upload-picture/{professionTypesId}', [PicturesBusinessController::class, 'uploadPictureForBusiness'])->middleware('auth:sanctum');

    Route::delete('/pictures-business/{pictureId}', [PicturesBusinessController::class, 'deletePictureById']);
        // Supprimer une photo spécifique par ID
     // Supprimer toutes les photos d'un type de profession pour un utilisateur donné
     Route::delete('/pictures-business/{professionTypes}/{professionTypeId}', [PicturesBusinessController::class, 'deleteAllPicturesByBusinessType']);

});


/* Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cover', [CoverPicController::class, 'getCover']);
    Route::get('/bio', [CoverPicController::class, 'getBio']);
    Route::post('/cover', [CoverPicController::class, 'updateCover']);
    Route::post('/bio', [CoverPicController::class, 'updateBio']);
    Route::delete('/cover', [CoverPicController::class, 'deleteCover']);
    Route::delete('/bio', [CoverPicController::class, 'deleteBio']);
});
 */

 Route::middleware('auth:sanctum')->group(function () {
    // Récupérer la photo de couverture
    Route::get('/coverpic/cover', [CoverPicController::class, 'showCover']);

    // Récupérer la biographie
    Route::get('/coverpic/bio', [CoverPicController::class, 'showBio']);

    // Mettre à jour ou créer la photo de couverture
    Route::post('/coverpic/cover', [CoverPicController::class, 'updateOrCreateCover']);

    // Mettre à jour ou créer la biographie
    Route::put('/coverpic/bio', [CoverPicController::class, 'updateOrCreateBio']);

    // Supprimer la photo de couverture
    Route::delete('/coverpic/cover', [CoverPicController::class, 'deleteCover']);

    // Supprimer la biographie
    Route::delete('/coverpic/bio', [CoverPicController::class, 'deleteBio']);
});
//Route::post('/user/cover', [CoverPicController::class, 'updateOrCreateCover'])->middleware('auth:sanctum');

