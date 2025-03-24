<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(){
        $teacherId = auth()->id();
        $questions = Question::where('teacher_id', $teacherId)->get();
        $questionsCount = $questions->count();
        $questions = Question::paginate(10); // Paginate by 10 items per page
        return view('teacher.question.questions', compact('questions', 'questionsCount'));
    }

    public function createMulti(){
        $teacher = Teacher::with('courses.subjects')->find(auth()->id());
        $subjects = $teacher->courses->flatMap->subjects->unique('id');
        return view('teacher.question.createMulti', compact( 'subjects'));
    }

    public function storeMulti(Request $request)
    {
        $teacher_id = auth()->id();
        $data = $request->validate([
            'title' => 'required|string',
            'subject' => 'required|exists:subjects,id',
            'answers' => 'required|array|min:2',
            'answers.*' => 'required|string',
            'points' => 'required|array',
            'points.*' => 'required|integer',
            'is_correct' => 'required|array|min:1',
        ], [
            'title.required' => 'Treść pytania jest wymagana.',
            'subject.required' => 'Wybierz przedmiot.',
            'answers.required' => 'Musisz dodać co najmniej dwie odpowiedzi.',
            'answers.min' => 'Musisz dodać co najmniej dwie odpowiedzi.',
            'answers.*.required' => 'Treść każdej odpowiedzi jest wymagana.',
            'points.required' => 'Musisz przypisać punkty do każdej odpowiedzi.',
            'points.min' => 'Musisz dodać punkty do co najmniej dwóch odpowiedzi.',
            'points.*.required' => 'Każda odpowiedź musi mieć przypisane punkty.',
            'points.*.integer' => 'Punkty muszą być liczbą całkowitą.',
            'points.*.min' => 'Punkty nie mogą być ujemne.',
            'is_correct.required' => 'Musisz zaznaczyć przynajmniej jedną poprawną odpowiedź.',
            'is_correct.min' => 'Musisz wybrać co najmniej jedną poprawną odpowiedź.',
        ]);

        // Sprawdzenie, czy suma punktów jest mniejsza niż 0
        if (array_sum($request->input('points')) <= 0) {
            return back()->withErrors(['points' => 'Suma punktów musi być większa niż 0.'])->withInput();
        }

        $question = Question::create([
            'title' => $data['title'],
            'type' => 'multi_choice',
            'teacher_id' => $teacher_id,
            'subject_id' => $data['subject'],
        ]);

        $questionId = $question->id;
        foreach ($data['answers'] as $index => $answer) {
            Answer::create([
                'title' => $answer,
                'is_correct' => in_array("Option " . $index, $data['is_correct']),
                'points' => $data['points'][$index],
                'question_id' => $questionId,
            ]);
        }

       return redirect()->route('questions.index')->with('success', 'Dodano pytanie wielokrotnego wyboru');
    }

    public function showMulti(Question $question){
        return view('teacher.question.showMulti', compact('question'));
    }

    public function editMulti(Question $question){
        $teacher_id = auth()->id();
        $teacher_courses = CourseTeacher::where('teacher_id', $teacher_id)->get();
        $subjects = $teacher_courses->flatMap(function ($courseTeacher) {
            return $courseTeacher->course->subjects;
        });
        return view('teacher.question.editMulti', compact('question', 'teacher_courses', 'subjects'));
    }

    public function updateMulti(Request $request, Question $question)
    {
        $data = $request->validate([
            'subject' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required|string',
            'answers.*.points' => 'required|numeric',
            'answers.*.is_correct' => 'sometimes|boolean',
        ], [
            'title.required' => 'Treść pytania jest wymagana.',
            'subject.required' => 'Wybierz przedmiot.',
        ]);

        $question->update([
            'subject_id' => $data['subject'],
            'title' => $data['title'],
        ]);

        $question->answers()->delete();
        foreach ($data['answers'] as $answerData) {
            $question->answers()->create([
                'title' => $answerData['text'],
                'points' => $answerData['points'],
                'is_correct' => isset($answerData['is_correct']) ? $answerData['is_correct'] : 0,
            ]);
        }
        return redirect()->route('questions.index');
    }

    public function destroyMulti(Question $question){
        $question->answers()->delete();
        $question->delete();

        return redirect()->route('questions.index');
    }



    public function createTrueFalse(){
        $teacher = Teacher::with('courses.subjects')->find(auth()->id());
        $subjects = $teacher->courses->flatMap->subjects->unique('id');
        return view('teacher.question.createTrueFalse', compact( 'subjects'));
    }

    public function storeTrueFalse(Request $request)
    {
        $teacher_id = auth()->id();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|exists:subjects,id',
            'answer' => 'required|boolean',
            'points' => 'required|gt:0',
        ], [
            'title.required' => 'Treść pytania jest wymagana.',
            'subject.required' => 'Wybierz przedmiot.',
            'answer.required' => 'Wybierz odpowiedź.',
            'points.gt' => 'Punkty muszą być większe niż 0.',
        ]);

        $question = Question::create([
            'title' => $data['title'],
            'type' => 'true_false',
            'teacher_id' => $teacher_id,
            'subject_id' => $data['subject'],
        ]);

        $question->answers()->createMany([
            [
                'title' => 'True',
                'is_correct' => $data['answer'] == 1 ? 1 : 0,
                'points' => $data['answer'] == 1 ? $data['points'] : 0,
            ],
            [
                'title' => 'False',
                'is_correct' => $data['answer'] == 0 ? 1 : 0,
                'points' => $data['answer'] == 0 ? $data['points'] : 0,
            ]
        ]);
        return redirect()->route('questions.index')->with('success', 'Dodano pytanie Prawda/Fałsz');
    }

    public function showTrueFalse(Question $question){
        return view('teacher.question.showTrueFalse', compact('question'));
    }

    public function editTrueFalse(Question $question){
        $teacher_id = auth()->id();
        $teacher_courses = CourseTeacher::where('teacher_id', $teacher_id)->get();
        $subjects = $teacher_courses->flatMap(function ($courseTeacher) {
            return $courseTeacher->course->subjects;
        });
        return view('teacher.question.editTrueFalse', compact('question', 'teacher_courses', 'subjects'));
    }

    public function updateTrueFalse(Request $request, Question $question){

        $teacher_id = auth()->id();

        $data = $request->validate([
            'subject' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'answer' => 'required|boolean',
            'points' => 'required|min:1',
            'type' => 'true_false',
        ], [
            'subject.required' => 'Wybierz przedmiot.',
            'title.required' => 'Treść pytania jest wymagana.',
            'answer.required' => 'Wybierz odpowiedź.',
            'points' => 'Punkty są wymagane.',
            'points.min' => 'Wartość punktów musi być większa niż 0.',
        ]);

        $question->update([
            'subject_id' => $data['subject'],
            'title' => $data['title'],
        ]);

        $question->answers()->delete();

        $question->answers()->createMany([
            [
                'title' => 'True',
                'is_correct' => $data['answer'] == 1 ? 1 : 0,
                'points' => $data['answer'] == 1 ? $data['points'] : 0,
            ],
            [
                'title' => 'False',
                'is_correct' => $data['answer'] == 0 ? 1 : 0,
                'points' => $data['answer'] == 0 ? $data['points'] : 0,
            ]
        ]);

        return redirect()->route('questionTrueFalse.show', $question->id);
    }

    public function destroyTrueFalse(Question $question){
        $question->answers()->delete();
        $question->delete();

        return redirect()->route('questions.index');
    }
}
