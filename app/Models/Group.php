<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['year', 'name', 'teacher_id', 'course_id', 'subject_id'];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    // Связь с предметом
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Связь с курсом
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

}
