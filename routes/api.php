<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/allUsers', '\App\Http\Controllers\UserController@allUsers'); // ok ...
Route::post('/createUsers', '\App\Http\Controllers\UserController@create'); // ok ...
Route::post('/updateUsers', '\App\Http\Controllers\UserController@update'); // ok ...
Route::post('/deleteUsers', '\App\Http\Controllers\UserController@delete');

