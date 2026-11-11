<?php

namespace App\Http\Controllers;

use App\Mail\InviteAdminMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display users management in admin panel.
     */
    public function indexAdmin(): View
    {
        $users = User::latest()->get();
        $totalUsers = $users->count();
        $approvedUsers = $users->where('status', 'approved')->count();
        $pendingUsers = $users->where('status', 'pending')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'approvedUsers', 'pendingUsers'));
    }

    /**
     * Store a new administrator account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,super-admin'],
            'status' => ['required', 'in:approved,pending'],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'] ?: null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin berhasil ditambahkan.');
    }

    /**
     * Update an administrator account.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,super-admin'],
            'status' => ['required', 'in:approved,pending'],
        ]);

        // Prevent self-demotion or self-suspension
        if (Auth::id() === $user->id) {
            if ($validated['status'] === 'pending') {
                return back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri menjadi Pending.');
            }
        }

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'] ?: null,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data akun admin "'.$user->name.'" berhasil diperbarui.');
    }

    /**
     * Quickly toggle status between approved and pending.
     */
    public function toggleStatus(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id && $user->status === 'approved') {
            return back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri menjadi Pending.');
        }

        $newStatus = $user->status === 'approved' ? 'pending' : 'approved';
        $user->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'approved' ? 'Disetujui (Approved)' : 'Menunggu Persetujuan (Pending)';

        return redirect()->route('admin.users.index')
            ->with('success', 'Status akun "'.$user->name.'" berhasil diubah menjadi '.$statusLabel.'.');
    }

    /**
     * Delete an administrator account.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "'.$name.'" berhasil dihapus.');
    }

    public function showLoginForm()
    {
        return view('login');
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

        if (! $user || ! Hash::check($password, $user->password)) {
            return back()->withErrors([
                'login_id' => 'Email/Username atau password salah.',
            ])->onlyInput('login_id');
        }

        if ($user->trashed()) {
            return back()->withErrors([
                'login_id' => 'Akun Anda saat ini sedang dinonaktifkan.',
            ])->onlyInput('login_id');
        }

        if ($user->status === 'pending') {
            return back()->withErrors([
                'login_id' => 'Akun Anda masih dalam status Pending dan menunggu persetujuan dari Super Admin.',
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
        if (! in_array(Auth::user()->role, ['super-admin', 'super_admin'])) {
            return abort(403, 'Akses Ditolak. Hanya Super Admin yang dapat mengundang.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'privileges' => ['required', 'array'],
        ]);

        $newAdmin = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'approved',
            'privileges' => $request->privileges,
        ]);

        Mail::to($newAdmin->email)->send(new InviteAdminMail(
            $newAdmin->name,
            $newAdmin->username,
            $request->password
        ));

        return back()->with('success', 'Admin berhasil dibuat dan email kredensial telah dikirim!');
    }
}
