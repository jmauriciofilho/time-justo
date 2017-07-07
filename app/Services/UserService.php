<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 08/04/17
 * Time: 15:15
 */

namespace App\Services;

use App\Http\Controllers\MediaController;
use App\Http\Responses\HttpResponses;
use App\Models\Event;
use App\Models\Group;
use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class UserService
{
	private $user;
	private $media;
    private $httpResponses;

	function __construct(User $user, MediaController $media, HttpResponses $httpResponses)
	{
		$this->user = $user;
		$this->media = $media;
		$this->httpResponses = $httpResponses;
	}

	public function loginApp(Request $request)
	{
        $has = Auth::attempt(['email' => $request->get('email'),
            'password' => $request->get('password')]);

        if ($has) {

            $token = str_random(16);

            $user = User::all()->where('email', '=', $request->get('email'))->first();
            $user->token_api = $token;
            $user->save();

            $userLoggedIn = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'media_id' => $user->media_id,
                'token_api' => $user->token_api
            ];

            return $userLoggedIn;
        }else{
            return null;
        }
	}

	public function isLogged(Request $request)
	{
	    return User::where('token_api', $request->get('token_api'))->get()->first();
	}

	public function logoutApp(Request $request)
	{
        $user = User::where('token_api', $request->get('token_api'))->get()->first();
        if (!empty($user)){
            $user->token_api = null;
            $user->save();
            return true;
        }
        return false;
	}

	public function create(Request $request)
	{
	    DB::transaction(function() use ($request)
        {
            $role = Role::find(2);
            $role_id = $role->id;
            $request->merge(['password' => bcrypt($request->get("password"))]);
            $user = $this->user->create($request->all());
            $user->roles()->attach($role_id);
        });
	}

	public function update(Request $request)
	{
        DB::beginTransaction();
	    $update = $this->user->where('token_api', $request->get('token_api'))
            ->update($request->all());
	    if ($update){
            DB::commit();
        }else{
            DB::rollBack();
            throw new \ErrorException();
        }
	}

	public function changePassword(Request $request)
	{
        DB::beginTransaction();
	    $request->merge(['password' => bcrypt($request->get("password"))]);
        $user = User::where('token_api', $request->get('token_api'))->get()->first();

        if (!empty($user)) {
            $user->password = $request->get('password');
            if($user->save()){
                DB::commit();
            }
        }else{
            DB::rollBack();
            throw new \ErrorException();
        }
	}

	public function delete(Request $request)
	{
        DB::beginTransaction();
	    if ($this->validationTokenApi($request->get('token_api'))) {
            $delete = $this->user->where('token_api', $request->get('token_api'))->delete();
            if ($delete) {
                DB::commit();
            }
        }else{
            DB::rollBack();
	        throw new \ErrorException();
        }
	}

//	public function setOverall(Request $request)
//	{
//		$user = User::find($request->get('id'));
//
//		if ($user != null){
//            $nota = $request->get('nota');
//            $somaNota = $request->get('somaNota');
//            $coute = $request->get('coute');
//
//            $user->calculateOverall($nota, $somaNota, $coute);
//
//            $user->save();
//
//            return 200;
//        }else{
//		    return 400;
//        }
//
//	}
//
//	public function setGoalsScored(Request $request)
//	{
//		$user = User::find($request->get('id'));
//
//		$user->goalsScored = $request->get('goalsScored');
//
//		$user->save();
//
//		return 200;
//	}

	public function invitePlayers(Request $request)
	{
        $user = User::find($request->get('user_id'));
		$event = Event::find($request->get('event_id'));

		if (!empty($user) && !empty($event)){
            DB::transaction(function () use ($event, $user)
            {
                $event->users()->attach($user);
            });
        }else{
            throw new \ErrorException();
        }

	}

	public function addUserGroup(Request $request)
	{
        $group = Group::find($request->get('group_id'));
        if (!empty($group)){
            DB::transaction(function () use ($request, $group){
                $group->users()->attach($request->get('id_user'));
                $group->quantUsers = $group->quantUsers + 1;
                $group->save();
            });
        }else{
            throw new \ErrorException();
        }

	}

	public function makeFriends(Request $request)
	{
		$user = User::where('token_api', $request->get('token_api'))->get()->first();
		$friend = User::where('email', $request->get('email_friend'))->get()->first();

		if (!empty($user) && !empty($friend)){
            DB::transaction(function () use ($user, $friend)
            {
                $user->users()->attach($friend);
            });
        }else{
		    throw new \ErrorException();
        }

	}

	public function removeFriends(Request $request)
    {
        $user = User::where('token_api', $request->get('token_api'))->get()->first();
        $friend = User::where('email', $request->get('email_friend'))->get()->first();

        if (!empty($user) && !empty($friend)){
            DB::transaction(function () use ($user, $friend)
            {
                DB::table('friends')
                    ->where('user_id', $user->id)
                    ->where('user_friend_id', $friend->id)
                    ->delete();
            });
        }else{
            throw new \ErrorException();
        }
    }

	public function myFriends(Request $request)
	{
		$user = User::where('token_api', $request->get('token_api'))->get()->first();

		if (!empty($user)) {
            return $user->users;
        }else{
		    throw new \ErrorException();
        }
	}

	public function addAvatar(Request $request)
    {
        $file = $request->file('image');
        $user = User::where('token_api', $request->get('token_api'))->get()->first();
        if(!empty($user)){
            if ($user->media_id != null){
                DB::transaction(function () use ($user, $file) {
                    $id = Media::find($user->media_id);
                    $this->media->deleteMedia($id);
                    $avatar = $this->media->uploadMedia($file);
                    $user->media_id = $avatar->id;
                    $user->save();
                });

            }else{
                DB::transaction(function () use ($user, $file) {
                    $avatar = $this->media->uploadMedia($file);
                    $user->media_id = $avatar->id;
                    $user->save();
                });
            }
        }else{
            throw new \ErrorException();
        }

    }

    public function returnUser(Request $request)
    {
        return User::where('email', $request->get('email'))->get()->first();
    }

	public function allUsers()
	{
		return User::all();
	}

	private function validationTokenApi($token_api)
    {
        $user = User::where('token_api', $token_api)->get()->first();
        return !empty($user);
    }
}