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
                'id', 'empleado_id', 'tipo_vacaciones_id', 'numero_dias', 'fecha_inicio', 'fecha_fin', 'estado', 'observaciones', 'aprobado_por'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
