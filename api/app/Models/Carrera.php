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
            ->get();
    }

    private static function insert($values)
    {
        $id = DB::table(self::$table)
            ->insertGetId([
                'personal' => $values['personal'],
                'cargo' => $values['cargo'],
                'unidad_negocio' => $values['unidad_negocio'],
                'area_trabajo' => $values['area_trabajo'],
                'regional' => $values['regional'],
                'gerencia' => $values['gerencia'],
                'fecha_ingreso' => $values['fecha_ingreso'],
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
                ['personal', $values['personal']],
                ['cargo', $values['cargo']],
                ['unidad_negocio', $values['unidad_negocio']],
                ['area_trabajo', $values['area_trabajo']],
                ['regional', $values['regional']],
                ['gerencia', $values['gerencia']],
                ['fecha_ingreso', $values['fecha_ingreso']],
            ])
            ->count();
        if ($rows != 0) {
            return $values['id'];
        }
        $affected = DB::table(self::$table)
            ->where([
                ['id', $values['id']],
                ['personal', $values['personal']],
            ])
            ->update([
                'cargo' => $values['cargo'],
                'unidad_negocio' => $values['unidad_negocio'],
                'area_trabajo' => $values['area_trabajo'],
                'regional' => $values['regional'],
                'gerencia' => $values['gerencia'],
                'fecha_ingreso' => $values['fecha_ingreso'],
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
        if (key_exists('id', $values) && !empty($values['id'])) {
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
                    'personal' => $data['personal'],
                    'cargo' => $data['cargo'],
                    'unidad_negocio' => $data['unidad_negocio'],
                    'area_trabajo' => $data['area_trabajo'],
                    'regional' => $data['regional'],
                    'gerencia' => $data['gerencia'],
                    'usuario' => $data['usuario'],
                    'fecha_ingreso' => $data['fecha_ingreso'],
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
