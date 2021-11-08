<?php

use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\FtpController;
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

Route::get('getAll', [PhotoController::class, 'ShowAll']);
Route::get('getPhotoNames', [FtpController::class, 'getAllFileName']);
Route::get('deletePhoto/{id}', [PhotoController::class, 'DeletePhoto']);
