<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'work_date',
        'work_user_id',
        'work_salary',
    ];

    protected $casts = [
        'id'=>'integer',
        'work_id'=>'integer',
        'work_date',
        'user_id'=>'integer',
        'work_user_id'=>'integer',
        'salary'=>'integer',
        'work_salary'=>'integer',
    ];
}
