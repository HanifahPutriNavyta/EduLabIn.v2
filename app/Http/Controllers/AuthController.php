<?php

namespace App\Http\Controllers;

use App\Models\KelasPraktikum;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $role = strtolower($user->role->role_name);
            
            
            // Redirect to appropriate dashboard based on role
            if ($role === 'asprak') {
                return redirect()->route('dashboard.indexAsprak');
            } else {
                // For dosen, laboran, praktikan - use same dashboard
                return redirect()->route('dashboard');
            }
        }   
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string|in:asprak,dosen,laboran,praktikan'
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            
            // Check if user's role matches the selected role
            if (strtolower($user->role->role_name) !== $credentials['role']) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'The selected role does not match your account role.',
                ])->withInput($request->except('password'));
            }

            $request->session()->regenerate();
            
            // Record login session
            $user->sessionLogins()->create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($user->role->role_name === 'asprak') {
                return redirect()->route('dashboard.indexAsprak');
            } else {
                return redirect()->intended('dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function showRegister()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role_id' => 'required|exists:roles,role_id'
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

  
} 