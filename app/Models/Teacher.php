<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = [
        'name', 'surname', 'email', 'password',
        'degree', 'comments', 'course_id', 'must_change_password'
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }


}
