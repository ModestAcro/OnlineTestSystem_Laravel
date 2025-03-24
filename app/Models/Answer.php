<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['title', 'is_correct', 'points', 'question_id'];
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
