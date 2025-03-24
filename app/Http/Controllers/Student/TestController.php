<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AnswerTest;
use App\Models\Question;
use App\Models\ResultTest;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function start($testId)
    {
        $student = auth()->user();

        $test = Test::with('questions.answers')->findOrFail($testId);
        $questions = $test->questions;
        $start_time = now();


        return view('student.test', compact('test', 'questions', 'start_time'));
    }


    public function store(Request $request, Test $test)
    {
        $max_points = $test->questions->sum(function ($question) {
            return $question->answers->sum('points');
        });

        $questions = $test->questions;
        $rules = [];
        foreach ($questions as $question) {
            if ($question->type == 'true_false') {
                $rules["question_{$question->id}"] = 'in:0,1';
            } elseif ($question->type == 'multi_choice') {
                $rules["question_{$question->id}"] = 'array|min:1';
                $rules["question_{$question->id}.*"] = 'exists:answers,id';
            }
        }

        $validated = $request->validate($rules);

        $total_points = 0;
        foreach ($validated as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                // Pobierz ID pytania
                $questionId = substr($key, 9);
                $question = $test->questions()->find($questionId);

                // Jeśli pytanie jest typu "true_false"
                if ($question->type == 'true_false') {
                    $studentAnswersList = $value; // wartość 0 lub 1 przekazana z formularza
                    // Sprawdzamy, która odpowiedź jest poprawna w bazie danych
                    $correctAnswer = $question->answers()->where('is_correct', true)->first();
                    // Jeśli poprawna odpowiedź istnieje, porównujemy ją z odpowiedzią studenta

                    // Zapisywanie odpowiedzi studenta
                    $studentAnswers[] = [
                        'question_id' => $question->id,
                        'answer' => $studentAnswersList,
                    ];

                    if ($correctAnswer) {
                        // Jeśli odpowiedź studenta jest poprawna, dodajemy punkty
                        if (($studentAnswersList == 1 && $correctAnswer->is_correct == 1 && $correctAnswer->title == 'True') || ($studentAnswersList == 0 && $correctAnswer->is_correct == 1 && $correctAnswer->title == 'False')) {
                            $total_points += $correctAnswer->points; // Dodajemy punkty za poprawną odpowiedź
                        }
                    }
                }
                // Jeśli pytanie jest typu "multi_choice"
                elseif ($question->type == 'multi_choice') {

                    // Pobierz wszystkie poprawne odpowiedzi
                    $correctAnswers = $question->answers()->where('is_correct', true)->get();
                    $correctAnswersCount = $correctAnswers->count();

                    // Pobierz odpowiedzi zaznaczone przez studenta
                    $studentAnswersList = $value; // Tablica odpowiedzi studenta

                    // Sprawdzenie, ile odpowiedzi zostało zaznaczone przez studenta
                    $correctSelectedCount = 0;
                    foreach ($studentAnswersList as $answerId) {
                        $answer = $question->answers()->find($answerId);
                        if ($answer && $answer->is_correct) {
                            $correctSelectedCount++;
                        }
                    }

                    // Zapisywanie odpowiedzi studenta
                    $studentAnswers[] = [
                        'question_id' => $question->id,
                        'answer' => json_encode($studentAnswersList), // Zapisujemy odpowiedzi jako JSON
                    ];

                    // Jeśli student zaznaczył więcej odpowiedzi niż jest poprawnych, przypisujemy 0 punktów
                    if (count($studentAnswersList) > $correctAnswersCount) {
                        $total_points += 0; // 0 punktów za pytanie
                    } else {
                        //Jeżeli zaznaczył 2 z 3 odpowiedzi to dodajemy
                        $pointsForThisQuestion = $correctAnswers->sum('points');
                        $total_points += ($pointsForThisQuestion * $correctSelectedCount) / $correctAnswersCount;
                    }
                }
            }
        }

        // Obliczamy procentowy wynik
        $percent_score = $total_points / $max_points * 100;

        // Wywołujemy funkcję calculateGrade, aby obliczyć ocenę
        $grade = $this->calculateGrade($percent_score);

        $start_time = $request->input('start_time');

        // Zapisujemy wynik testu
        $resultTest = ResultTest::create([
            'test_id' => $test->id,
            'student_id' => auth()->user()->id,
            'start_time' => $start_time,
            'end_time' => now(),
            'status' => 'completed',
            'max_score' => $max_points,
            'earned_score' => $total_points,
            'percent_score' => $percent_score,
            'result' => $grade,
        ]);

        return view('student.result', compact('resultTest'));
    }




    // Funkcja pomocnicza do obliczenia oceny
    private function calculateGrade($percent_score)
    {
        if ($percent_score >= 90) {
            return 5; // Bardzo dobry
        } elseif ($percent_score >= 80) {
            return 4.5; // Dobry plus
        } elseif ($percent_score >= 70) {
            return 4; // Dobry
        } elseif ($percent_score >= 60) {
            return 3.5; // Dostateczny plus
        } elseif ($percent_score >= 51) {
            return 3; // Dostateczny
        } else {
            return 2; // Niedostateczny
        }
    }


    public function result(Test $test)
    {
        $result = ResultTest::where('test_id', $test->id)
            ->where('student_id', auth()->id())
            ->latest()
            ->first();

        return view('student.result', compact('test', 'result'));
    }



}
