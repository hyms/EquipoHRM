<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalVacaciones extends Model
{
    use SoftDeletes;
    protected $table = 'empleado_vacaciones';
    protected static $tableHistory = 'empleado_vacaciones_history';
    protected $guarded = [];

    public function save(array $options = [])
    {
        $result = parent::save($options);
        if ($this->wasChanged()) {
            self::history($this->id);
        }
        return $result;
    }

    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select(
                'id', 'empleado_id', 'tipo_vacaciones_id', 'numero_dias', 'fecha_inicio', 'fecha_fin', 'total_disponible', 'total_usado', 'estado', 'observaciones', 'aprobado_por'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }

    public static function remmaining_days(int $empleado_id)
    {
        $dayWorkss = 0;
        if (!empty($empleado_id)) {
            $dayWorkss = Personal::getDaysWork($empleado_id);
            $disponible = self::where('empleado_id', $empleado_id)
                ->where('estado', 2)
                ->orderBy('updated_at', 'desc')
                ->first();
            if (!empty($disponible)) {
                //if ($estado->total_disponible == 0 && $estado->total_usado == 0) {
                $disponible->total_disponible = $dayWorkss - $disponible->total_usado;
                //}
                return $disponible->total_disponible;
            }
        }
        return $dayWorkss;
    }
}
