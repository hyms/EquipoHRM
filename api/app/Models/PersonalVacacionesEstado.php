<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalVacacionesEstado extends Model
{
    protected $table = 'empleado_vacaciones_estado';
    protected static $tableHistory = 'empleado_vacaciones_estado_history';
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
                "id", "total_disponible", "total_usado", "empleado_id"
            )
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }

    public static function remmaining_days(int $empleado_id)
    {
        if (!empty($empleado_id)) {
            $estado = PersonalVacacionesEstado::where('empleado_id', $empleado_id)->first();
            //if ($estado->total_disponible == 0 && $estado->total_usado == 0) {
            $estado->total_disponible = Personal::getDaysWork($empleado_id) - $estado->total_usado;
            //}
            return $estado->total_disponible;
        }
    }
}
