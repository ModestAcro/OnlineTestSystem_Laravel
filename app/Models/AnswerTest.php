<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerTest extends Model
{
    protected $table = 'answer_test';
    protected $fillable = ['result_test_id', 'test_id', 'student_id', 'question_id', 'answer_id', 'correct','points'];

    public function resultTest(){
        return $this->belongsTo(ResultTest::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function answer(){
        return $this->belongsTo(Answer::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function test(){
        return $this->belongsTo(Test::class);
    }
}
