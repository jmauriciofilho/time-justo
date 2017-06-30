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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
	    DB::transaction(function () use ($request)
        {
            $this->user->where('token_api', $request->get('token_api'))
                ->update($request->all());
        });
	}

	public function changePassword(Request $request)
	{
	    DB::transaction(function () use ($request){
            $request->merge(['password' => bcrypt($request->get("password"))]);
            $user = User::where('token_api', $request->get('token_api'))->get()->first();

            if (!empty($user)) {
                $user->password = $request->get('password');
                $user->save();
            }
        });
	}

	public function delete(Request $request)
	{
	    DB::transaction(function () use ($request){
            $this->user->where('token_api', $request->get('token_api'))->delete();
        });
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
		$users = $request->get('user_id');

        $user = User::find($users);
		$event = Event::find($request->get('event_id'));

		if ($user != null && $event != null){
            $event->users()->attach($users);
            return 200;
        }else{
		    return 404;
        }

	}

	public function addUserGroup(Request $request)
	{
		$users = $request->get('user_id');

		$group = Group::find($request->get('group_id'));

		$group->quantUsers = $group->quantUsers + count($users);
		$group->save();

		$group->users()->attach($users);

		return 200;
	}

	public function makeFriends(Request $request)
	{
		$user = User::find($request->get('user_id'));
		$friend = User::find($request->get('user_friend_id'));

		if ($user != null && $friend != null){
            $user->users()->attach($friend);
            return 200;
        }else{
		    return 404;
        }

	}

	public function removeFriends(Request $request)
    {
        $friends = DB::table('friends')
            ->where('user_id', $request->get('user_id'))
            ->where('user_friend_id', $request->get('user_friend_id'))
            ->delete();

        if ($friends != null){
            return 200;
        }else{
            return 404;
        }
    }

	public function myFriends(Request $request)
	{
		$user = User::find($request->get('id'));

		return json_encode($user->users);
	}

	public function addAvatar(Request $request)
    {
        $file = $request->file('image');
        $user = User::find($request->get('idUser'));
        if ($user->media_id != null){
            $id = Media::find($user->media_id);
            $this->media->deleteMedia($id);
            $avatar = $this->media->uploadMedia($file);
            $user->media_id = $avatar->id;
            $user->save();
            return 200;
        }else{
            $avatar = $this->media->uploadMedia($file);
            $user->media_id = $avatar->id;
            $user->save();
            return 200;
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
}