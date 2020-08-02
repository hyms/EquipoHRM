<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacacionesTipo extends Model
{
    use SoftDeletes;
    protected $table = 'vacaciones_tipo';
    protected static $tableHistory = 'vacaciones_tipo_history';
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
                'id', 'tipo', 'tiempo_dias'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
