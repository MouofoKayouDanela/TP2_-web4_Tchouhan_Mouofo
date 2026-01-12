<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddlewarw;
use App\Http\Middleware\OwnUserOnly;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//Routes du TP2 ici : 
Route::get('/films', 'App\Http\Controllers\FilmController@index');

Route::post('/register', 'App\Http\Controllers\AuthController@register')->middleware('throttle:5,1');

Route::post('/login', 'App\Http\Controllers\AuthController@login')->middleware('throttle:5,1');

Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->middleware('throttle:5,1', 'auth:sanctum');



Route::middleware('auth:sanctum','throttle:60,1', RoleMiddlewarw::class.':2')->group(function () {
     Route::post('/film', 'App\Http\Controllers\FilmController@store');
     Route::put('/film/{id}', 'App\Http\Controllers\FilmController@update');
     Route::delete('/film/{id}', 'App\Http\Controllers\FilmController@destroy');   
});


Route::middleware('auth:sanctum','throttle:60,1',OwnUserOnly::class)->group(function () {
     Route::get('/user/{id}', 'App\Http\Controllers\UserController@show');
     Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
});

Route::post('/critic', 'App\Http\Controllers\CriticController@store')->middleware('auth:sanctum','throttle:60,1');