<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Responses\HttpResponses;
use App\Services\EventService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $eventService;
    private $httResponses;

    function __construct(EventService $eventService, HttpResponses $httpResponses)
    {
    	$this->eventService = $eventService;
    	$this->httResponses = $httpResponses;
    }

    public function create(EventRequest $request)
    {
        try{
            $event = $this->eventService->create($request);
            $response = [
                'id_event' => $event->id
            ];
            return $this->httResponses->reponseSuccess($response);
        }catch (QueryException $e){
            return $this->httResponses->reponseError("Parâmetro type incorreto");
        }catch (\ErrorException $e){
            return $this->httResponses->errorParameters();
        }
    }

    public function update(Request $request)
    {
        try{
            if ($request->has('id')) {
                $this->eventService->update($request);
                return $this->httResponses->success();
            }else{
                return $this->httResponses->reponseError("Parâmetro id obrigatorio.");
            }
        }catch (QueryException $e){
            return $this->httResponses->errorParameters();
        }catch (\ErrorException $e){
            return $this->httResponses->errorParameters();
        }

    }

    public function delete(Request $request)
    {
        try{
            if ($request->has('id')) {
                $this->eventService->delete($request);
                return $this->httResponses->success();
            }else{
                return $this->httResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httResponses->reponseError("Id inválido.");
        }

    }

	public function setIsConfirmation(Request $request)
	{
	    try{
            if ($request->has('event_id')){
                $this->eventService->setIsConfirmation($request);
                return $this->httResponses->success();
            }else{
                return $this->httResponses->errorParameters();
            }
        }catch (\ErrorException $e){
            return $this->httResponses->reponseError("Erro ao alterar confirmação do evento");
        }catch (\Exception $e){
            return $this->httResponses->reponseError("Evento não existe.");
        }
	}

	public function addEventImage(Request $request)
    {
        return $this->eventService->addEventImage($request);
    }

    public function returnEvent(Request $request)
    {
        return $this->eventService->returnEvent($request);
    }

	public function eventAttendance(Request $request)
	{
		return $this->eventService->eventAttendance($request);
	}

    public function allEvents()
    {
    	$events = $this->eventService->allEvents();
    	return $this->httResponses->reponseSuccess($events);
    }
}
