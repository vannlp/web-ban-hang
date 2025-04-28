<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login(Request $request) {
        return Inertia::render('Admin/Login');
    }
    
    public function handleLogin(Request $request) {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }
}
