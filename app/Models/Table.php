<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'kode_table',
        'qr_code',
        'status',
        'floor',
    ];
}
