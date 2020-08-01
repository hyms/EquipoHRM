<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Areastrabajo extends Model
{
    use SoftDeletes;
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
