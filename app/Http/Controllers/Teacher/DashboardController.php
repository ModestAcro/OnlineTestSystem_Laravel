<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboard(){

        $teacher = auth()->user();  // Получаем ID текущего учителя


        $groups = $teacher->groups;
        $questions = $teacher->questions;
        $tests = $teacher->tests;
        $completedTest = $teacher->tests->where('end_time', '<', now());

        // Получаем количество этих вопросов
        $questionsCount = $questions->count();
        $groupsCount = $groups->count();
        $testsCount = $tests->count();
        $completedTestCount = $completedTest->count();



        return view('teacher.dashboard', compact( 'questionsCount', 'groupsCount', 'testsCount', 'completedTestCount'));
    }
}
