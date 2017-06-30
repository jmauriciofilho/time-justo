<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Responses\HttpResponses;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    private $httpResponses;

    function __construct(UserService $userService, HttpResponses $httpResponses)
    {
    	$this->userService = $userService;
    	$this->httpResponses = $httpResponses;
    }

    public function loginApp(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')){
            return $this->httpResponses->errorParameters();
        }else{
            $userLoggedIn = $this->userService->loginApp($request);
            if (empty($userLoggedIn)){
                return $this->httpResponses->userNotFound();
            }else{
                return $this->httpResponses->reponseSuccess($userLoggedIn);
            }
        }
    }

    public function isLogged(Request $request)
    {
        if($request->has('token_api')) {
            $user = $this->userService->isLogged($request);
    	    return $this->httpResponses->reponseSuccess(!empty($user));
        }
        return $this->httpResponses->errorParameters();
    }

    public function logout(Request $request)
    {
        if ($request->has('token_api')){
    	    $isLogout = $this->userService->logoutApp($request);
    	    if ($isLogout) {
                return $this->httpResponses->success();
            }
            return $this->httpResponses->reponseSuccess('Token inválido!');
        }
        return $this->httpResponses->errorParameters();
    }

    public function create(UserRequest $request)
    {
        try{
            $this->userService->create($request);
            return $this->httpResponses->success();
        }catch (QueryException $e){
            return $this->httpResponses->reponseError('Este e-mail já está cadastrado!');
        }
    }

    public function update(UserUpdateRequest $request)
    {
        $isUpdate = $this->userService->update($request);
        if ($isUpdate){
            return $this->httpResponses->success();
        }else{
            return $this->httpResponses->reponseError("Erro na atualização.");
        }
    }

    public function changePassword(UserChangePasswordRequest $request)
    {
        try{
            $isChange = $this->userService->changePassword($request);
            if ($isChange){
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->reponseError("Erro na alteração da senha.");
            }
        }catch (\Exception $exception){
            return $this->httpResponses->error();
        }

    }

    public function delete(Request $request)
    {
        if ($request->has('token_api')){
            $isDelete = $this->userService->delete($request);

            if ($isDelete){
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->reponseError("Erro na requisição.");
            }
        }else{
            return $this->httpResponses->errorParameters();
        }

    }

//	public function setOverall(Request $request)
//	{
//		return $this->userService->setOverall($request);
//	}
//
//	public function setGoalsScored(Request $request)
//	{
//		return $this->userService->setGoalsScored($request);
//	}

	public function invitePlayers(Request $request)
	{
		return $this->userService->invitePlayers($request);
	}

	public function addUserGroup(Request $request)
	{
		return $this->userService->addUserGroup($request);
	}

	public function makeFriends(Request $request)
	{
		return $this->userService->makeFriends($request);
	}

	public function removeFriends(Request $request)
    {
        return $this->userService->removeFriends($request);
    }

	public function myFriends(Request $request)
	{
		return $this->userService->myFriends($request);
	}

	public function addAvatar(Request $request)
    {
        return $this->userService->addAvatar($request);
    }

    public function returnUser(Request $request)
    {
        if ($request->has('email')) {
            $user = $this->userService->returnUser($request);
            if (!empty($user)){
                return $this->httpResponses->reponseSuccess($user);
            }else{
                return $this->httpResponses->reponseError('Usuário não encontrado.');
            }
        }
        return $this->httpResponses->errorParameters();
    }

    public function allUsers()
    {
    	$users = $this->userService->allUsers();

    	return json_encode($users);
    }
}
