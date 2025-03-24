<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'duration',
        'number_of_trials',
        'group_id',
        'teacher_id',
        'subject_id',
        'course_id',
    ];
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
    return $this->belongsTo(Teacher::class);
    }

    public function results()
    {
        return $this->hasMany(ResultTest::class, 'test_id');
    }

    public function resultTests()
    {
        return $this->hasMany(ResultTest::class, 'test_id');
    }

}
