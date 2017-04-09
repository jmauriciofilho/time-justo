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

/*
 * Rotas de usuário...
 */
Route::get('/loginApp', '\App\Http\Controllers\UserController@loginApp');
Route::get('/allUsers', '\App\Http\Controllers\UserController@allUsers'); // ok ...
Route::post('/createUser', '\App\Http\Controllers\UserController@create'); // ok ...
Route::post('/updateUser', '\App\Http\Controllers\UserController@update'); // ok ...
Route::post('/deleteUser', '\App\Http\Controllers\UserController@delete'); // ok ...
Route::post('/setConfirmParticipation', '\App\Http\Controllers\UserController@setConfirmParticipation');

/*
 * Rotas de eventos...
 */
Route::get('/allGames', '\App\Http\Controllers\GameController@allGames');
Route::post('/createGame', '\App\Http\Controllers\GameController@create');
Route::post('/updateGame', '\App\Http\Controllers\GameController@update');
Route::post('/deleteGame', '\App\Http\Controllers\GameController@delete');