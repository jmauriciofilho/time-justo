<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	const FUTEBOL = 'futebol';
	const BASKETBALL ='basketball';
	const VOLEI = 'vólei';
	const FUTSAL = 'futsal';

    protected $fillable = [
        'name',
        'owner',
    	'type',
    	'date',
	    'minimumUsers',
	    'maximumUsers',
	    'valueToBePaid',
	    'address',
	    'isEventConfirmed',
    ];

    public function setOwner()
    {
    	return $this->hasOne(User::class);
    }

    public function users()
    {
    	return $this->belongsToMany(User::class, 'guest_players', 'event_id', 'user_id');
    }
}
