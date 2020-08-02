<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personal extends Model
{
    use SoftDeletes;
    protected $table = 'empleado';
    protected static $tableHistory = 'empleado_history';
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
                'id', 'apellidos', 'nombres', 'ci', 'telefono_propio', 'telefono_referencia', 'fecha_nacimiento', 'profesion', 'direccion', 'estado_civil', 'id_usuario', 'fecha_ingreso'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
