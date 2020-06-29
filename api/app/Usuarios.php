<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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

    public static function VerificarUsuario($email, $password)
    {
        $user = self::where([
            ['name', $email],
            ['borrado', 0]
        ])->first();
        if (!$user) {
            return false;
        }
        if (!Hash::check($password, $user->password, [])) {
            return false;
        }
        return $user;
    }

    protected static $tableC = 'usuarios';
    protected static $tableHistory = 'usuariosHistory';

    public static function GetAllC()
    {
        return DB::table(self::$tableC)
            ->select('id', 'name', 'alias', 'created_at', 'estado')
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }

    public static function GetC($id)
    {
        return DB::table(self::$tableC)
            ->select('id', 'name', 'alias', 'detail', 'created_at', 'estado', 'rol')
            ->where([
                ['id', $id],
                ['borrado', 0],
            ])
            ->get();
    }

    private static function insertC($values)
    {
        $id = DB::table(self::$tableC)
            ->insertGetId([
                'name' => $values['name'],
                'password' => Hash::make($values['password']),
                'alias' => $values['alias'],
                'detail' => (empty($values['detail']) ? '' : $values['detail']),
                'estado' => $values['estado'],
                'rol' => $values['rol'],
                'borrado' => 0,
                'api_token' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        self::history($id);
        return $id;
    }

    private static function updateC($values)
    {
        $data = [
            ['id', $values['id']],
            ['name', $values['name']],
            ['alias', $values['alias']],
            ['detail', (empty($values['detail']) ? '' : $values['detail'])],
            ['estado', $values['estado']],
            ['rol', $values['rol']],
            ['borrado', 0],
            ['updated_at', Carbon::now()]
        ];
        if(!empty($values['password'])) {
            array_push($data, ['password', Hash::make($values['password'])]);
        }

        $rows = DB::table(self::$tableC)
            ->where($data)
            ->count();
        if ($rows != 0) {
            return $values['id'];
        }
        $data = [
            'name' => $values['name'],
            'alias' => $values['alias'],
            'detail' => (empty($values['detail']) ? '' : $values['detail']),
            'estado' => $values['estado'],
            'rol' => $values['rol'],
            'updated_at' => Carbon::now(),
        ];
        if(!empty($values['password'])) {
            $tmp_pass = DB::table(self::$tableC)->where([
                ['id', $values['id']],
                ['password', Hash::make($values['password'])]
            ])->count();
            if ($tmp_pass == 0) {
                $data['password']= Hash::make($values['password']);
            }
        }
        $affected = DB::table(self::$tableC)
            ->where([
                ['id', $values['id']]
            ])
            ->update($data);

        if ($affected) {
            self::history($values['id']);
            return $values['id'];
        }
        return null;
    }

    public static function SaveC(array $values)
    {
        if (key_exists('id', $values)) {
            return self::updateC($values);
        }
        return self::insertC($values);
    }

    private static function history($id)
    {
        if (!empty($id)) {
            $temp_id = Auth::guard('api')->user();
            $data = DB::table(self::$tableC)
                ->select('id', 'name', 'password', 'alias', 'detail', 'estado', 'borrado', 'rol')
                ->where([
                    ['id', $id],
                ])
                ->get()->first();
            $data = (array)$data;
            DB::table(self::$tableHistory)
                ->insert([
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'password' => $data['password'],
                    'alias' => $data['alias'],
                    'detalle' => $data['detail'],
                    'estado' => $data['estado'],
                    'borrado' => $data['borrado'],
                    'rol' => $data['rol'],
                    'registerUtc' => Carbon::now(),
                    'registerBy' => !empty($temp_id) ? $temp_id['id'] : null,
                ]);
        }
    }

    public static function Del(array $values)
    {
        $affected = DB::table(self::$tableC)
            ->where([
                ['id', $values['id']]
            ])
            ->update([
                'borrado' => 1,
                'updated_at' => Carbon::now(),
            ]);
        if ($affected) {
            self::history($values['id']);
        }
        return $affected;
    }

}
