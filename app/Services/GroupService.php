<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 17/04/17
 * Time: 13:16
 */

namespace App\Services;


use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupService
{
	private $group;

	function __construct(Group $group)
	{
		$this->group = $group;
	}

	public function create(Request $request)
	{
	    DB::transaction(function() use ($request) {
            $this->group->create($request->all());
        });
	}

	public function update(Request $request)
	{
        $group = Group::find($request->get('id'));
        if (!empty($group)){
            DB::transaction(function () use ($request) {
                $this->group->where('id', $request->get('id'))->update($request->all());
            });
        }else{
            throw new \Exception();
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