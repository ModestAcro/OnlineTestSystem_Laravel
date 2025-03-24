<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    protected $fillable = ['name', 'comments'];

    // Определяем связь многие ко многим с таблицей subjects
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
