<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostFilterController;
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

//Register and login routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Protected routes with authentication.
Route::middleware('auth:sanctum')->group(function(){

    //Routes for authenticated users.
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/posts', PostController::class);

    //User routes.
    Route::group(['prefix' => 'posts', 'as' => 'user.'], function(){
        Route::get('/', [PostController::class, 'index']);
        Route::post('/{id}/comments', [CommentController::class, 'store']);
        Route::apiResource('/comments', CommentController::class);
        Route::get('/', PostFilterController::class);
    });


    //Admin Routes.
    Route::group(['middleware' => 'is_admin', 'prefix' => 'posts', 'as' => 'admin.'], function(){
        Route::apiResource('/category', CategoryController::class);
        Route::apiResource('/tags', CategoryController::class);
        Route::apiResource('/comments', CommentController::class);
        Route::get('/{id}/comments', [CommentController::class, 'index']);
        Route::get('/', PostFilterController::class);
    });

});
