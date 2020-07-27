<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Areastrabajo extends Model
{
    protected $table = 'areasTrabajo';
    protected static $tableHistory = 'areasTrabajoHistory';

    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select(
                'id', 'nombre', 'detalle'
            )
                ->where([
                    ['id', $id],
                ])->first();
            History::save(self::$tableHistory, $data);
        }
    }
}
