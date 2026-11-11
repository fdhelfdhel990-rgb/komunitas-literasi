@extends('layouts.admin')

@section('title', 'Kelola Admin & Izin')
@section('page_title', 'Kelola Admin & Izin')

@section('content')
    <!-- Flash Notifications -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-[#E8EEF8] border border-[#0A2373]/20 text-[#0A2373] text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-[#0A2373]"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-[#0A2373]/70 hover:text-[#0A2373]">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-slate-100 border border-slate-300 text-slate-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-slate-700"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-slate-500 hover:text-slate-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-slate-100 border border-slate-300 text-slate-800 text-xs sm:text-sm">
            <div class="font-bold mb-1 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-slate-700"></i>
                <span>Terjadi kesalahan validasi:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kelola Admin &amp; Hak Akses
            </h1>
        </div>
        <div>
            <button 
                type="button" 
                onclick="openModal('create-user-modal')"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer whitespace-nowrap"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Admin Baru</span>
            </button>
        </div>
    </div>

    <!-- Summary Metrics (Design System Styling) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Total Akun Admin</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $totalUsers ?? count($users) }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Status Disetujui (Approved)</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $approvedUsers ?? $users->where('status', 'approved')->count() }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Menunggu Persetujuan (Pending)</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $pendingUsers ?? $users->where('status', 'pending')->count() }}</div>
        </div>
    </div>

    <!-- User Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-12 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama &amp; Email</th>
                        <th class="px-4 sm:px-6 py-3.5">Username</th>
                        <th class="px-4 sm:px-6 py-3.5">Role</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($users as $index => $u)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-900 flex items-center gap-2">
                                    <span>{{ $u->name }}</span>
                                    @if (Auth::id() === $u->id)
                                        <span class="text-[10px] text-slate-400 font-normal">(Anda)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-600 font-['Poppins'] text-xs">
                                {{ $u->username ?? '-' }}
                            </td>
                            <!-- Role: Teks bersih bawaan sistem -->
                            <td class="px-4 sm:px-6 py-4 text-xs font-medium text-slate-800">
                                {{ in_array($u->role, ['super-admin', 'super_admin']) ? 'Super Admin' : 'Admin' }}
                            </td>
                            <!-- Status: Teks bersih bawaan sistem -->
                            <td class="px-4 sm:px-6 py-4 text-center text-xs font-semibold {{ $u->status === 'approved' ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ $u->status === 'approved' ? 'Approved' : 'Pending' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.users.toggle-status', $u->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if ($u->status === 'pending')
                                            <button 
                                                type="submit" 
                                                title="Setujui Akun (Approve)"
                                                class="px-2.5 py-1 rounded-lg bg-[#0A2373] hover:bg-[#071a57] text-white text-xs font-semibold transition cursor-pointer shadow-2xs"
                                            >
                                                <i class="fa-solid fa-check text-[10px] mr-1"></i>
                                                <span>Setujui</span>
                                            </button>
                                        @else
                                            <button 
                                                type="submit" 
                                                title="Ubah Menjadi Pending"
                                                onclick="return confirm('Yakin ingin mengubah status akun {{ $u->name }} menjadi Pending?')"
                                                class="px-2 py-1 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 text-xs transition cursor-pointer"
                                                @if (Auth::id() === $u->id) disabled title="Tidak dapat mengubah akun sendiri" @endif
                                            >
                                                <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Edit User Button -->
                                    <button 
                                        type="button" 
                                        onclick="openModal('edit-user-modal-{{ $u->id }}')" 
                                        title="Edit Data & Hak Akses"
                                        class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-[#0A2373] hover:border-[#0A2373] hover:bg-[#0A2373]/5 transition cursor-pointer"
                                    >
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    @if (Auth::id() !== $u->id)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                title="Hapus Akun"
                                                class="p-1.5 rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition cursor-pointer"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal for this user -->
                        <div id="edit-user-modal-{{ $u->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeModal('edit-user-modal-{{ $u->id }}')"></div>
                            
                            <div class="flex min-h-full items-center justify-center p-4">
                                <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden p-6 z-10 animate-in fade-in zoom-in-95 duration-150">
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                                        <h3 class="text-base font-bold text-slate-900">
                                            Edit Akun Admin: {{ $u->name }}
                                        </h3>
                                        <button type="button" onclick="closeModal('edit-user-modal-{{ $u->id }}')" class="text-slate-400 hover:text-slate-700">
                                            <i class="fa-solid fa-xmark text-lg"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('admin.users.update', $u->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                            <input type="text" name="name" required value="{{ old('name', $u->name) }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                                                <input type="text" name="username" value="{{ old('username', $u->username) }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                                                <input type="email" name="email" required value="{{ old('email', $u->email) }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Peran <span class="text-rose-500">*</span></label>
                                                <select name="role" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] bg-white">
                                                    <option value="admin" @selected(in_array($u->role, ['admin']))>Admin</option>
                                                    <option value="super-admin" @selected(in_array($u->role, ['super-admin', 'super_admin']))>Super Admin</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Akun <span class="text-rose-500">*</span></label>
                                                <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] bg-white">
                                                    <option value="approved" @selected($u->status === 'approved')>Approved (Aktif)</option>
                                                    <option value="pending" @selected($u->status === 'pending')>Pending (Menunggu)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                                        </div>

                                        <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                                            <button type="button" onclick="closeModal('edit-user-modal-{{ $u->id }}')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-4 py-2 rounded-xl bg-[#0A2373] text-white text-xs font-semibold hover:bg-[#071a57] transition cursor-pointer shadow-xs">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada user terdaftar di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="create-user-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeModal('create-user-modal')"></div>
        
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden p-6 z-10 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-5">
                    <h3 class="text-base font-bold text-slate-900">
                        Tambah Admin Baru
                    </h3>
                    <button type="button" onclick="closeModal('create-user-modal')" class="text-slate-400 hover:text-slate-700">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="Contoh: Sarah Anindya" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                            <input type="text" name="username" placeholder="sarah_admin" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required placeholder="sarah@literasitamsel.id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Peran <span class="text-rose-500">*</span></label>
                            <select name="role" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] bg-white">
                                <option value="admin" selected>Admin</option>
                                <option value="super-admin">Super Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status Akun <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm focus:outline-none focus:border-[#0A2373] bg-white">
                                <option value="approved" selected>Approved (Langsung Aktif)</option>
                                <option value="pending">Pending (Menunggu Persetujuan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeModal('create-user-modal')" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#0A2373] text-white text-xs font-semibold hover:bg-[#071a57] transition cursor-pointer shadow-xs">
                            Simpan Akun Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection
