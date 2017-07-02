<?php

namespace App\Http\Controllers;

use App\Http\Responses\HttpResponses;
use App\Services\GuestPlayersService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class GuestPlayersController extends Controller
{
	private $guestPlayersService;
	private $httpResponses;

	function __construct(GuestPlayersService $guestPlayersService, HttpResponses $httpResponses)
	{
		$this->guestPlayersService = $guestPlayersService;
		$this->httpResponses = $httpResponses;
	}

	public function setConfirmParticipation(Request $request)
    {
        try{
            if ($request->has('event_id') && $request->has('token_api') && $request->has('confirmParticipation')) {
                $this->guestPlayersService->setConfirmParticipation($request);
                return $this->httpResponses->success();
            }else{
                return $this->httpResponses->errorParameters();
            }
        }catch (QueryException $e){
            return $this->httpResponses->reponseError("Parâmetro boolean incorreto.");
        }catch (\ErrorException $e){
            return $this->httpResponses->reponseError("Token inválido");
        }catch (\Exception $e){
            return $this->httpResponses->reponseError("Evento não existe");
        }
	}
}
