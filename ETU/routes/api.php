<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Routes du TP2 ici : 
Route::get('/films', 'App\Http\Controllers\FilmController@index');


Route::middleware('throttle:5,1')->group(function () {
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
});
Route::middleware(['auth:sanctum', 'throttle:5,1'])->post('/signout', [AuthController::class, 'logout']);