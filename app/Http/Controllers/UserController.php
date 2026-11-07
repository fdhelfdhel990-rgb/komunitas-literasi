<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\InviteAdminMail;

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

    public function inviteAdmin(Request $request)
    {
        if (Auth::user()->role !== 'super-admin') {
            return abort(403, 'Akses Ditolak. Hanya Super Admin yang dapat mengundang.');
        }

        // PLACEHOLDER: validasi input form sementara, sesuaikan saat FE sudah bikin view
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'privileges' => ['required', 'array']
        ]);

        $newAdmin = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'admin',
            'privileges' => $request->privileges
        ]);

        Mail::to($newAdmin->email)->send(new InviteAdminMail(
            $newAdmin->name, 
            $newAdmin->username, 
            $request->password
        ));

        return back()->with('success', 'Admin berhasil dibuat dan email kredensial telah dikirim!');
    }   
}
