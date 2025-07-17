<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AuthClientController extends Controller
{
    public function login(Request $request) {
        return Inertia::render('Client/Login');
    }
    
    public function handleLogin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended('/');
    }
    
    public function profile(Request $request) {
        
        return Inertia::render('Client/Profile', [
            'title' => "Trang cá nhân"
        ]);
    }
}
