<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 17/04/17
 * Time: 13:16
 */

namespace App\Services;


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
			return 200;
		} else {
			return 400;
		}
	}

	public function update(Request $request)
	{
		$isUpdate = $this->group->where('id', $request->get('id'))->update($request->all());

		if ($isUpdate){
			return 200;
		} else{
			return 400;
		}
	}

	public function delete(Request $request)
	{
		$isDelete = $this->group->where('id', $request->get('id'))->delete();

		if ($isDelete){
			return 200;
		}else{
			return 400;
		}
	}

	public function allGroups()
	{
		$groups = Group::all();

		return json_encode($groups);
	}
}