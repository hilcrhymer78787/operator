<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'task_user_id',
        'task_state',
        'task_type',
        'year',
        'month',
    ];

    protected $casts = [
        'task_id'=>'integer',
        'id'=>'integer',
        'task_user_id'=>'integer',
        'user_id'=>'integer',
        'task_state'=>'integer',
        'state'=>'integer',
        'task_type'=>'integer',
        'type'=>'integer',
        'year'=>'integer',
        'month'=>'integer',
      ];
}


