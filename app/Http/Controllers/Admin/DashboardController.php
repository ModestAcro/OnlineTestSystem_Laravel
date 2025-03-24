<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){

        $courses = Course::all();
        $coursesCount = $courses->count();

        $subjects = Subject::all();
        $subjectsCount = $subjects->count();

        $students = Student::all();
        $studentsCount = $students->count();

        $teachers = Teacher::all();
        $teachersCount = $teachers->count();

        return view('admin.dashboard', compact('courses', 'coursesCount', 'subjects', 'subjectsCount', 'students', 'studentsCount', 'teachers', 'teachersCount'));
    }

}
