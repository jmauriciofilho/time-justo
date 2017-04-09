<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'date',
	    'address',
	    'gamesPlayed',
    ];

    public function users()
    {
    	return $this->belongsToMany(User::class, 'guest_players', 'game_id', 'user_id');
    }
}
