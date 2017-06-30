<?php

use Illuminate\Http\Request;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

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
Route::post('/isLogged', '\App\Http\Controllers\UserController@isLogged');
Route::post('/logout', '\App\Http\Controllers\UserController@logout');
Route::get('/allUsers', '\App\Http\Controllers\UserController@allUsers');
Route::post('/returnUser', '\App\Http\Controllers\UserController@returnUser');
Route::post('/createUser', '\App\Http\Controllers\UserController@create');
Route::post('/updateUser', '\App\Http\Controllers\UserController@update');
Route::post('/changePassword', '\App\Http\Controllers\UserController@changePassword');
Route::post('/deleteUser', '\App\Http\Controllers\UserController@delete');
Route::post('/setOverall', '\App\Http\Controllers\UserController@setOverall');
Route::post('/setGoalsScored', '\App\Http\Controllers\UserController@setGoalsScored');
Route::post('/invitePlayers', '\App\Http\Controllers\UserController@invitePlayers');
Route::post('/addUserGroup', '\App\Http\Controllers\UserController@addUserGroup');
Route::post('/makeFriends', '\App\Http\Controllers\UserController@makeFriends');
Route::post('/removeFriends', '\App\Http\Controllers\UserController@removeFriends');
Route::post('/myFriends', '\App\Http\Controllers\UserController@myFriends');
Route::post('/setConfirmParticipation', '\App\Http\Controllers\GuestPlayersController@setConfirmParticipation');
Route::post('/addAvatar', '\App\Http\Controllers\UserController@addAvatar');

/*
 * Rotas de eventos...
 */
Route::get('/allEvents', '\App\Http\Controllers\EventController@allEvents');
Route::post('/createEvent', '\App\Http\Controllers\EventController@create');
Route::post('/updateEvent', '\App\Http\Controllers\EventController@update');
Route::post('/deleteEvent', '\App\Http\Controllers\EventController@delete');
Route::post('/setEventIsConfirmation', '\App\Http\Controllers\EventController@setIsConfirmation');
Route::post('/eventAttendance', '\App\Http\Controllers\EventController@eventAttendance');
Route::post('/returnEvent', '\App\Http\Controllers\EventController@returnEvent');
Route::post('/addEventImage', '\App\Http\Controllers\EventController@addEventImage');

/*
 * Rotas de grupos...
 */
Route::get('/allGroups', '\App\Http\Controllers\GroupController@allGroups');
Route::post('/createGroup', '\App\Http\Controllers\GroupController@create');
Route::post('/updateGroup', '\App\Http\Controllers\GroupController@update');
Route::post('/deleteGroup', '\App\Http\Controllers\GroupController@delete');

/*
 * Rotas de media...
 */
Route::post('/upload-media', '\App\Http\Controllers\MediaController@uploadMedia');
Route::post('/delete-media', '\App\Http\Controllers\MediaController@deleteMedia');