<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Vacaciones
{
    protected static $table = 'permisosVacacion';
    protected static $tableHistory = 'permisosVacacionHistory';

    public static function GetAll()
    {
        return DB::table(self::$table)
            ->select('id', 'tiempo_parcial', 'tiempo_completo', 'numero_dias', 'fecha_inicio', 'fecha_fin', 'estado')
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }

    public static function Get($id)
    {
        return DB::table(self::$table)
            ->select('id', 'tiempo_parcial', 'tiempo_completo', 'numero_dias', 'fecha_inicio', 'fecha_fin', 'estado')
            ->where([
                ['id', $id],
                ['borrado', 0],
            ])
            ->get()->first();
    }

    private static function insert($values)
    {
        $values['borrado'] = 0;
        $values['created_at'] = Carbon::now();
        $values['updated_at'] = Carbon::now();
        $id = DB::table(self::$table)
            ->insertGetId($values);
        self::history($id);
        return $id;
    }

    private static function update($values)
    {
        $data = array();
        foreach ($values as $key => $value) {
            array_push($data, [$key, $value]);
        }
        array_push($data, ['borrado', 0]);

        $rows = DB::table(self::$table)
            ->where($data)
            ->count();
        if ($rows != 0) {
            return $values['id'];
        }
        $values['updated_at'] = Carbon::now();
        $affected = DB::table(self::$table)
            ->where([
                ['id', $values['id']]
            ])
            ->update($values);

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
                ->select(
                    'id', 'tiempo_parcial', 'tiempo_completo', 'numero_dias', 'fecha_inicio', 'fecha_fin', 'estado'
                )
                ->where([
                    ['id', $id],
                ])
                ->get()->first();
            $data = (array)$data;
            $data['registerUtc'] = Carbon::now();
            $data['registerBy'] = (!empty($temp_id) ? $temp_id['id'] : null);
            DB::table(self::$tableHistory)
                ->insert($data);
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
