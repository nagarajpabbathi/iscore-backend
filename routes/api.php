<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\APIController;
use App\Http\Controllers\{
    CasinoController,
    CategoryController,
    PostController,
    UserController
};

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

Route::get('post', [PostController::class, 'postList']);
Route::get('post-prediction', [PostController::class, 'postPrediction']);
Route::get('post/{id}', [PostController::class, 'postDetails']);
Route::post('likes', [PostController::class, 'likes']);
Route::get('casino', [CasinoController::class, 'casinoList']);
Route::get('casino/{id}', [CasinoController::class, 'casinoDetails']);
Route::get('category', [CategoryController::class, 'categoryList']);

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::post('forget-password', [UserController::class, 'forgetPassword']);
Route::post('password-update', [UserController::class, 'passwordUpdate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'userDetails']);
    Route::post('user-profile', [UserController::class, 'userProfileUpdate']);
    Route::get('logout', [UserController::class, 'logout']);
});

//This API routes are to get the details of cricket matches
Route::get('live', [APIController::class, 'live']);
Route::post('foryou', [APIController::class, 'foryou']);
Route::post('upcoming', [APIController::class, 'upcoming']);
Route::post('finished', [APIController::class, 'finished']);

Route::post('infoPage', [APIController::class, 'infoPage']);
Route::post('fantasypage', [APIController::class, 'fantasypage']);
Route::post('commenterypage', [APIController::class, 'commenterypage']);
Route::post('livepage', [APIController::class, 'livepage']);
Route::post('scorecardpage', [APIController::class, 'scorecardpage']);
Route::post('pointstable', [APIController::class, 'pointstable']);
Route::get('test', [APIController::class, 'testing']);
