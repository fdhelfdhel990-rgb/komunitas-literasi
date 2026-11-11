@extends('layouts.admin')

@section('title', 'Identitas & Konten')
@section('page_title', 'Identitas & Konten')

@section('content')
    <!-- Flash Notifications -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
            <div class="font-bold mb-1 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Terjadi kesalahan pengisian form:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 sm:mb-8">
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
            Statistik Beranda &amp; Informasi Footer
        </h1>
    </div>

    <!-- Setting Form -->
    <div class="max-w-4xl bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Section 1: Statistik Beranda -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">1. Angka Capaian Beranda (Homepage Stats)</h3>
                    </div>
                    <span class="text-xs font-['Poppins'] font-bold text-slate-400">Section 2</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Berdampak Positif ke</label>
                        <div class="relative">
                            <input type="text" name="impact_kids" required value="{{ old('impact_kids', $settings['stats']['impact_kids'] ?? '≥ 400 Anak') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Koleksi Bacaan</label>
                        <div class="relative">
                            <input type="text" name="book_collection" required value="{{ old('book_collection', $settings['stats']['book_collection'] ?? '± 500 Buku') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sukarelawan Aktif</label>
                        <div class="relative">
                            <input type="text" name="active_volunteers" required value="{{ old('active_volunteers', $settings['stats']['active_volunteers'] ?? '20 Remaja') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Perpustakaan Desa</label>
                        <div class="relative">
                            <input type="text" name="village_libraries" required value="{{ old('village_libraries', $settings['stats']['village_libraries'] ?? '2 Lokasi Binaan') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Identitas & Kontak Footer -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">2. Kontak &amp; Identitas Komunitas</h3>
                    </div>
                    <span class="text-xs font-['Poppins'] font-bold text-slate-400">Footer Area</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Komunitas</label>
                        <input type="text" name="address" required value="{{ old('address', $settings['contact']['address'] ?? 'Tambun Selatan, Kabupaten Bekasi, Jawa Barat') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / Narahubung</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['contact']['whatsapp'] ?? '+62 812-3456-7890') }}" placeholder="+62 8xx-xxxx-xxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Resmi Komunitas</label>
                            <input type="email" name="email" value="{{ old('email', $settings['contact']['email'] ?? 'kontak@literasitamsel.org') }}" placeholder="kontak@literasitamsel.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Instagram (@)</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $settings['contact']['instagram'] ?? 'https://instagram.com/literasitamsel') }}" placeholder="https://instagram.com/literasitamsel" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan YouTube</label>
                            <input type="text" name="youtube" value="{{ old('youtube', $settings['contact']['youtube'] ?? 'https://youtube.com/@literasitamsel') }}" placeholder="https://youtube.com/@literasitamsel" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
