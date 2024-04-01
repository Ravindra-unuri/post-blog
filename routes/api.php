<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogpostCategoryController;
use App\Http\Controllers\BlogpostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
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

Route::post('/AdminRegister', [AdminController::class, 'Registration']);
Route::post('/AdminLogin', [AdminController::class, 'login']);

Route::post('/UserRegister', [UserController::class, 'Registration']);
Route::post('/UserLogin', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:admin']], function () {

    Route::post('/AdminProfile', [AdminController::class, 'profile']);
    Route::post('/AdminLogout', [AdminController::class, 'logout']);

    Route::group(['prefix' => '/blogpostCategory'], function () {
        Route::post('/createCategory', [BlogpostCategoryController::class, 'createCategory']);
        Route::post('/updateCategory/{id}', [BlogpostCategoryController::class, 'updateCategory']);
        Route::post('/deleteCategory/{id}', [BlogpostCategoryController::class, 'deleteCategory']);
    });
});

Route::group(['middleware' => ['auth:user']], function () {
    Route::post('/MyProfile', [UserController::class, 'MyProfile']);
    Route::post('/UserLogout', [UserController::class, 'logout']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);
    // Route::post('/updateUser', function(Request $request){
    //     dd($request->post());
    // });
    
    Route::post('/updatePassword', [UserController::class, 'updatePassword']);
    Route::post('/get', [UserController::class, 'get']);

    Route::group(['prefix' => '/blogpost'], function () {
        Route::post('/storeBlogpost', [BlogpostController::class, 'storeBlogpost']);
        Route::post('/updateBlogpost/{id}', [BlogpostController::class, 'updateBlogpost']);
        Route::post('/showBlogpost', [BlogpostController::class, 'showBlogpost']);
        Route::post('/myBlogpost', [BlogpostController::class, 'myBlogpost']);
        Route::post('/deleteBlogpost/{id }', [BlogpostController::class, 'deleteBlogpost']);
    });

    Route::group(['prefix' => '/like'], function () {
        Route::post('/doLike/{blogpost_id}', [LikeController::class, 'doLike']);
        Route::post('/allLike', [LikeController::class, 'allLike']);
        Route::post('/likeDetail/{id}', [LikeController::class, 'likeDetail']);
        Route::post('/dislike/{id}', [LikeController::class, 'dislike']);
    });

    Route::group(['prefix' => '/comment'], function () {
        Route::post('/doComment/{id}', [CommentController::class, 'doComment']);
        Route::post('/allComment', [CommentController::class, 'allComment']);
        Route::post('/commentDetail/{id}', [CommentController::class, 'commentDetail']);
        Route::post('/deleteComment/{id}', [CommentController::class, 'deleteComment']);
    });

    Route::group(['prefix' => '/followcontrol'], function () {
        Route::post('/follow/{userId}', [FollowController::class, 'follow']);
        Route::delete('/unfollow/{userId}', [FollowController::class, 'unfollow']);
    });
});
