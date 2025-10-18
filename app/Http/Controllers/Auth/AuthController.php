<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\habits;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    public function register() {
        return view('auth.register');
    }

    public function login() {
        return view('auth.login');
    }

    public function store(Request $request) {
        $request->validate([
            "username" => "required|max:65",
            "email" => "required",
            "password" => "required|min:8|confirmed"
        ]);

        User::create([
            "id" => Str::random(13),
            "username" => $request->username,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->withSuccess('Account successfully registered & logged in');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->withSuccess("Welcome back, ".auth()->user()->username);
        }

        return back()->withErrors([
            "email" => "Your account does not exist in the system, please check again :("
        ])->onlyInput('email');
    }

    public function dashboard () {
        if(Auth::check()) { 
            $habits = habits::getHabitsByUser(auth()->user()->id);
            return view('auth.dashboard', compact('habits'));
        }

        return redirect()->route('login')
            ->withErrors([
            'email' => 'Please log in first to access this page.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }    
}
