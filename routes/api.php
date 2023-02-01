<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFilterController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected routes with authentication.
Route::middleware('auth:sanctum')->group(function(){

    //Routes for authenticated users.
    Route::post('/logout', [AuthController::class, 'logout']);

    //CRUD routes.
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/tags', TagController::class);
    Route::apiResource('/posts', PostController::class);

    //Post Comments crud
    Route::group(['prefix' => 'posts/{post}'], function(){
        Route::apiResource('/comments', CommentController::class);
    });

    //Filter route.
    Route::get('/posts', PostFilterController::class);
});
