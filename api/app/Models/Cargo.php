<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes;
    protected $table = 'cargos';
    protected static $tableHistory = 'cargosHistory';

    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select('id', 'nombre', 'detalle', 'cargo_padre')
                ->where([
                    ['id', $id],
                ])->first();
            History::save(self::$tableHistory, $data);
        }
    }

}
