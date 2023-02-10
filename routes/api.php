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

    //Comments that belong to post route.
    Route::group(['prefix' => 'posts', 'as' => 'posts.'], function(){
        Route::apiResource('/{post}/comments', CommentController::class)->except('show');
    });

    Route::group(['prefix' => 'comments', 'as' => 'comments'], function(){
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/{comment}', [CommentController::class, 'show']);
    });

    Route::apiResource('/posts', PostController::class);

    //Filter route.
    Route::get('/posts', PostFilterController::class);

});
