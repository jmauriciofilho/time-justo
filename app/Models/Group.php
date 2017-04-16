<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	const PRIVADO = 'privado';
	const PUBLICO = 'público';

    protected $fillable = [
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
