<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(){
        $students = Student::all();
        $studentsCount = $students->count();
        return view('admin.student.students', compact('students', 'studentsCount'));
    }

    public function create(){
        $courses = Course::all();
        return view('admin.student.create', compact('courses'));
    }

    public function store(Request $request){
            $data = request()->validate([
                'course_id' => 'required|integer|exists:courses,id',
                'academic_year' => 'required|integer',
                'year' => 'required|integer',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'album_number' => 'required|digits:5|unique:students,album_number',
                'email' => 'required|email|unique:students,email',
                'password' => 'required|string',
                'comments' => 'nullable|string',
            ], [
                'course_id.required' => 'Wybierz kurs.',
                'academic_year.required' => 'Wybierz rok akademicki.',
                'year.required' => 'Wybierz rok.',
                'name.required' => 'Imię jest wymagane.',
                'surname.required' => 'Nazwisko jest wymagane.',
                'album_number.required' => 'Numer albumu jest wymagany.',
                'album_number.unique' => 'Taki numer albumu już istnieje.',
                'album_number.digits' => 'Numer albumu musi składać się z 5 cyfr.',
                'email.required' => 'Adres email jest wymagany.',
                'password.required' => 'Hasło jest wymagane.',
            ]);

            // Password hash
            $data['password'] = bcrypt($data['password']);
            $data['must_change_password'] = true; // Ustawiamy w kolumnie must_change_password na true(1) aby zmienic hasło

            Student::create($data);
            return redirect()->route('students.index');
    }

    public function show(Student $student){
        $course = $student->course;
        $subjects = $course->subjects;
        return view('admin.student.show', compact('student','course', 'subjects'));
    }

    public function edit(Student $student){
        $course = $student->course;
        $courses = Course::all();
        return view('admin.student.edit', compact('student','course', 'courses'));
    }

    public function update(Request $request, Student $student){
        $data = request()->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'academic_year' => 'required|integer',
            'year' => 'required|integer',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'album_number' => 'required|digits:5|unique:students,album_number,' . $student->id,
            'email' => 'required|email|unique:students,email',
            'password' => 'nullable|string',
            'comments' => 'nullable|string',
        ], [
            'course_id.required' => 'Wybierz kurs.',
            'academic_year.required' => 'Wybierz rok akademicki.',
            'year.required' => 'Wybierz rok.',
            'name.required' => 'Imię jest wymagane.',
            'surname.required' => 'Nazwisko jest wymagane.',
            'album_number.required' => 'Numer albumu jest wymagany.',
            'album_number.unique' => 'Taki numer albumu już istnieje.',
            'album_number.digits' => 'Numer albumu musi składać się z 5 cyfr.',
            'email.required' => 'Adres email jest wymagany.',
            'email.unique' => 'Taki adres email już istnieje.',
            'password.required' => 'Hasło jest wymagane.',
        ]);

        // Password hash
        $data['password'] = bcrypt($data['password']);
        $data['must_change_password'] = true;

        $student->update($data);
        $course = $student->course;
        return view('admin.student.show', compact('student', 'course'));
    }

    public function destroy(Student $student){
        $student->delete();
        return redirect()->route('students.index');
    }
}
