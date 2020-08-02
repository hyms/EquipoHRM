<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regional extends Model
{
    use SoftDeletes;
    protected $table = 'regional';
    protected static $tableHistory = 'regionalHistory';
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
                'id', 'nombre', 'detalle'
            )
                ->withTrashed()
                ->find($id)
                ->toArray();
            History::save(self::$tableHistory, $data);
        }
    }
}
