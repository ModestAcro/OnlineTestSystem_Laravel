<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    protected $fillable = ['name', 'comments'];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
