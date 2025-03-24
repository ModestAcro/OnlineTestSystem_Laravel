<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    public function loginForm(){
        return view('login');
    }

    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Находим пользователя по email
        $admin = Admin::where('email', $validated['email'])->first();
        // Если администратор не найден, ищем студента
        $student = Student::where('email', $validated['email'])->first();
        // Если студент не найден, ищем уцителя
        $teacher = Teacher::where('email', $validated['email'])->first();



        if (!$admin && !$student && !$teacher) {
            // Если пользователя не существует, вернем ошибку для поля email
            return back()->withErrors([
                'email' => 'Taki użytkownik nie istnieje', // Ошибка для email
            ])->withInput();
        }

        // Проверяем, существует ли пользователь и совпадает ли пароль
        if ($admin && Hash::check($validated['password'], $admin->password)) {
            // Входим в систему
            Auth::guard('admin')->login($admin);
            // Перенаправляем на панель управления администратора
            return redirect()->route('admin.dashboard');
        }

        // Проверка, если это студент
        if ($student && Hash::check($validated['password'], $student->password)) {
            Auth::guard('student')->login($student);

            if ($student->must_change_password) {
                return redirect()->route('change-password.index');
            }

            return redirect()->route('student.dashboard');
        }

        // Проверка, если это уцител
        if ($teacher && Hash::check($validated['password'], $teacher->password)) {
            Auth::guard('teacher')->login($teacher);

            if ($teacher->must_change_password) {
                return redirect()->route('change-password.index');
            }

            return redirect()->route('teacher.dashboard');
        }

        // Перенаправляем на панель управления администратора
        return back()->withErrors([
            'password' => 'Nieprawidłowe hasło',
        ])->withInput();
    }


    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();  //Destroy all the data
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }

}
