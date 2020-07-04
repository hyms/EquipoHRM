<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cargo
{
    protected static $table = 'cargos';
    protected static $tableHistory = 'cargosHistory';

    public static function GetAll()
    {
        return DB::table(self::$table)
            ->select('id', 'nombre', 'detalle', 'estado')
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }

    public static function Get($id)
    {
        return DB::table(self::$table)
            ->select('id', 'nombre', 'estado')
            ->where([
                ['id', $id],
                ['borrado', 0],
            ])
            ->get();
    }

    private static function insert($values)
    {
        $id = DB::table(self::$table)
            ->insertGetId([
                'nombre' => $values['nombre'],
                'estado' => $values['estado'],
                'detalle' => $values['detalle'],
                'cargo_padre' => $values['cargo_padre'],
                'borrado' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        self::history($id);
        return $id;
    }

    private static function update($values)
    {
        $rows = DB::table(self::$table)
            ->where([
                ['id', $values['id']],
                ['nombre', $values['nombre']],
                ['estado', $values['estado']],
                ['detalle', $values['detalle']],
                ['cargo_padre', $values['cargo_padre']],
                ['borrado', 0],
            ])
            ->count();
        if ($rows != 0) {
            return $values['id'];
        }
        $affected = DB::table(self::$table)
            ->where([
                ['id', $values['id']]
            ])
            ->update([
                'nombre' => $values['nombre'],
                'estado' => $values['estado'],
                'detalle' => $values['detalle'],
                'cargo_padre' => $values['cargo_padre'],
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
            $data = DB::table(self::$table)
                ->where([
                    ['id', $id],
                ])
                ->get()->first();
            $data = (array)$data;
            DB::table(self::$tableHistory)
                ->insert([
                    'id' => $data['id'],
                    'nombre' => $data['nombre'],
                    'estado' => $data['estado'],
                    'detalle' => $data['detalle'],
                    'borrado' => $data['borrado'],
                    'cargo_padre' => $data['cargo_padre'],
                    'registerUtc' => Carbon::now(),
                    'registerBy' => !empty($temp_id) ? $temp_id['id'] : null,
                ]);
        }
    }

    public static function Delete(array $values)
    {
        $affected = DB::table(self::$table)
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
