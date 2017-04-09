<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    function __construct(UserService $userService)
    {
    	$this->userService = $userService;
    }

    public function loginApp(Request $request)
    {
    	return $this->userService->loginApp($request);
    }

    public function create(Request $request)
    {
    	return $this->userService->create($request);
    }

    public function update(Request $request)
    {
    	return $this->userService->update($request);
    }

    public function delete(Request $request)
    {
    	return $this->userService->delete($request);
    }

	public function setConfirmParticipation(Request $request)
	{
		return $this->userService->setConfirmParticipation($request);
	}

    public function allUsers()
    {
    	return $this->userService->allUsers();
    }
}
