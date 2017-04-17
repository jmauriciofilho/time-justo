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
Route::post('/loginApp', '\App\Http\Controllers\UserController@loginApp'); // ok ...
Route::get('/allUsers', '\App\Http\Controllers\UserController@allUsers'); // ok ...
Route::post('/createUser', '\App\Http\Controllers\UserController@create'); // ok ...
Route::post('/updateUser', '\App\Http\Controllers\UserController@update'); // ok ...
Route::post('/deleteUser', '\App\Http\Controllers\UserController@delete'); // ok ...
Route::post('/setOverall', '\App\Http\Controllers\UserController@setOverall');
Route::post('/setGoalsScored', '\App\Http\Controllers\UserController@setGoalsScored'); // ok ..
Route::post('/invitePlayers', '\App\Http\Controllers\UserController@invitePlayers'); // ok ...
Route::post('/setConfirmParticipation', '\App\Http\Controllers\GuestPlayersController@setConfirmParticipation'); // ok ...

/*
 * Rotas de eventos...
 */
Route::get('/allEvents', '\App\Http\Controllers\EventController@allEvents');
Route::post('/createEvent', '\App\Http\Controllers\EventController@create');
Route::post('/updateEvent', '\App\Http\Controllers\EventController@update');
Route::post('/deleteEvent', '\App\Http\Controllers\EventController@delete');