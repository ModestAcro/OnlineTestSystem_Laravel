<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function index(){
        return view('changePassword');
    }

    public function change(Request $request)
    {
        // Проверяем пользователя во всех гвардах
        $user = Auth::guard('teacher')->user()
            ?? Auth::guard('student')->user();

        // Если пользователь не найден, редирект на логин
        if (!$user) {
            return redirect()->route('login')->withErrors([
                'error' => 'Nie jesteś zalogowany.',
            ]);
        }

        // Валидация
        $data = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Обновление пароля
        $user->password = bcrypt($data['password']);
        $user->must_change_password = 0;
        $user->save();

        // Разлогиниваем пользователя и просим залогиниться снова
        Auth::guard('teacher')->logout();
        Auth::guard('student')->logout();

        return redirect()->route('login')->with('success', 'Hasło zostało zmienione. Zaloguj się ponownie.');
    }


}
