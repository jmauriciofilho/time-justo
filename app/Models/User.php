<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
	    'email',
	    'password',
	    'token_api',
	    'media_id',
	    'appearances',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
	    'remember_token'
    ];

    public function calculateOverall($nota, $somaNotas, $couter)
    {
		$this->overall = ($nota + $somaNotas)/$couter;
    }

    public function roles()
    {
    	return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }

    public function users()
    {
    	return $this->belongsToMany(User::class, 'friends', 'user_id', 'user_friend_id');
    }

    public function avatar()
    {
    	return $this->hasOne(Media::class);
    }
}
