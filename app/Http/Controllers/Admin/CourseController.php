<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(){
        $allCourses = Course::all();
        $allCoursesCount = $allCourses->count();
        return view('admin.course.courses', compact('allCourses', 'allCoursesCount'));
    }

    public function create(){
        $subjects = Subject::all();
        return view('admin.course.create', compact('subjects'));
    }

    public function store(){
        $data = request()->validate([
            'name' => 'required|string',
            'subjects' => 'required|array|exists:subjects,id',
            'comments' => 'nullable|string',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'subjects.required' => 'Wybierz przedmioty.'
        ]);

        $course = Course::create([
           'name' => $data['name'],
           'comments' => $data['comments'] ?? null,
        ]);

        // Przypisanie przedmiotow do kursu w tabele many-to-many
        $course->subjects()->attach($data['subjects']);
        return redirect()->route('courses.index');
    }

    public function show(Course $course){
        $subjects = $course->subjects;
        return view('admin.course.show', compact('course', 'subjects'));
    }

    public function edit(Course $course){
        $subjects = Subject::all();
        $selectedSubjects = $course->subjects->pluck('id')->toArray();
        return view('admin.course.edit', compact('course', 'subjects', 'selectedSubjects'));
    }

    public function update(Request $request, Course $course){
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'subjects' => 'required|array|exists:subjects,id',
            'comments' => 'nullable|string|string',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'subjects.required' => 'Wybierz przedmioty.'
        ]);

        $course->update($data);
        $course->subjects()->sync($data['subjects']);
        return redirect()->route('courses.show', $course->id);
    }

    public function destroy(Course $course){
        $course->delete();
        return redirect()->route('courses.index');
    }

}
