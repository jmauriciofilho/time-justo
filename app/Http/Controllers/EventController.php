<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $gameService;

    function __construct(EventService $gameService)
    {
    	$this->gameService = $gameService;
    }

    public function create(Request $request)
    {
    	return $this->gameService->create($request);
    }

    public function update(Request $request)
    {
    	return $this->gameService->update($request);
    }

    public function delete(Request $request)
    {
    	return $this->gameService->delete($request);
    }

    public function allEvents()
    {
    	return $this->gameService->allGames();
    }
}
