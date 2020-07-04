<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Unidadesnegocio
{
    protected static $table = 'unidadesNegocio';
    protected static $tableHistory = 'unidadesNegocioHistory';

    public static function GetAll()
    {
        return DB::table(self::$table)
            ->select('id', 'nombre', 'direccion', 'telefono', 'celular')
            ->where([
                ['borrado', 0],
            ])
            ->get();
    }

    public static function Get($id)
    {
        return DB::table(self::$table)
            ->select(
                'id',
                "nombre",
                "direccion",
                "telefono",
                "celular",
                "fax",
                "ciudad",
                "departamento",
                "encargado",
                "email",
                "web",
                "fecha_nacimiento",
                "id_empresa"
            )
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
                "nombre" => $values["nombre"],
                "direccion" => $values["direccion"],
                "telefono" => $values["telefono"],
                "celular" => $values["celular"],
                "fax" => $values["fax"],
                "ciudad" => $values["ciudad"],
                "departamento" => $values["departamento"],
                "encargado" => $values["encargado"],
                "email" => $values["email"],
                "web" => $values["web"],
                "fecha_nacimiento" => $values["fecha_nacimiento"],
                'id_empresa' => $values["id_empresa"],
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
                ["nombre", $values["nombre"]],
                ["direccion", $values["direccion"]],
                ["telefono", $values["telefono"]],
                ["celular", $values["celular"]],
                ["fax", $values["fax"]],
                ["ciudad", $values["ciudad"]],
                ["departamento", $values["departamento"]],
                ["encargado", $values["encargado"]],
                ["email", $values["email"]],
                ["web", $values["web"]],
                ["fecha_nacimiento", $values["fecha_nacimiento"]],
                ["id_empresa", $values["id_empresa"]],
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
                "direccion" => $values["direccion"],
                "telefono" => $values["telefono"],
                "celular" => $values["celular"],
                "fax" => $values["fax"],
                "ciudad" => $values["ciudad"],
                "departamento" => $values["departamento"],
                "encargado" => $values["encargado"],
                "email" => $values["email"],
                "web" => $values["web"],
                "fecha_nacimiento" => $values["fecha_nacimiento"],
                'updated_at' => Carbon::now()
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
                    "direccion" => $data["direccion"],
                    "telefono" => $data["telefono"],
                    "celular" => $data["celular"],
                    "fax" => $data["fax"],
                    "ciudad" => $data["ciudad"],
                    "departamento" => $data["departamento"],
                    "encargado" => $data["encargado"],
                    "email" => $data["email"],
                    "web" => $data["web"],
                    "fecha_nacimiento" => $data["fecha_nacimiento"],
                    "estado" => $data["estado"],
                    'borrado' => $data['borrado'],
                    'id_empresa' => $data['id_empresa'],
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
