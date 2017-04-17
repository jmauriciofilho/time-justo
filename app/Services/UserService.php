<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 08/04/17
 * Time: 15:15
 */

namespace App\Services;

use App\Models\Event;
use App\Models\GuestPlayers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
	private $user;

	function __construct(User $user)
	{
		$this->user = $user;
	}

	public function loginApp(Request $request)
	{
		$has = Auth::attempt(['email' => $request->get('email'),
			'password' => $request->get('password')]);
		if ($has) {
			$user = User::all()->where('email', '=', $request->get('email'))->first();
			$json = new Collection();
			$json->put('user', $user->toArray());
			$json->put('role', $user->roles->toArray());
			return json_encode($json->toArray());
		}
		return "Usuário não encontrado!";
	}

	public function create(Request $request)
	{
		DB::transaction(function() use ($request)
		{
			$role = $request->get('role_id');
			$request->merge(['password' => bcrypt($request->get("password"))]);
			$user = $this->user->create($request->all());
			$user->roles()->attach($role);
		});
		return "Usuário criado com sucesso.";
	}

	public function update(Request $request)
	{
		$request->merge(['password' => bcrypt($request->get("password"))]);
		$updateUser = $this->user->where('id', $request->get('id'))->update($request->all());

		if ($updateUser){
			return "Usuário atualizado com sucesso!";
		}else{
			return "Erro ao atualizar usuário!";
		}
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

		$event = Event::find($request->get('game_id'));

		$event->users()->attach($users);

		return "Usuários convidados com sucesso!";
	}

	public function makeFriends()
	{

	}

	public function myFriends()
	{

	}

	public function allUsers()
	{
		$users = User::all();

		return json_encode($users);
	}
}