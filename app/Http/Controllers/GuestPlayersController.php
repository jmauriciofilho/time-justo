<?php

namespace App\Http\Controllers;

use app\Services\GuestPlayersService;
use Illuminate\Http\Request;

class GuestPlayersController extends Controller
{
	private $guestPlayersService;

	function __construct(GuestPlayersService $guestPlayersService)
	{
		$this->guestPlayersService = $guestPlayersService;
	}

	public function setConfirmParticipation(Request $request)
	{
		return $this->guestPlayersService->setConfirmParticipation($request);
	}
}
