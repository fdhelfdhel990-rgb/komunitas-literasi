@extends('layouts.admin')

@section('title', 'Kelola Admin & Izin')
@section('page_title', 'Kelola Admin & Izin')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">07</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Pengaturan Akses</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kelola Admin &amp; Hak Akses
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Daftar akun pengelola komunitas, status persetujuan pendaftaran, dan peran pengguna.
            </p>
        </div>
    </div>

    @php
        $users = \App\Models\User::all();
    @endphp

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
                            <td class="px-4 sm:px-6 py-4 text-center font-mono text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $u->name }}</div>
                                <div class="text-xs text-slate-400">{{ $u->email }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-600 font-mono text-xs">
                                {{ $u->username ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $u->role === 'super_admin' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $u->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                @if($u->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <button type="button" onclick="alert('Kelola hak akses untuk user ini.')" class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition">
                                    <i class="fa-solid fa-gear text-xs"></i>
                                </button>
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
