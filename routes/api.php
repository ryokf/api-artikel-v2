<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Models\UserInterest;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(ArticleController::class)->group(function(){
    Route::get('/article', 'index');
    Route::post('/article', 'store');
    Route::put('/article', 'update');
    Route::delete('/article', 'destroy');
});

Route::controller(CategoryController::class)->group(function(){
    Route::get('/category', 'index');
    Route::post('/category', 'store');
    Route::put('/category', 'update');
    Route::delete('/category', 'destroy');
});

Route::controller(BookmarkController::class)->group(function(){
    Route::get('/bookmark', 'index');
    Route::post('/bookmark', 'store');
    Route::delete('/bookmark', 'destroy');
});

Route::controller(CommentController::class)->group(function(){
    Route::post('/comment', 'store');
    Route::put('/comment', 'update');
    Route::delete('/comment', 'destroy');
});

Route::controller(UserInterest::class)->group(function(){
    Route::get('/user-interest', 'index');
    Route::post('/user-interest', 'store');
    Route::delete('/user-interest', 'destroy');
});

Route::controller(User::class)->group(function(){
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
    Route::get('/me', 'me');
});




