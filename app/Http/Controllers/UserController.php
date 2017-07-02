<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\HttpResponses;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request)
    {
        try{
            if ($request->has('token_api')){
                if ($request->has('email')) {
                    $validator = Validator::make($request->all(), [
                        'email' => 'email'
                    ]);
                    if ($validator->fails()){
                        return $this->httpResponses->reponseError("Formato do email incorreto");
                    }
                }
                $this->userService->update($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Token inválido");
        }
    }

    public function changePassword(Request $request)
    {
        try{
            if ($request->has('token_api') && $request->has('password')){
                $this->userService->changePassword($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Token inválido");
        }

    }

    public function delete(Request $request)
    {
        try{
            if ($request->has('token_api')){
                $this->userService->delete($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Token inválido");
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
        try {
            if ($request->has('user_id') && $request->has('event_id')) {
                $this->userService->invitePlayers($request);
                return $this->httpResponses->success();
            } else {
                return $this->httpResponses->errorParameters();
            }
        }catch (QueryException $e){
            return $this->httpResponses->reponseError("Convite já realizado.");
        }catch (\ErrorException $e){
	        return $this->httpResponses->reponseError("Id's inválidos.");
        }
	}

	public function addUserGroup(Request $request)
	{
		return $this->userService->addUserGroup($request);
	}

	public function makeFriends(Request $request)
	{
	    try{
            if ($request->has('token_api') && $request->has('email_friend')) {
                $this->userService->makeFriends($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (QueryException $e){
	        return $this->httpResponses->reponseError("Amizade já estabelecida.");
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Informações inválidos.");
        }

	}

	public function removeFriends(Request $request)
    {
        try{
            if ($request->has('token_api') && $request->has('email_friend')){
                $this->userService->removeFriends($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Informação não encontrada.");
        }
    }

	public function myFriends(Request $request)
	{
	    try{
            if ($request->has('token_api')){
                $friends = $this->userService->myFriends($request);
                return $this->httpResponses->reponseSuccess($friends);
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (\ErrorException $exception) {
            return $this->httpResponses->reponseError("Informação não encontrada.");
        }
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
