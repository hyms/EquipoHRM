<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class History
{
    public static function save($table, array $data)
    {
        $temp = Auth::guard('api')->user();
        $data['registerUtc'] = Carbon::now();
        $data['registerBy'] = (!empty($temp) ? $temp['id'] : null);
        DB::table($table)
            ->insert($data);
    }
}
