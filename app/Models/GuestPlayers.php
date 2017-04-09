<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestPlayers extends Model
{
    protected $fillable = [
    	'game_id',
	    'user_id',
	    'confirmParticipation',
    ];
}
