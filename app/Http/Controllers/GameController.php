<?php

namespace App\Http\Controllers;

use app\Services\GameService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    private $gameService;

    function __construct(GameService $gameService)
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

    public function allGames()
    {
    	return $this->gameService->allGames();
    }
}
