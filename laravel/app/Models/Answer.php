<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_id',
        'user_id',
        'question_id',
        'answer_content',
        'year',
        'month',
    ];

    protected $casts = [
        'user_id'=>'integer',
        'answer_content' => 'integer',
        'content' => 'integer',
        'year'=>'integer',
        'month'=>'integer',
    ];
}
