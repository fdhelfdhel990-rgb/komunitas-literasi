<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('login');   // PLACEHOLDER: ganti saat FE sudah bikin view
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginId = $request->login_id;
        $password = $request->password;

        $user = User::withTrashed()
                    ->where('email', $loginId)
                    ->orWhere('username', $loginId)
                    ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login_id' => 'Email/Username atau password salah.',
            ])->onlyInput('login_id');
        }

        if ($user->trashed()) {
            return back()->withErrors([
                'login_id' => 'Akun Anda saat ini sedang dinonaktifkan.',
            ])->onlyInput('login_id');
        }

        Auth::login($user);
        $request->session()->regenerate();
        
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
