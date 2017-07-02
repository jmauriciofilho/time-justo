<?php
/**
 * Created by IntelliJ IDEA.
 * User: mauricio
 * Date: 10/04/17
 * Time: 10:52
 */

namespace App\Services;


use App\Models\GuestPlayers;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestPlayersService
{
	private $guestPlayers;

	function __construct(GuestPlayers $guestPlayers)
	{
		$this->guestPlayers = $guestPlayers;
	}

	public function setConfirmParticipation(Request $request)
	{
        DB::beginTransaction();
	    $user = User::where('token_api', $request->get('token_api'))->get()->first();
	    if (!empty($user)){
            $guestPlayers = $this->guestPlayers->where('event_id', $request->get('event_id'))
                ->where('user_id', $user->id)->first();
            if (!empty($guestPlayers)){
                $guestPlayers->confirmParticipation = $request->get('confirmParticipation');
                if($guestPlayers->save()){
                    DB::commit();
                }else{
                    DB::rollBack();
                }
            }else{
                DB::rollBack();
                throw new \Exception();
            }
        }else{
            DB::rollBack();
            throw new \ErrorException();
        }
	}

}