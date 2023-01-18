<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'quiz_title',
        'quiz_content',
    ];

    protected $casts = [
        'quiz_id' => 'integer',
    ];
}
