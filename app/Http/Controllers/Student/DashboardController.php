<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ResultTest;
use App\Models\Student;
use App\Models\Test;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Controller
    public function dashboard()
    {
        $student = auth()->user();
        $tests = $student->groups
            ->flatMap->tests
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());

        $attemptsUsed = [];

        // Filtruj testy, gdzie liczba prób jest mniejsza niż 'number_of_trials'
        $tests = $tests->filter(function ($test) use ($student, &$attemptsUsed) {
            // Liczba prób dla danego testu
            $attemptCount = ResultTest::where('student_id', $student->id)
                ->where('test_id', $test->id)
                ->count();

            // Dodaj liczbę prób do tablicy
            $attemptsUsed[$test->id] = $attemptCount;

            // Sprawdź, czy liczba prób jest mniejsza niż dozwolona liczba prób (number_of_trials)
            return $attemptCount < $test->number_of_trials;
        });

        return view('student.dashboard', compact('tests', 'attemptsUsed'));
    }


    public function showCompletedTests()
    {
        $studentId = auth()->id();
        $completedTests = ResultTest::with('test')
        ->where('student_id', $studentId)
            ->get();

        $bestTests = $completedTests->groupBy('test_id')->map(function ($tests) {

            return $tests->sortByDesc('result')->first();
        });

        // Количество тестов с наибольшим результатом для каждого теста
        $completedTestCount = $bestTests->count();

        return view('student.completed', compact('bestTests', 'completedTestCount'));
    }







}
