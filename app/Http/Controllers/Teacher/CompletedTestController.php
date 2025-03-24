<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedTestController extends Controller
{
    public function index(){
        $teacher = auth()->user();
        $completedTests = $teacher->tests()->where('end_time', '<', now())->paginate(10);
        $completedTestsCount = $completedTests->count();
        return view('teacher.completedTests.index', compact('completedTests', 'completedTestsCount'));
    }

    public function show($id){
        $test = Test::findOrFail($id);
        $results = $test->resultTests;
        return view('teacher.completedTests.show', compact('test', 'results'));
    }
}
