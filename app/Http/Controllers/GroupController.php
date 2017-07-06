<?php

namespace App\Http\Controllers;

use App\Exceptions\GroupException;
use App\Http\Responses\HttpResponses;
use App\Services\GroupService;
use Illuminate\Database\QueryException;
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
            if ($request->has('owner') && $request->has('type') && $request->has('name')) {
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
	    try {
            if ($request->has('id') && $request->has('owner')) {
                $this->groupService->update($request);
                return $this->httpResponses->success();
            } else {
                return $this->httpResponses->errorParameters();
            }
        }catch (\Exception $e){
            return $this->httpResponses->reponseError("O Valor dos parâmetros possui um erro.");
        }

	}

	public function delete(Request $request)
	{
	    try{
            if($request->has('id')) {
                $this->groupService->delete($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
	        return $this->httpResponses->reponseError("Grupo não existe.");
        }
	}

	public function returnGroup(Request $request)
    {
        try{
            if ($request->has('id')){
                $group = $this->groupService->returnGroup($request);
                return $this->httpResponses->reponseSuccess($group);
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Grupo não existe.");
        }
    }

	public function allGroups()
	{
		$groups = $this->groupService->allGroups();
		return $this->httpResponses->reponseSuccess($groups);
	}
}
