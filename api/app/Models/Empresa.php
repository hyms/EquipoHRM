<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Empresa
{
    protected static $table = 'empresas';
    protected static $tableHistory = 'empresasHistory';

    /*public static function GetAll()
    {
        return DB::table(self::$table)
            ->select(
                'id',
                'nombre',
                'nit',
                'direccion',
                "telefono",
                "celular",
            )
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }*/

    public static function Get($id)
    {

        return DB::table(self::$table)
            ->select(
                'id',
                "nombre",
                "nit",
                "direccion",
                "telefono",
                "celular",
                "fax",
                "ciudad",
                "departamento",
                "gerente",
                "email",
                "web",
                "fecha_nacimiento",
                'created_at'
            )
            ->where([
                ['id', $id],
                ['borrado', 0],
                ['estado', 1],
            ])
            ->get();
    }

    private static function update($values)
    {
        $rows = DB::table(self::$table)
            ->where([
                ['id', $values['id']],
                ['nombre', $values['nombre']],
                ["nit",$values['nit']],
                ["direccion",$values['direccion']],
                ["telefono",$values['telefono']],
                ["celular",$values['celular']],
                ["fax",$values['fax']],
                ["ciudad",$values['ciudad']],
                ["departamento",$values['departamento']],
                ["gerente",$values['gerente']],
                ["email",$values['email']],
                ["web",$values['web']],
                ["fecha_nacimiento",'LIKE',$values['fecha_nacimiento']],
                ['estado', 1],
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
                "nit" => $values['nit'],
                "direccion" => $values['direccion'],
                "telefono" => $values['telefono'],
                "celular" => $values['celular'],
                "fax" => $values['fax'],
                "ciudad" => $values['ciudad'],
                "departamento" => $values['departamento'],
                "gerente" => $values['gerente'],
                "email" => $values['email'],
                "web" => $values['web'],
                "fecha_nacimiento" => $values['fecha_nacimiento'],
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
                    "nit" => $data['nit'],
                    "direccion" => $data['direccion'],
                    "telefono" => $data['telefono'],
                    "celular" => $data['celular'],
                    "fax" => $data['fax'],
                    "ciudad" => $data['ciudad'],
                    "departamento" => $data['departamento'],
                    "gerente" => $data['gerente'],
                    "email" => $data['email'],
                    "web" => $data['web'],
                    "fecha_nacimiento" => $data['fecha_nacimiento'],
                    "estado" => $data['estado'],
                    "borrado" => $data['borrado'],
                    'registerUtc' => Carbon::now(),
                    'registerBy' => !empty($temp_id) ? $temp_id['id'] : null,
                ]);
        }
    }

}