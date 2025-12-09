<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register()
    {
        return view('pages.auth.register');
    }

    public function register_(RegisterRequest $req)
    {
        $req->validated();

        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        return response()->json(['message' => 'Berhasil mendaftar, silakan login.'], 201);
    }

    public function login()
    {
        return view('pages.auth.login');
    }

    public function login_(LoginRequest $req)
    {
        $req->validated();

        $credentials = $req->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();

            $user = Auth::user();
            $redirectUrl = $user->role === 'admin' ? '/admin/project' : '/karyawan/task';

            return response()->json([
                'message' => 'Login berhasil.',
                'redirect_url' => $redirectUrl
            ], 200);
        }

        return response()->json(['message' => 'Email atau password salah.'], 401);
    }

    public function logout(Request $req)
    {
        Auth::logout();

        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect('/login');
    }
}
