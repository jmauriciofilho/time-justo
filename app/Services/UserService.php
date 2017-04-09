<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 08/04/17
 * Time: 15:15
 */

namespace App\Services;


use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
	private $user;

	function __construct(User $user)
	{
		$this->user = $user;
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

	public function allUsers()
	{
		$users = User::all();

		return json_encode($users);
	}
}