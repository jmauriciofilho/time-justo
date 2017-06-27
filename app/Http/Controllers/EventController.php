<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $eventService;

    function __construct(EventService $eventService)
    {
    	$this->eventService = $eventService;
    }

    public function create(Request $request)
    {
    	return $this->eventService->create($request);
    }

    public function update(Request $request)
    {
    	return $this->eventService->update($request);
    }

    public function delete(Request $request)
    {
    	return $this->eventService->delete($request);
    }

	public function setIsConfirmation(Request $request)
	{
		return $this->eventService->setIsConfirmation($request);
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
    	return $this->eventService->allEvents();
    }
}
