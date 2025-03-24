<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(){
        $teachers = Teacher::all();
        $teachersCount = $teachers->count();
        return view('admin.teacher.teachers', compact('teachers', 'teachersCount'));
    }

    public function create(){
        $courses = Course::all();
        return view('admin.teacher.create', compact('courses'));
    }

    public function store(Request $request){
        $data = request()->validate([
            'courses' => 'required|array|exists:courses,id',
            'degree' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'password' => 'required|string',
            'comments' => 'nullable|string',
        ], [
            'courses.required' => 'Kierunki są wymagane.',
            'degree.required' => 'Stopień jest wymagany.',
            'name.required' => 'Imię jest wymagane.',
            'surname.required' => 'Nazwisko jest wymagane.',
            'email.required' => 'Adres email jest wymagany.',
            'email.unique' => 'Taki adres email już istnieje.',
            'password' => 'Hasło jest wymagane.',
        ]);

        // Password hash
        $data['password'] = bcrypt($data['password']);
        $data['must_change_password'] = true;


        $teacher = Teacher::create($data);
        $teacher->courses()->attach($data['courses']);
        return redirect()->route('teachers.index');
    }

    public function show(Teacher $teacher){
        $courses = $teacher->courses;
        return view('admin.teacher.show', compact('courses', 'teacher'));
    }

    public function edit(Teacher $teacher){
        $courses = Course::all();
        $selectedCourses = $teacher->courses->pluck('id')->toArray();
        return view('admin.teacher.edit', compact( 'selectedCourses', 'courses', 'teacher'));
    }

    public function update(Request $request, Teacher $teacher){
        $data = request()->validate([
            'courses' => 'required|array|exists:courses,id',
            'degree' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'password' => 'nullable|string',
            'comments' => 'nullable|string',
        ], [
            'courses.required' => 'Kierunki są wymagane.',
            'degree.required' => 'Stopień jest wymagany.',
            'name.required' => 'Imię jest wymagane.',
            'surname.required' => 'Nazwisko jest wymagane.',
            'email.required' => 'Adres email jest wymagany.',
            'email.unique' => 'Taki adres email już istnieje.',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['must_change_password'] = true;

        $teacher->update($data);
        $teacher->courses()->sync($data['courses']);
        return redirect()->route('teachers.show', $teacher->id);
    }

    public function destroy(Teacher $teacher){
        $teacher->delete();
        return redirect()->route('teachers.index');
    }
}
