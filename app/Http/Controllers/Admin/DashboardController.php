<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class DashboardController extends Controller
{
    
    public function index(Request $request) {
        return Inertia::render('Admin/Home', [
            'title' => 'Dashboard'
        ]);
    }
    
    public function testApi(Request $request) {
        // $data = $this->validate($request, ['b' => 'required', 'c' => 'required']);
        return Response::success("Tesst HHHHH", 200);
    }
}
