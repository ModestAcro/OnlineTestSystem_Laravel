<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupStudent extends Model
{
    protected $table = 'group_student';
    protected $fillable = ['id_grupy', 'id_studenta'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
