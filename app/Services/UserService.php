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
			$json = new Collection();
			$json->put('user', $user->toArray());
			$json->put('role', $user->roles->toArray());
			return json_encode($json->toArray());
		}
		return "Usuário não encontrado!";
	}

	public function isLogged(Request $request)
	{
		$user = User::find($request->get('id'));
		if($request->get('token_api') == $user->token_api){
			return "1";
		}else{
			return "0";
		}
	}

	public function logoutApp(Request $request)
	{
		$user = User::find($request->get('id'));
		$user->token_api = null;
		$user->save();
		return "Usuário foi deslogado!";
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
		return "Usuário criado com sucesso.";
	}

	public function update(Request $request)
	{
		$this->user->where('id', $request->get('id'))->update($request->all());

		return "Usuário atualizado com sucesso!";
	}

	public function changePassword(Request $request)
	{
		$request->merge(['password' => bcrypt($request->get("password"))]);
		$user = User::find($request->get('id'));
		$user->password = $request->get('password');
		$user->save();
		return "Senha alterada com sucesso!";
	}

	public function delete(Request $request)
	{
		$deleteUser = $this->user->where('id', $request->get('id'))->delete();

		if ($deleteUser){
			return "Usuário removido com sucesso!";
		}else{
			return "Erro ao remover usuário!";
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

		return "Média registrada!";
	}

	public function setGoalsScored(Request $request)
	{
		$user = User::find($request->get('id'));

		$user->goalsScored = $request->get('goalsScored');

		$user->save();

		return "Numero de goals registrado!";
	}

	public function invitePlayers(Request $request)
	{
		$users = $request->get('user_id');

		$event = Event::find($request->get('event_id'));

		$event->users()->attach($users);

		return "Usuários convidados com sucesso!";
	}

	public function addUserGroup(Request $request)
	{
		$users = $request->get('user_id');

		$group = Group::find($request->get('group_id'));

		$group->quantUsers = $group->quantUsers + count($users);
		$group->save();

		$group->users()->attach($users);

		return "Usuários adicionados ao grupo com sucesso!";
	}

	public function makeFriends(Request $request)
	{
		$user = User::find($request->get('user_id'));
		$friend = User::find($request->get('user_friend_id'));

		$user->users()->attach($friend);

		return "Amizade criada com sucesso!";
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
            return "Avatar alterado com sucesso!";
        }else{
            $avatar = $this->media->uploadMedia($file);
            $user->media_id = $avatar->id;
            $user->save();
            return "Avatar salvo com sucesso!";
        }

    }

	public function allUsers()
	{
		$users = User::all();

		return json_encode($users);
	}
}