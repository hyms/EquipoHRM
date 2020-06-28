<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = ['email', 'password'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function VerificarUsuario($email,$password)
    {
        $user = Usuarios::where('email', $email)->first();
        if (!$user) {
            return false;
        }
        if (!Hash::check($password, $user->password, [])) {
            return false;
        }
        return $user;
    }

    public static function GetAll()
    {
        return DB::table('usuarios')
            ->get()
            ->all();
    }

    public static function set(array $usuario)
    {
        return DB::table('usuarios')->insertGetId([]);
    }

}
