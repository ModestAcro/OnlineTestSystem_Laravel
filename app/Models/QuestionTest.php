<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTest extends Model
{
    protected $fillable = ['question_id', 'test_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
