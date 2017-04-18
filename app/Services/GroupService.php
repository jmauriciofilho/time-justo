<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 17/04/17
 * Time: 13:16
 */

namespace app\Services;


use App\Models\Group;
use Illuminate\Http\Request;

class GroupService
{
	private $group;

	function __construct(Group $group)
	{
		$this->group = $group;
	}

	public function create(Request $request)
	{
		$isCreate = $this->group->create($request->all());

		if ($isCreate){
			return "Grupo creado com sucesso!";
		} else {
			return "Erro ao criar grupo!";
		}
	}

	public function update(Request $request)
	{
		$isUpdate = $this->group->where('id', $request->get('id'))->update($request->all());

		if ($isUpdate){
			return "Grupo atualizado com sucesso!";
		} else{
			return "Erro ao atualizar grupo!";
		}
	}

	public function delete(Request $request)
	{
		$isDelete = $this->group->where('id', $request->get('id'))->delete();

		if ($isDelete){
			return "Grupo excluido com sucesso!";
		}else{
			return "Erro ao excluir grupo!";
		}
	}

	public function allGroups()
	{
		$groups = Group::all();

		return json_encode($groups);
	}
}