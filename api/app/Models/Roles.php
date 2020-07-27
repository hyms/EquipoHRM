<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';
    protected static $tableHistory = 'rolesHistory';

    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select('id', 'nombre', 'description')
                ->where([
                    ['id', $id],
                ])->first();
            History::save(self::$tableHistory, $data);
        }
    }
}
