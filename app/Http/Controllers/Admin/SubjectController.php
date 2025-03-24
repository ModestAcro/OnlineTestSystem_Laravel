<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(){

        $subjects = Subject::all();
        $subjectsCount = $subjects->count();

        return view('admin.subject.subjects', compact('subjects', 'subjectsCount'));
    }

    public function create(){
        return view('admin.subject.create');
    }

    public function store(Request $request){
        $data = request()->validate([
           'name' => 'required|string',
           'comments' => 'nullable|string',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
        ]);

        Subject::create($data);
        return redirect()->route('subjects.index');
    }

    public function show(Subject $subject){
        return view('admin.subject.show', compact('subject'));
    }

    public function edit(Subject $subject){
        return view('admin.subject.edit', compact('subject'));
    }

    public function update(Subject $subject){
        $data = request()->validate([
            'name' => 'required|string',
            'comments' => 'nullable|string',
        ], [
            'name.required' => 'Nazwa jest wymagana.',
        ]);

        $subject->update($data);
        return view('admin.subject.show', compact('subject'));
    }

    public function destroy(Subject $subject){
        $subject->delete();
        return redirect()->route('subjects.index');
    }
}
