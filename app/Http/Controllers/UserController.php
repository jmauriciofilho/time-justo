<?php

namespace App\Http\Controllers;

use App\Services\GuestPlayersService;
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

	public function setConfirmParticipation(Request $request)
	{
		return GuestPlayersService::class->setConfirmParticipation($request);
	}

	public function addUserGroup(Request $request)
	{
		return $this->userService->addUserGroup($request);
	}

	public function makeFriends(Request $request)
	{
		return $this->userService->makeFriends($request);
	}

	public function myFriends(Request $request)
	{
		return $this->userService->myFriends($request);
	}

    public function allUsers()
    {
    	return $this->userService->allUsers();
    }
}
