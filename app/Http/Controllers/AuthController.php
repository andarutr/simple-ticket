<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function register()
    {
        return view('pages.auth.register');
    }

    public function register_(RegisterRequest $req)
    {
        $req->validated();

        $this->userRepository->create($req->only(['name', 'email', 'password']));

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
