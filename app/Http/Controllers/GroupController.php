<?php

namespace App\Http\Controllers;

use app\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
	private $groupService;

	function __construct(GroupService $groupService)
	{
		$this->groupService = $groupService;
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
