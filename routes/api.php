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
Route::post('/loginApp', '\App\Http\Controllers\UserController@loginApp');
Route::get('/allUsers', '\App\Http\Controllers\UserController@allUsers');
Route::post('/createUser', '\App\Http\Controllers\UserController@create');
Route::post('/updateUser', '\App\Http\Controllers\UserController@update');
Route::post('/deleteUser', '\App\Http\Controllers\UserController@delete');
Route::post('/setOverall', '\App\Http\Controllers\UserController@setOverall');
Route::post('/setGoalsScored', '\App\Http\Controllers\UserController@setGoalsScored');
Route::post('/invitePlayers', '\App\Http\Controllers\UserController@invitePlayers');
Route::post('/setConfirmParticipation', '\App\Http\Controllers\UserController@setConfirmParticipation');
Route::post('/addUserGroup', '\App\Http\Controllers\UserController@addUserGroup');
Route::post('/makeFriends', '\App\Http\Controllers\UserController@makeFriends');
Route::post('/myFriends', '\App\Http\Controllers\UserController@myFriends');

/*
 * Rotas de eventos...
 */
Route::get('/allEvents', '\App\Http\Controllers\EventController@allEvents');
Route::post('/createEvent', '\App\Http\Controllers\EventController@create');
Route::post('/updateEvent', '\App\Http\Controllers\EventController@update');
Route::post('/deleteEvent', '\App\Http\Controllers\EventController@delete');
Route::post('/setEventIsConfirmation', '\App\Http\Controllers\EventController@setIsConfirmation');
Route::post('/eventAttendance', '\App\Http\Controllers\EventController@eventAttendance');

/*
 * Rotas de grupos...
 */
Route::get('/allGroups', '\App\Http\Controllers\GroupController@allGroups');
Route::get('/createGroup', '\App\Http\Controllers\GroupController@create');
Route::get('/updateGroup', '\App\Http\Controllers\GroupController@update');
Route::get('/deleteGroup', '\App\Http\Controllers\GroupController@delete');