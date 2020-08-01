<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use SoftDeletes;
    protected $table = 'roles';
    protected static $tableHistory = 'roles_history';
    protected $guarded = ['deleted_at', 'updated_at'];

    public static function history($id)
    {
        if (!empty($id)) {
            $data = self::select('id', 'name', 'description')
                ->withTrashed()
                ->where([
                    ['id', $id],
                ])->get()->first()->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
