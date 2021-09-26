<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleriesController;
use App\Http\Controllers\CommentController;

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


Route::group(['prefix' => 'auth'], function () {

    Route::get('/me', [AuthController::class, 'getMyProfile'])->middleware('auth');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refreshToken'])->middleware('auth');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});


Route::put('/edit-galleries/{gallery}', [GalleriesController::class, 'update']);
Route::get('/galleries', [GalleriesController::class, 'index']);

Route::get('/galleries/{gallery}', [GalleriesController::class, 'show']);

Route::get('/my-galleries/{user_id}', [GalleriesController::class, 'getMyGalleries']);

Route::post('/galleries/{gallery}/comments', [CommentController::class, 'store']);

Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
Route::delete('/galleries/{gallery}', [GalleriesController::class, 'destroy']);

Route::post('/create-galleries', [GalleriesController::class, 'store']);


