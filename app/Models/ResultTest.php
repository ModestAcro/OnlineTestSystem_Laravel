<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultTest extends Model
{
    protected $table = 'result_test';
    protected $fillable = ['test_id', 'student_id', 'start_time', 'end_time', 'status', 'max_score','earned_score', 'percent_score','result'];

    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
