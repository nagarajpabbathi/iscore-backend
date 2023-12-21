<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        if (auth()->check()) {

            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        $v = $request->validate([
            'email' => 'required|exists:users',
            'password' => 'required|min:6',
        ]);

        if (auth()->attempt($v)) {

            return redirect()->route('dashboard');
        }

        return redirect()->route('login')->withErrors(['password' => 'password are wrong.']);
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}