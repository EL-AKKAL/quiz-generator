<?php

namespace App\Models;

use App\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'question',
        'description',
        'data',
        'quiz_id',
    ];

    protected $casts = [
        'data' => 'array',
        'type' => QuestionType::class,
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
