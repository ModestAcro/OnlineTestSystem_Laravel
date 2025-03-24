<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;  // Добавляем этот трейт
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = [
        'name', 'surname', 'album_number', 'email', 'password',
        'year', 'academic_year', 'comments', 'course_id', 'must_change_password'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
