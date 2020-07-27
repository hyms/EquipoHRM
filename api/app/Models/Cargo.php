<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
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
