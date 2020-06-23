<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
    use Notifiable,HasApiTokens;

    protected $fillable = ['email', 'password'];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
