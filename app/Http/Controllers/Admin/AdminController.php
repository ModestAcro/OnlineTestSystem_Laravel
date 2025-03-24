<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{




    public function registerForm(){
        return view('register');
    }


    public function register(Request $request){
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);

       $validated['password'] = Hash::make($validated['password']);

       $admin = Admin::create($validated);


       return redirect()->route('login.form');
    }
}
