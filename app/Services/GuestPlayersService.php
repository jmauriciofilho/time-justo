<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 10/04/17
 * Time: 10:52
 */

namespace App\Services;


use App\Models\GuestPlayers;
use Illuminate\Http\Request;

class GuestPlayersService
{
	private $guestPlayers;

	function __construct(GuestPlayers $guestPlayers)
	{
		$this->guestPlayers = $guestPlayers;
	}

	public function setConfirmParticipation(Request $request)
	{
		$guestPlayers = $this->guestPlayers->where('id', $request->get('id'))->first();

		$guestPlayers->confirmParticipation = $request->get('confirmParticipation');

		$guestPlayers->save();

		return "Participação alterada!";
	}

}