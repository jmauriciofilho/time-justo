<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
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
		return $this->groupService->create($request);
	}

	public function update(Request $request)
	{
		return $this->groupService->update($request);
	}

	public function delete(Request $request)
	{
		return $this->groupService->delete($request);
	}

	public function allGroups()
	{
		return $this->groupService->allGroups();
	}
}
