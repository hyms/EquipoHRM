<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Roles
{
    protected static $table = 'roles';
    protected static $tableHistory = 'rolesHistory';

    public static function GetAll()
    {
        return DB::table(Roles::$table)
            ->select('id', 'nombre', 'created_at', 'estado')
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }

    public static function Get($id)
    {
        return DB::table(Roles::$table)
            ->select('id', 'nombre', 'created_at', 'estado')
            ->where([
                ['id', $id],
                ['borrado', 0],
            ])
            ->get();
    }

    private static function insert($values)
    {
        $id = DB::table(Roles::$table)
            ->insertGetId([
                'nombre' => $values['nombre'],
                'estado' => $values['estado'],
                'borrado' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        self::history($id);
        return $id;
    }

    private static function update($values)
    {
        $rows = DB::table(Roles::$table)
            ->where([
                ['id', $values['id']],
                ['nombre', $values['nombre']],
                ['estado', $values['estado']],
                ['borrado', 0],
            ])
            ->count();
        if ($rows != 0) {
            return $values['id'];
        }
        $affected = DB::table(Roles::$table)
            ->where([
                ['id', $values['id']]
            ])
            ->update([
                'nombre' => $values['nombre'],
                'estado' => $values['estado'],
                'updated_at' => Carbon::now(),
            ]);

        if ($affected) {
            self::history($values['id']);
            return $values['id'];
        }
        return null;
    }

    public static function Save(array $values)
    {
        if (key_exists('id', $values)) {
            return self::update($values);
        }
        return self::insert($values);
    }

    private static function history($id)
    {
        if (!empty($id)) {
            $temp_id = Auth::guard('api')->user();
            $data = DB::table(Roles::$table)
                ->select('id', 'nombre', 'estado', 'borrado')
                ->where([
                    ['id', $id],
                ])
                ->get()->first();
            $data = (array)$data;
            DB::table(Roles::$tableHistory)
                ->insert([
                    'id' => $data['id'],
                    'nombre' => $data['nombre'],
                    'estado' => $data['estado'],
                    'borrado' => $data['borrado'],
                    'registerUtc' => Carbon::now(),
                    'registerBy' => !empty($temp_id) ? $temp_id['id'] : null,
                ]);
        }
    }

    public static function Delete(array $values)
    {
        $affected = DB::table(Roles::$table)
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
