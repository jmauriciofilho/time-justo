<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'owner',
    	'type',
    	'date',
	    'minimumUsers',
	    'maximumUsers',
	    'valueToBePaid',
	    'costByUser',
	    'address',
	    'isEventConfirmed',
    ];

    public function setOwner()
    {
    	return $this->hasOne(User::class);
    }

    public function users()
    {
    	return $this->belongsToMany(User::class, 'guest_players', 'game_id', 'user_id');
    }
}
