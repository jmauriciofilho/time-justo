<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 08/04/17
 * Time: 15:15
 */

namespace App\Services;

use App\Http\Controllers\MediaController;
use App\Models\Event;
use App\Models\Group;
use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
	private $user;
	private $media;

	function __construct(User $user, MediaController $media)
	{
		$this->user = $user;
		$this->media = $media;
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
                'cod' => 200,
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'media_id' => $user->media_id,
                'token_api' => $user->token_api
            ];

			$json = new Collection();
            $json->put('response', $userLoggedIn);
			//dd($json);

            return json_encode($json->toArray());
		}
		return 400;
	}

	public function isLogged(Request $request)
	{
		$user = User::find($request->get('id'));
		if($request->get('token_api') == $user->token_api){
			return 1;
		}else{
			return 0;
		}
	}

	public function logoutApp(Request $request)
	{
		$user = User::find($request->get('id'));
		if ($user != null){
            $user->token_api = null;
            $user->save();
            return 200;
        }else{
		    return 400;
        }

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
		return 200;
	}

	public function update(Request $request)
	{
		$this->user->where('id', $request->get('id'))->update($request->all());

		return 200;
	}

	public function changePassword(Request $request)
	{
		$request->merge(['password' => bcrypt($request->get("password"))]);
		$user = User::find($request->get('id'));
		$user->password = $request->get('password');
		$user->save();
		return 200;
	}

	public function delete(Request $request)
	{
		$deleteUser = $this->user->where('id', $request->get('id'))->delete();

		if ($deleteUser){
			return 200;
		}else{
			return 400;
		}
	}

	public function setOverall(Request $request)
	{
		$user = User::find($request->get('id'));

		$nota = $request->get('nota');
		$somaNota = $request->get('somaNota');
		$coute = $request->get('coute');

		$user->calculateOverall($nota, $somaNota, $coute);

		$user->save();

		return 200;
	}

	public function setGoalsScored(Request $request)
	{
		$user = User::find($request->get('id'));

		$user->goalsScored = $request->get('goalsScored');

		$user->save();

		return 200;
	}

	public function invitePlayers(Request $request)
	{
		$users = $request->get('user_id');

		$event = Event::find($request->get('event_id'));

		$event->users()->attach($users);

		return 200;
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

		$user->users()->attach($friend);

		return 200;
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
        $user = User::find($request->get('id'));

        if ($user != null){
            return json_encode($user);
        }else{
            return 404;
        }

    }

	public function allUsers()
	{
		$users = User::all();

		return json_encode($users);
	}
}