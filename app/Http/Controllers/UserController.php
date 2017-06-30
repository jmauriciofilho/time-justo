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
    	return $this->userService->isLogged($request);
    }

    public function logout(Request $request)
    {
    	return $this->userService->logoutApp($request);
    }

    public function create(UserRequest $request)
    {
        try{
            $this->userService->create($request);
            return $this->httpResponses->success();
        }catch (QueryException $exception){
            return $this->httpResponses->reponseError('Este e-mail já está cadastrado!');
        }
    }

    public function update(UserUpdateRequest $request)
    {
    	return $this->userService->update($request);
    }

    public function changePassword(UserChangePasswordRequest $request)
    {
    	return $this->userService->changePassword($request);
    }

    public function delete(Request $request)
    {
    	return $this->userService->delete($request);
    }

	public function setOverall(Request $request)
	{
		return $this->userService->setOverall($request);
	}

	public function setGoalsScored(Request $request)
	{
		return $this->userService->setGoalsScored($request);
	}

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
        return $this->userService->returnUser($request);
    }

    public function allUsers()
    {
    	return $this->userService->allUsers();
    }
}
