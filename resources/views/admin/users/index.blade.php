@extends('layouts.admin')

@section('title', 'Kelola Admin & Izin')
@section('page_title', 'Kelola Admin & Izin')

@section('content')
    <!-- Flash Notifications -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
            <div class="font-semibold mb-1.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span>Terjadi kesalahan validasi:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-rose-700 ml-1">
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
            <a 
                href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer whitespace-nowrap"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Admin Baru</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Total Akun Admin</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $totalUsers ?? count($users) }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Akun Aktif</div>
            <div class="text-2xl font-bold text-slate-900 font-['Poppins']">{{ $approvedUsers ?? $users->where('status', 'approved')->count() }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs">
            <div class="text-xs text-slate-500 font-medium mb-1">Akun Dinonaktifkan</div>
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
                        <th class="px-4 sm:px-6 py-3.5">Peran</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($users as $index => $u)
                        @php
                            $isSuper = in_array($u->role, ['super-admin', 'super_admin']);
                            $isCurrentUser = Auth::id() === $u->id;
                            $isActive = $u->status === 'approved';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-900 flex items-center gap-2">
                                    <span>{{ $u->name }}</span>
                                    @if ($isCurrentUser)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-normal">(Anda)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-600 font-['Poppins'] text-xs">
                                {{ $u->username ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $isSuper ? 'bg-[#E8EEF8] text-[#0A2373]' : 'bg-slate-100 text-slate-700' }} text-xs font-bold">
                                    {{ $isSuper ? 'Super Admin' : 'Admin' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold whitespace-nowrap {{ $isActive ? 'border-emerald-200 bg-white text-emerald-700' : 'border-slate-200 bg-white text-slate-500' }}">
                                    <span class="h-5 w-5 rounded-full border-2 {{ $isActive ? 'border-emerald-500' : 'border-slate-300' }}" aria-hidden="true"></span>
                                    {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <!-- Action Column -->
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Edit Link to Detail Page -->
                                    <a 
                                        href="{{ route('admin.users.edit', $u->id) }}" 
                                        title="Edit Profil &amp; Hak Akses"
                                        class="p-2 rounded-lg border border-slate-200 text-slate-600 hover:text-[#0A2373] hover:border-[#0A2373] hover:bg-[#0A2373]/5 transition cursor-pointer"
                                    >
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    @if (! $isCurrentUser)
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $u->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                title="Hapus Akun"
                                                class="p-2 rounded-lg border border-slate-200 text-slate-500 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition cursor-pointer"
                                            >
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
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
@endsection
