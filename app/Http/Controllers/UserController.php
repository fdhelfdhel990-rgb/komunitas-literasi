<?php

namespace App\Http\Controllers;

use App\Jobs\SendAdminInvitationJob;
use App\Mail\InviteAdminMail;
use App\Models\User;
use App\Notifications\NewAdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Pastikan hanya Super Admin yang dapat mengakses kelola admin.
     */
    protected function checkSuperAdmin(): void
    {
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya dapat diakses oleh Super Admin.');
        }
    }

    /**
     * Ubah array checkbox dari form menjadi JSON boolean baku.
     */
    protected function formatPrivileges(array $selectedModules): array
    {
        $formatted = [];
        foreach (User::MODULES as $module) {
            $formatted[$module] = in_array($module, $selectedModules, true);
        }

        return $formatted;
    }

    /**
     * Menampilkan daftar seluruh admin (aktif maupun tidak aktif / soft-deleted).
     */
    public function indexAdmin(): View
    {
        $this->checkSuperAdmin();

        $users = User::withTrashed()->latest()->get();
        $totalUsers = $users->count();
        $activeUsers = $users->where('status', 'aktif')->whereNull('deleted_at')->count();
        $inactiveUsers = $users->filter(fn ($u) => $u->status === 'tidak aktif' || $u->trashed())->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    /**
     * Menampilkan form tambah / undang admin baru.
     */
    public function create(): View
    {
        $this->checkSuperAdmin();

        return view('admin.users.create');
    }

    /**
     * Menyimpan admin baru dan mengirim kredensial via email SMTP (.env).
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'username'     => ['nullable', 'string', 'max:50', 'unique:users,username'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:6'],
            'privileges'   => ['required', 'array', 'min:1'],
            'privileges.*' => ['required', 'string', 'distinct', 'in:' . implode(',', User::MODULES)],
        ], [
            'privileges.required' => 'Admin wajib memiliki minimal 1 hak akses (privilege).',
            'privileges.min'      => 'Admin wajib memiliki minimal 1 hak akses (privilege).',
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'username'   => $validated['username'] ?: null,
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => 'admin',
            'status'     => 'aktif',
            'privileges' => $this->formatPrivileges($validated['privileges']),
        ]);

        // Kirim email kredensial akun via SMTP yang terdaftar di .env
        dispatch(new SendAdminInvitationJob($user, $validated['password']));

        // Simpan notifikasi lonceng untuk Super Admin jika tabel notifications tersedia
        if (Auth::user() && Schema::hasTable('notifications')) {
            Auth::user()->notify(new NewAdminNotification($user));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "' . $validated['name'] . '" berhasil dibuat dan email undangan telah dikirim.');
    }

    /**
     * Menampilkan form edit profil dan hak akses admin.
     */
    public function edit(int|string $id): View
    {
        $this->checkSuperAdmin();

        $user = User::withTrashed()->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data dan hak akses admin.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::withTrashed()->findOrFail($id);
        $isSuperAdmin = $user->isSuperAdmin();

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'username'     => ['nullable', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'     => ['nullable', 'string', 'min:6'],
            'privileges'   => $isSuperAdmin ? ['nullable', 'array'] : ['required', 'array', 'min:1'],
            'privileges.*' => ['required', 'string', 'distinct', 'in:' . implode(',', User::MODULES)],
        ], [
            'privileges.required' => 'Admin wajib memiliki minimal 1 hak akses (privilege).',
            'privileges.min'      => 'Admin wajib memiliki minimal 1 hak akses (privilege).',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'] ?: null;
        $user->email = $validated['email'];
        $user->privileges = $isSuperAdmin
            ? User::fullPrivileges()
            : $this->formatPrivileges($validated['privileges']);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data akun admin "' . $user->name . '" berhasil diperbarui.');
    }

    /**
     * Menonaktifkan akun admin (status = 'tidak aktif' + Soft Delete).
     */
    public function deactivate(int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::findOrFail($id);

        if ($user->isSuperAdmin() || Auth::id() === $user->id) {
            return back()->with('error', 'Akun Super Admin tidak dapat dinonaktifkan sebelum jabatan dipindahtangankan.');
        }

        $user->update(['status' => 'tidak aktif']);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "' . $user->name . '" telah dinonaktifkan.');
    }

    /**
     * Mengaktifkan kembali akun admin dari Soft Delete (restore + status = 'aktif').
     */
    public function restore(int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        $user->update(['status' => 'aktif']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "' . $user->name . '" berhasil diaktifkan kembali.');
    }

    /**
     * Menghapus akun secara permanen (khusus akun yang sudah dinonaktifkan).
     */
    public function forceDestroy(int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->isSuperAdmin() || Auth::id() === $user->id) {
            return back()->with('error', 'Akun Super Admin tidak dapat dihapus.');
        }

        $name = $user->name;
        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun admin "' . $name . '" telah dihapus secara permanen.');
    }

    /**
     * Memindahtangankan jabatan Super Admin ke admin aktif lainnya.
     */
    public function transferSuperAdmin(int|string $id): RedirectResponse
    {
        $this->checkSuperAdmin();

        $currentSuper = Auth::user();
        $targetAdmin = User::findOrFail($id);

        if ($targetAdmin->id === $currentSuper->id) {
            return back()->with('error', 'Anda sudah menjabat sebagai Super Admin.');
        }

        if ($targetAdmin->status !== 'aktif') {
            return back()->with('error', 'Jabatan Super Admin hanya dapat dipindahkan ke admin yang berstatus aktif.');
        }

        DB::transaction(function () use ($currentSuper, $targetAdmin) {
            // Admin terpilih menjadi Super Admin dengan seluruh privilege = true
            $targetAdmin->update([
                'role'       => 'super-admin',
                'status'     => 'aktif',
                'privileges' => User::fullPrivileges(),
            ]);

            // Mantan Super Admin turun menjadi admin biasa, tetapi full privilege tetap dipertahankan
            $currentSuper->update([
                'role'       => 'admin',
                'privileges' => User::fullPrivileges(),
            ]);
        });

        return redirect()->route('admin.dashboard')
            ->with('success', 'Jabatan Super Admin berhasil dipindahkan ke "' . $targetAdmin->name . '".');
    }
}