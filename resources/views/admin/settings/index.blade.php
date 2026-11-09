@extends('layouts.admin')

@section('title', 'Identitas & Konten')
@section('page_title', 'Identitas & Konten')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">06</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Pengaturan Konten</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Statistik Beranda &amp; Informasi Footer
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola angka capaian komunitas pada section beranda serta informasi kontak dan identitas website.
            </p>
        </div>
        <div>
            <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#0A2373] text-[#0A2373] text-xs sm:text-sm font-semibold hover:bg-[#0A2373]/5 transition cursor-pointer">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                <span>Lihat Beranda Publik</span>
            </a>
        </div>
    </div>

    <!-- Setting Form -->
    <div class="max-w-4xl bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
        <form onsubmit="event.preventDefault(); alert('Pengaturan konten dan identitas situs berhasil disimpan!');" class="space-y-8">
            <!-- Section 1: Statistik Beranda -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">1. Angka Capaian Beranda (Homepage Stats)</h3>
                        <p class="text-xs text-slate-500">Angka ini tampil dengan efek animasi counter pada halaman utama.</p>
                    </div>
                    <span class="text-xs font-mono text-slate-400">Section 2</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Berdampak Positif ke</label>
                        <div class="relative">
                            <input type="text" value="≥ 400 Anak" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Label deskripsi: "Anak yang terjangkau kegiatan baca"</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Koleksi Bacaan</label>
                        <div class="relative">
                            <input type="text" value="± 500 Buku" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Label deskripsi: "Buku bacaan fisik di perpustakaan"</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sukarelawan Aktif</label>
                        <div class="relative">
                            <input type="text" value="20 Remaja" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Label deskripsi: "Remaja penggerak literasi"</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Perpustakaan Desa</label>
                        <div class="relative">
                            <input type="text" value="2 Lokasi Binaan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 font-semibold focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Label deskripsi: "Lokasi perpustakaan binaan komunitas"</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Identitas & Kontak Footer -->
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">2. Kontak &amp; Identitas Komunitas</h3>
                        <p class="text-xs text-slate-500">Informasi ini tampil di area footer seluruh halaman web publik.</p>
                    </div>
                    <span class="text-xs font-mono text-slate-400">Footer Area</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Komunitas</label>
                        <input type="text" value="Tambun Selatan, Kabupaten Bekasi, Jawa Barat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor WhatsApp / Narahubung</label>
                            <input type="text" placeholder="+62 8xx-xxxx-xxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Resmi Komunitas</label>
                            <input type="email" placeholder="kontak@literasitamsel.org" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Instagram (@)</label>
                            <input type="text" placeholder="https://instagram.com/literasitamsel" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan YouTube</label>
                            <input type="text" placeholder="https://youtube.com/@literasitamsel" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
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
