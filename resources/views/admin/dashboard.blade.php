@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight">
                Selamat datang di workspace.
            </h1>
        </div>
        <div class="text-xs sm:text-sm text-slate-400 font-medium">
            {{ now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- Metric Cards (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <!-- Card 1: Koleksi Buku -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs sm:shadow-sm hover:shadow-md transition">
            <span class="text-xs font-['Poppins'] font-bold text-slate-400 block mb-3">BK</span>
            <div class="text-xs text-slate-500 font-medium mb-1">Koleksi Buku</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">{{ \App\Models\Book::count() }}</div>
            <a href="{{ route('admin.library.index') }}" class="text-xs font-semibold text-[#0A2373] hover:underline flex items-center gap-1.5">
                <span>Kelola katalog</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <!-- Card 2: Publication -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs sm:shadow-sm hover:shadow-md transition">
            <span class="text-xs font-['Poppins'] font-bold text-slate-400 block mb-3">PB</span>
            <div class="text-xs text-slate-500 font-medium mb-1">Publication</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">9</div>
            <a href="{{ route('admin.publications.index') }}" class="text-xs font-semibold text-[#0A2373] hover:underline flex items-center gap-1.5">
                <span>Kelola publikasi</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <!-- Card 3: Media Partner -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs sm:shadow-sm hover:shadow-md transition">
            <span class="text-xs font-['Poppins'] font-bold text-slate-400 block mb-3">MP</span>
            <div class="text-xs text-slate-500 font-medium mb-1">Media Partner aktif</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">4</div>
            <a href="{{ route('admin.media-partners.index') }}" class="text-xs font-semibold text-[#0A2373] hover:underline flex items-center gap-1.5">
                <span>Kelola mitra</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <!-- Card 4: Akun Admin -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs sm:shadow-sm hover:shadow-md transition">
            <span class="text-xs font-['Poppins'] font-bold text-slate-400 block mb-3">AD</span>
            <div class="text-xs text-slate-500 font-medium mb-1">Akun admin aktif</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-900 mb-4">
                {{ \App\Models\User::where('status', 'approved')->count() > 0 ? \App\Models\User::where('status', 'approved')->count() : 1 }}
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#0A2373] hover:underline flex items-center gap-1.5">
                <span>Kelola akses</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>

    <!-- Lower Grid (Manajemen Konten & Statistik Homepage) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">
        <!-- Left: Manajemen Konten (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs sm:shadow-sm">
            <div class="mb-5">
                <h2 class="text-sm sm:text-base font-bold text-slate-900">Manajemen Konten</h2>
            </div>

            <div class="space-y-3">
                <a href="{{ route('admin.publications.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#0A2373] hover:bg-slate-50 transition group">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#E8EEF8] text-[#0A2373] text-xs font-['Poppins'] font-bold flex items-center justify-center shrink-0">02</span>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-[#0A2373]">Publication</span>
                    </div>
                    <span class="text-xs text-slate-500">Kelola artikel dan status tayang &rarr;</span>
                </a>

                <a href="{{ route('admin.library.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#0A2373] hover:bg-slate-50 transition group">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#E8EEF8] text-[#0A2373] text-xs font-['Poppins'] font-bold flex items-center justify-center shrink-0">03</span>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-[#0A2373]">Digital Library</span>
                    </div>
                    <span class="text-xs text-slate-500">Kelola metadata dan sampul buku &rarr;</span>
                </a>

                <a href="{{ route('admin.join-us.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#0A2373] hover:bg-slate-50 transition group">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#E8EEF8] text-[#0A2373] text-xs font-['Poppins'] font-bold flex items-center justify-center shrink-0">04</span>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-[#0A2373]">Join Us</span>
                    </div>
                    <span class="text-xs text-slate-500">Kelola kartu dan status pendaftaran &rarr;</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#0A2373] hover:bg-slate-50 transition group">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-[#E8EEF8] text-[#0A2373] text-xs font-['Poppins'] font-bold flex items-center justify-center shrink-0">06</span>
                        <span class="text-sm font-semibold text-slate-800 group-hover:text-[#0A2373]">Identitas & Konten</span>
                    </div>
                    <span class="text-xs text-slate-500">Kelola informasi footer &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Right: Statistik Homepage (5 cols) -->
        @php
            $communityStats = \App\Http\Controllers\SettingController::getSettings()['stats'] ?? [];
        @endphp
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-xs sm:shadow-sm flex flex-col justify-between">
            <div>
                <div class="mb-5">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Statistik Homepage</h2>
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm">
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Berdampak Positif ke</span>
                        <span class="font-bold text-slate-900">{{ $communityStats['impact_kids'] ?? '≥ 400 Anak' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Koleksi Bacaan</span>
                        <span class="font-bold text-slate-900">{{ $communityStats['book_collection'] ?? '± 500 Buku' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Sukarelawan Aktif</span>
                        <span class="font-bold text-slate-900">{{ $communityStats['active_volunteers'] ?? '20 Remaja' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-600">Perpustakaan Desa</span>
                        <span class="font-bold text-slate-900">{{ $communityStats['village_libraries'] ?? '2 Lokasi Binaan' }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <a href="{{ route('admin.settings.index') }}" class="inline-block w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-[#0A2373] text-[#0A2373] text-xs sm:text-sm font-semibold hover:bg-[#0A2373]/5 active:scale-[0.98] transition">
                    Edit statistik
                </a>
            </div>
        </div>
    </div>
@endsection
