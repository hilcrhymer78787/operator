<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'question_content',
        'question_reason',
        'question_important',
    ];

    protected $casts = [
        'question_id'=>'integer',
        'question_important' => 'boolean',
      ];
}
