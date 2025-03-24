<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\TestController as StudentTestController;
use App\Http\Controllers\Teacher\CompletedTestController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ExcelController;
use App\Http\Controllers\Teacher\GroupController;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\TestController;
use Illuminate\Support\Facades\Route;


//  Register
Route::get('/admin/register', [AdminController::class, 'registerForm'])->name('register.form');
Route::post('/admin/register', [AdminController::class, 'register'])->name('register');

//  Login
Route::get('/', [LoginController::class, 'loginForm'])->name('login.form');
Route::post('/', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//  Change password
Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');
Route::post('/change-password', [ChangePasswordController::class, 'change'])->name('change-password');


//Admin
Route::middleware('auth:admin')->group(function () {

    //Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');

    //Teachers
    Route::get('/admin/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/admin/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/admin/teachers/create', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/admin/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/admin/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::patch('/admin/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/admin/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.delete');

    //Courses
    Route::get('/admin/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/admin/courses/create', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/admin/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/admin/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('/admin/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/admin/courses/{course}', [CourseController::class, 'destroy'])->name('courses.delete');

    //Subjects
    Route::get('/admin/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/admin/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/admin/subjects/create', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/admin/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/admin/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::patch('/admin/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/admin/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.delete');

    //Students
    Route::get('/admin/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/admin/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/admin/students/create', [StudentController::class, 'store'])->name('students.store');
    Route::get('/admin/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/admin/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::patch('/admin/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('students.delete');
});


//Teacher
Route::middleware('auth:teacher')->group(function () {
   Route::get('/teacher/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('teacher.dashboard');

    //Tests
    Route::get('/teacher/tests', [TestController::class, 'index'])->name('tests.index');
    Route::get('/teacher/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/teacher/tests/create', [TestController::class, 'store'])->name('tests.store');
    Route::get('/teacher/tests/{test}', [TestController::class, 'show'])->name('tests.show');
    Route::get('/teacher/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::patch('/teacher/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/teacher/tests/{test}', [TestController::class, 'destroy'])->name('tests.delete');
    //AJAX
    Route::get('/get-subjects/{courseId}', [TestController::class, 'getSubjectsByCourse']);
    Route::get('/get-groups/{subjectId}', [TestController::class, 'getGroupsBySubject']);
    //Route::get('/get-questions/{subjectId}', [TestController::class, 'getQuestionsBySubject']);


    //Completed test
    Route::get('/teacher/completed-tests', [CompletedTestController::class, 'index'])->name('completed-tests.index');
    Route::get('/teacher/completed-tests/{test}', [CompletedTestController::class, 'show'])->name('completed-tests.show');
    Route::get('/export-results/{test_id}', [ExcelController::class, 'export'])->name('export.results');


    //Questions
    Route::get('/student/questions', [QuestionController::class, 'index'])->name('questions.index');
    //Multi questions
    Route::get('/student/multi-question/create', [QuestionController::class, 'createMulti'])->name('questionMulti.create');
    Route::post('/student/multi-question/create', [QuestionController::class, 'storeMulti'])->name('questionMulti.store');
    Route::get('/student/multi-question/{question}', [QuestionController::class, 'showMulti'])->name('questionMulti.show');
    Route::get('/student/multi-question/{question}/edit', [QuestionController::class, 'editMulti'])->name('questionMulti.edit');
    Route::patch('/student/multi-question/{question}', [QuestionController::class, 'updateMulti'])->name('questionMulti.update');
    Route::delete('/student/multi-question/{question}', [QuestionController::class, 'destroyMulti'])->name('questionMulti.delete');
    //True/False questions
    Route::get('/teacher/truefalse-question/create', [QuestionController::class, 'createTrueFalse'])->name('questionTrueFalse.create');
    Route::post('/teacher/truefalse-question/create', [QuestionController::class, 'storeTrueFalse'])->name('questionTrueFalse.store');
    Route::get('/teacher/truefalse-question/{question}', [QuestionController::class, 'showTrueFalse'])->name('questionTrueFalse.show');
    Route::get('/teacher/truefalse-question/{question}/edit', [QuestionController::class, 'editTrueFalse'])->name('questionTrueFalse.edit');
    Route::patch('/teacher/truefalse-question/{question}', [QuestionController::class, 'updateTrueFalse'])->name('questionTrueFalse.update');
    Route::delete('/teacher/truefalse-question/{question}', [QuestionController::class, 'destroyTrueFalse'])->name('questionTrueFalse.delete');


    //Groups
    Route::get('/student/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/student/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/student/groups/create', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/student/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/student/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('/student/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/student/groups/{group}', [GroupController::class, 'destroy'])->name('groups.delete');
    //AJAX
    Route::get('/get/group/subjects/{courseId}', [GroupController::class, 'getSubjectsByCourse']);
});


//Student
Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/student/test/{test}', [StudentTestController::class, 'start'])->name('student.test.start');
    Route::post('/student/test/{test}', [StudentTestController::class, 'store'])->name('student.test.store');
    Route::get('/student/test/{test}/result', [StudentTestController::class, 'result'])->name('student.test.result');
    Route::get('/student/competed-tests', [StudentDashboardController::class, 'showCompletedTests'])->name('student.competed-tests');








});
























