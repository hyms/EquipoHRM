<?php

namespace App;

use App\Models\History;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Usuarios extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $table = 'usuarios';
    protected static $tableHistory = 'usuarios_history';

    public static function VerificarUsuario($username, $password)
    {
        $user = self::where([
            ['username', $username],
        ])->first();
        if (!$user) {
            return false;
        }
        if (!Hash::check($password, $user->password, [])) {
            return false;
        }
        return $user;
    }


    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select(
                'id', 'username', 'password', 'email', 'alias', 'api_token', 'estado', 'ultimo_acceso', 'rol'
            )
                ->where([
                    ['id', $id],
                ])->first();
            History::save(self::$tableHistory, $data);
        }
    }

}
