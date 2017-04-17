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

	}

	public function update(Request $request)
	{

	}

	public function delete(Request $request)
	{

	}

	public function allGroups()
	{

	}
}