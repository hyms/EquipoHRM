<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Carrera
{
    protected static $table = 'carrera';
    protected static $tableHistory = 'carreraHistory';

    public static function Get($id)
    {
        return DB::table(self::$table)
            ->select('id', 'personal', 'cargo', 'unidad_negocio', 'area_trabajo', 'regional', 'gerencia', 'usuario', 'fecha_ingreso')
            ->where([
                ['personal', $id],
            ])
            ->get()->first();
    }

    private static function insert($values)
    {
        $values['created_at'] = Carbon::now();
        $values['updated_at'] = Carbon::now();
        $id = DB::table(self::$table)
            ->insertGetId($values);
        self::history($id);
        return $id;
    }

    private static function update($values)
    {
        $datos = array();
        foreach ($values as $key => $value) {
            array_push($datos, [$key, $value]);
        }
        $rows = DB::table(self::$table)
            ->where($values)
            ->count();
        if ($rows != 0) {
            return $values['personal'];
        }
        $values['updated_at'] = Carbon::now();
        $affected = DB::table(self::$table)
            ->where([
                ['personal', $values['personal']],
            ])
            ->update($values);

        if ($affected) {
            self::history($values['personal']);
            return $values['personal'];
        }
        return null;
    }

    public static function Save(array $values)
    {
        if (key_exists('personal', $values) && !empty($values['personal'])) {
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
                    ['personal', $id],
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

    public static function GetHistory($id)
    {
        return DB::table(self::$tableHistory)
            ->select('personal.nombres', 'personal.apellidos', 'cargos.nombre as cargo', 'registerUtc as fecha')
            ->where([
                ['personal', $id],
            ])
            ->leftJoin('personal', 'personal', '=', 'personal.id')
            ->leftJoin('cargos', 'cargo', '=', 'cargos.id')
            ->orderBy('registerUtc', 'desc')
            ->get();
    }

}
