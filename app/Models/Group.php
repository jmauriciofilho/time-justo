<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	const PRIVADO = 'privado';
	const PUBLICO = 'público';

    protected $fillable = [
        'name',
    	'owner',
	    'type',
	    'quantUsers'
    ];

    public function setOwner()
    {
    	return $this->hasOne(User::class);
    }

    public function users()
    {
    	return $this->belongsToMany(User::class, 'members', 'group_id', 'user_id');
    }
}
