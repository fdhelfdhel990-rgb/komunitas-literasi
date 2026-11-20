<?php

namespace App\Http\Controllers;

use App\Jobs\SendAdminInvitationJob;
use App\Mail\InviteAdminMail;
use App\Models\User;
use App\Notifications\NewAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Check if authenticated user is Super Admin.
     */
    protected function checkSuperAdmin(): void
    {
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya dapat diakses oleh Super Admin.');
        }
    }

    /**
     * Display users management in admin panel.
     */
    public function indexAdmin(): View
    {
        $this->checkSuperAdmin();

        $users = User::latest()->get();
        $totalUsers = $users->count();
        $approvedUsers = $users->where('status', 'approved')->count();
        $pendingUsers = $users->where('status', 'pending')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'approvedUsers', 'pendingUsers'));
    }

    /**
     * Show the dedicated form for creating a new admin.
     */
    public function create(): View
    {
        $this->checkSuperAdmin();

        return view('admin.users.create');
    }

    /**
     * Store a new administrator account.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkSuperAdmin();

        // Hapus permanen data akun sampah (soft-deleted) jika email/username sama agar bisa digunakan kembali
        User::onlyTrashed()->where('email', $request->email)
            ->when($request->username, fn($q) => $q->orWhere('username', $request->username))
            ->forceDelete();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'privileges' => ['required', 'array', 'min:1', 'max:6'],
            'privileges.*' => ['required', 'string', 'distinct', 'in:dashboard,publications,library,join-us,media-partners,settings'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'] ?: null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'status' => 'approved',
            'privileges' => $validated['privileges'],
        ]);

        // Kirim email kredensial akun menggunakan Queue Job (Modul Bagian 7)
        dispatch(new SendAdminInvitationJob($user, $validated['password']));

        // Kirim notifikasi ke Super Admin (Modul Bagian 4)
        if (Auth::user()) {
            Auth::user()->notify(new NewAdminNotification($user));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "'.$validated['name'].'" berhasil ditambahkan dan email undangan telah dikirim via antrean (Queue).');
    }

    /**
     * Show the dedicated form for editing an administrator account.
     */
    public function edit(int|string $id): View
    {
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update an administrator account.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        // Hapus permanen data akun sampah (soft-deleted) jika email/username sama agar bisa digunakan kembali
        User::onlyTrashed()->where('email', $request->email)
            ->when($request->username, fn($q) => $q->orWhere('username', $request->username))
            ->forceDelete();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'unique:users,username,'.$user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => $user->isSuperAdmin() ? ['nullable', 'in:approved'] : ['required', 'in:approved,pending'],
            'privileges' => $user->isSuperAdmin() ? ['nullable', 'array'] : ['required', 'array', 'min:1', 'max:6'],
            'privileges.*' => ['required', 'string', 'distinct', 'in:dashboard,publications,library,join-us,media-partners,settings'],
        ]);

        $isSuperAdmin = $user->isSuperAdmin();

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'] ?: null,
            'email' => $validated['email'],
            'role' => $isSuperAdmin ? $user->role : 'admin',
            'status' => $isSuperAdmin ? $user->status : $validated['status'],
            'privileges' => $isSuperAdmin ? $user->privileges : $validated['privileges'],
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
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $newStatus = $user->status === 'approved' ? 'pending' : 'approved';
        $user->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'approved' ? 'Aktif' : 'Nonaktif';

        return redirect()->route('admin.users.index')
            ->with('success', 'Status peran "'.$user->name.'" berhasil diubah menjadi '.$statusLabel.'.');
    }

    /**
     * Delete an administrator account.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

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

        // Kirim email kredensial akun menggunakan Queue Job (Modul Bagian 7)
        dispatch(new SendAdminInvitationJob($newAdmin, $request->password));

        // Kirim notifikasi ke Super Admin (Modul Bagian 4)
        if (Auth::user()) {
            Auth::user()->notify(new NewAdminNotification($newAdmin));
        }

        return back()->with('success', 'Admin berhasil dibuat dan email kredensial telah dimasukkan ke antrean pengiriman (Queue)!');
    }
}
