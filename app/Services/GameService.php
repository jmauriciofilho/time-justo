<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 09/04/17
 * Time: 00:03
 */

namespace app\Services;


use App\Models\Game;
use Illuminate\Http\Request;

class GameService
{
	private $game;

	function __construct(Game $game)
	{
		$this->game = $game;
	}

	public function create(Request $request)
	{
		$createGame = $this->game->create($request->all());

		if ($createGame){
			return "Evento criado com sucesso!";
		}else{
			return "Erro ao criar evento!";
		}
	}

	public function update(Request $request)
	{
		$updateGame = $this->game->where('id', $request->get('id'))->update($request->all());

		if ($updateGame){
			return "Evento atualizado com sucesso!";
		}else{
			return "Erro ao atualizar evento!";
		}
	}

	public function delete(Request $request)
	{
		$deleteGame = $this->game->where('id', $request->get('id'))->delete();

		if ($deleteGame){
			return "Evento excluido com sucesso!";
		}else{
			return "Erro ao excluir evento!";
		}
	}

	public function allGames()
	{
		$games = Game::all();

		return json_encode($games);
	}

}