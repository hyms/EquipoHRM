<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Unidadesnegocio extends Model
{
    use SoftDeletes;
    protected $table = 'unidadesNegocio';
    protected static $tableHistory = 'unidadesNegocioHistory';
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
                'id', 'nombre', 'direccion', 'telefono', 'celular', 'fax', 'ciudad', 'departamento', 'encargado', 'email', 'web', 'fecha_nacimiento'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }

}
