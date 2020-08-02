<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonalEmpresa extends Model
{
    use SoftDeletes;
    protected $table = 'empleado_empresa';
    protected static $tableHistory = 'empleado_empresa_history';
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
                'id', 'empleado', 'cargo', 'unidad_negocio', 'area_trabajo', 'regional', 'gerencia'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
