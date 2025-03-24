<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Group;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(){
        $teacher = auth()->user();
        $tests = $teacher->tests;
        $testsCount = $teacher->tests->count();
        $tests = $teacher->tests()->paginate(10);

        return view('teacher.test.tests', compact('tests', 'testsCount'));
    }

    public function create(){
        $courses = auth()->user()->courses;
        $groups = auth()->user()->groups;
        $questions = auth()->user()->questions;
        return view('teacher.test.create', compact('courses', 'groups', 'questions'));
    }

    public function store(Request $request){
        $teacher_id = auth()->id();
        $data = $request->validate([
            'course' => 'required|exists:courses,id',
            'subject' => 'required|exists:subjects,id',
            'group' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'start-time' => 'nullable|date',
            'end-time' => 'nullable|date',
            'duration' => 'required|numeric|min:1',
            'attempts' => 'required|in:unlimited,one,multiple',
            'number-of-attempts' => 'nullable|numeric|min:1',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'course.required' => 'Wybierz kurs.',
            'subject.required' => 'Wybierz przedmiot.',
            'group.required' => 'Wybierz grupę',
            'duration.required' => 'Podaj czas trwania.',
            'attempts' => 'Ustaw liczbę podejść',
            'number-of-attempts.required' => 'Ustaw liczbę podejść',
            'questions.required' => 'Wybierz pytania.',
        ]);

        if($data['attempts'] == 'unlimited'){
            $data['attempts'] = -1;
        } elseif ($data['attempts'] == 'one') {
            $data['attempts'] = 1;
        } else {
            $data['attempts'] = $data['number-of-attempts'];
        }

        $test = Test::create([
            'name' => $data['name'],
            'start_time' => $data['start-time'] ?? null,
            'end_time' => $data['end-time'] ?? null,
            'duration' => $data['duration'],
            'number_of_trials' => $data['attempts'],
            'group_id' => $data['group'],
            'teacher_id' => $teacher_id,
            'subject_id' => $data['subject'],
            'course_id' => $data['course'],
        ]);

        $test->questions()->attach($data['questions']);
        return redirect()->route('tests.index');
    }

    // ################### AJAX #################
    public function getSubjectsByCourse($courseId)
    {
        // Получаем курс по ID и связанные с ним предметы
        $course = Course::find($courseId);
        $subjects = $course ? $course->subjects : [];

        // Возвращаем данные в формате JSON
        return response()->json([
            'subjects' => $subjects
        ]);
    }
    public function getGroupsBySubject($subjectId)
    {
        $teacher_id = auth()->id();
        // Получаем группы, связанные с выбранным предметом
        $groups = Group::where('subject_id', $subjectId)
            ->where('teacher_id', $teacher_id) // Проверка на учителя
            ->get();

        return response()->json(['groups' => $groups]);
    }

//    public function getQuestionsBySubject($subjectId)
//    {
//        // Получаем текущего учителя
//        $teacherId = auth()->id();
//
//        // Получаем вопросы для текущего учителя и выбранного предмета
//        $questions = Question::where('subject_id', $subjectId)
//            ->where('teacher_id', $teacherId)  // Фильтрация по учителю
//            ->get();
//
//        return response()->json(['questions' => $questions]);
//    }
    // ################### AJAX #################

    public function show(Test $test){
        return view('teacher.test.show', compact('test'));
    }

    public function edit(Test $test)
    {
        $courses = auth()->user()->courses;
        $groups = auth()->user()->groups;
        $questions = auth()->user()->questions;
        $selectedQuestions = $test->questions()->pluck('questions.id')->toArray();
        return view('teacher.test.edit', compact('test', 'courses', 'groups', 'questions', 'selectedQuestions'));

    }

    public function update(Request $request, Test $test)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'start-time' => 'nullable|date',
            'end-time' => 'nullable|date',
            'duration' => 'required|integer|min:1',
            'attempts' => 'required|string|in:unlimited,one,multiple',
            'number-of-attempts' => 'nullable|integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*' => 'exists:questions,id',
            'limitSwitch' => 'nullable|boolean',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
            'duration.required' => 'Podaj czas trwania.',
            'attempts' => 'Ustaw liczbę podejść',
            'number-of-attempts.required' => 'Ustaw liczbę podejść',
            'questions.required' => 'Wybierz pytania.',
        ]);

        if (empty($data['limitSwitch'])) {
            $data['start-time'] = null;
            $data['end-time'] = null;
        }

        $test->name = $data['name'];
        $test->course_id = $data['course'];
        $test->subject_id = $data['subject'];
        $test->group_id = $data['group'];
        $test->start_time = $data['start-time'];
        $test->end_time = $data['end-time'];
        $test->duration = $data['duration'];

        // Update the number of attempts
        if ($data['attempts'] == 'unlimited') {
            $test->number_of_trials = -1; // Unlimited attempts
        } elseif ($data['attempts'] == 'one') {
            $test->number_of_trials = 1; // One attempt
        } elseif ($data['attempts'] == 'multiple' && isset($data['number-of-attempts'])) {
            $test->number_of_trials = $data['number-of-attempts']; // Multiple attempts
        }

        $test->save();
        $test->questions()->sync($data['questions']);
        return view('teacher.test.show', compact('test'));
    }

    public function destroy(Test $test){
        $test->delete();
        return redirect()->route('tests.index');
    }
}
