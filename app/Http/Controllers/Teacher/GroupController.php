<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){
        $teacher = auth()->user();
        $groups = $teacher->groups()->with(['subject', 'course', 'students'])->get();
        $groupsCount = $groups->count();
        $groups = Group::paginate(10); // Paginate by 10 items per page
        return view('teacher.group.groups', compact('groups', 'groupsCount'));
    }

    public function create()
    {
        $teacher = auth()->user();
        $courses = $teacher->courses;

        //  Pobranie przedmiotów związanych z kierunkiem
        $subjects = Subject::whereHas('courses', function ($query) use ($courses) {
            $query->whereIn('courses.id', $courses->pluck('id'));
        })->get();

        $students = Student::whereIn('course_id', $courses->pluck('id'))->get();
        return view('teacher.group.create', compact('courses', 'subjects', 'students'));
    }

    public function store(Request $request){
        $teacher_id = auth()->id();
        $data = request()->validate([
            'name' => 'required|string',
            'year' => 'required|integer',
            'course' => 'required|integer|exists:courses,id',
            'subject' => 'required|integer|exists:subjects,id',
            'students' => 'required|nullable|array',
            'students.*' => 'integer|exists:students,id',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'year.required' => 'Rok jest wymagany.',
            'course.required' => 'Wybierz kurs.',
            'subject.required' => 'Wybierz przedmiot.',
            'students.required' => 'Wybierz studentów',
        ]);

        $group = Group::create([
            'name' => $data['name'],
            'year' => $data['year'],
            'teacher_id' => $teacher_id,
            'course_id' => $data['course'],
            'subject_id' => $data['subject'], // Должно быть здесь!
        ]);

        $group->students()->attach($data['students']);
        return redirect()->route('groups.index')->with('success', 'Grupa została stworzona');
    }

    public function show(Group $group){
        return view('teacher.group.show', compact('group'));
    }


    public function edit(Group $group)
    {
        $teacher = auth()->user();
        $courses = $teacher->courses;
        $subjects = $courses->flatMap(function ($course) {
            return $course->subjects;
        });

        $groupCourseId = $group->course_id;
        $students = Student::where('course_id', $groupCourseId)->get();
        $selectedStudents = $group->students->pluck('id')->toArray();
        return view('teacher.group.edit', compact('group', 'courses', 'subjects', 'students', 'selectedStudents'));
    }

    public function update(Request $request, Group $group){
        $data = request()->validate([
           'year' => 'required|integer',
            'name' => 'required|string',
            'course' => 'required|integer|exists:courses,id',
            'subject' => 'required|integer|exists:subjects,id',
            'students' => 'required|nullable|array',
            'students.*' => 'integer|exists:students,id',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'year.required' => 'Rok jest wymagany.',
            'course.required' => 'Wybierz kurs.',
            'subject.required' => 'Wybierz przedmiot.',
            'students.required' => 'Wybierz studentów',
        ]);

        $group->update([
            'year' => $data['year'],
            'name' => $data['name'],
            'subject_id' => $data['subject'],
            'course_id' => $data['course'],
        ]);

        $group->students()->sync($data['students']);
        return view('teacher.group.show', compact('group'));
    }

    public function destroy(Group $group){
        $group->delete();
        return redirect()->route('groups.index');

    }

    // ############## AJAX ##############
    public function getSubjectsByCourse($courseId)
    {
        $course = Course::find($courseId);
        $subjects = $course ? $course->subjects : [];
        return response()->json([
            'subjects' => $subjects
        ]);
    }
    // ############## AJAX ##############

}
