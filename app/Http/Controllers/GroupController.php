<?php

namespace App\Http\Controllers;

use App\Http\Responses\HttpResponses;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
	private $groupService;
	private $httpResponses;

	function __construct(GroupService $groupService, HttpResponses $httpResponses)
	{
		$this->groupService = $groupService;
		$this->httpResponses = $httpResponses;
	}

	public function create(Request $request)
	{
	    try{
            if ($request->has('owner') && $request->has('type')) {
                $this->groupService->create($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\PDOException $e) {
            return $this->httpResponses->reponseError("Erro no valor dos parâmetros owner ou type.");
        }

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
