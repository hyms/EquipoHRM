<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    use SoftDeletes;
    protected $table = 'Empresa';
    protected $guarded = [];

}
