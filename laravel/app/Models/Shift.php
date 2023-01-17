<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id',
        'shift_week_num',
        'shift_week_day',
        'shift_user_id',
    ];

    protected $casts = [
        'shift_id'=>'integer',
        'shift_week_num'=>'integer',
        'shift_user_id'=>'integer',
      ];
}
