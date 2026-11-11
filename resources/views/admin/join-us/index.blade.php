@extends('layouts.admin')

@section('title', 'Kelola Join Us & Kolaborasi')
@section('page_title', 'Join Us')

@section('content')
    <!-- Flash Messages -->
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

    @if (isset($errors) && $errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm shadow-xs">
            <div class="font-semibold mb-1.5 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 ml-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$edit)
        <!-- ============================================================== -->
        <!-- VIEW: 3 SIMPLE CARDS MENU                                      -->
        <!-- ============================================================== -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kegiatan Relawan &amp; Join Us
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <!-- Card 1: Ikut Volunteer (Pencantuman Link Form) -->
            <a href="{{ route('admin.join-us.index', ['edit' => 'volunteer']) }}" class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-md hover:border-[#0A2373] transition flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#E8EEF8] text-[#0A2373] flex items-center justify-center mb-4 text-xl group-hover:scale-105 transition">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <h2 class="text-base font-bold text-slate-900 group-hover:text-[#0A2373] transition">
                            Ikut Volunteer
                        </h2>
                        <span class="text-xs font-medium {{ ($settings['volunteer']['status'] ?? 'Buka') === 'Buka' ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $settings['volunteer']['status'] ?? 'Buka' }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mb-3 line-clamp-2">
                        {{ $settings['volunteer']['description'] ?? 'Pencantuman link formulir pendaftaran relawan komunitas.' }}
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-link text-[10px]"></i>
                        <span class="truncate">{{ !empty($settings['volunteer']['form_link']) ? 'Link Form Terpasang' : 'Belum Ada Link' }}</span>
                    </div>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#0A2373]">
                    <span>Kelola Link Formulir</span>
                    <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- Card 2: Volunteer Event (Bisa Bertambah: Tambah, Edit, Hapus) -->
            <a href="{{ route('admin.join-us.index', ['edit' => 'event']) }}" class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-md hover:border-[#0A2373] transition flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#E8EEF8] text-[#0A2373] flex items-center justify-center mb-4 text-xl group-hover:scale-105 transition">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <h2 class="text-base font-bold text-slate-900 group-hover:text-[#0A2373] transition">
                            Volunteer Event
                        </h2>
                        <span class="text-xs font-medium text-[#0A2373] font-['Poppins'] font-bold">
                            {{ count($settings['events'] ?? []) }} Event
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mb-3 line-clamp-2">
                        Kelola agenda kegiatan sukarelawan (tambah, edit, dan hapus event berkala).
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-layer-group text-[10px]"></i>
                        <span>Daftar kegiatan sukarelawan aktif</span>
                    </div>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#0A2373]">
                    <span>Kelola Daftar Event</span>
                    <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>

            <!-- Card 3: Ajukan Kerja Sama Kolaborasi (Pencantuman Link Form) -->
            <a href="{{ route('admin.join-us.index', ['edit' => 'collaboration']) }}" class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-xs hover:shadow-md hover:border-[#0A2373] transition flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-[#E8EEF8] text-[#0A2373] flex items-center justify-center mb-4 text-xl group-hover:scale-105 transition">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <h2 class="text-base font-bold text-slate-900 group-hover:text-[#0A2373] transition">
                            Ajukan Kerja Sama Kolaborasi
                        </h2>
                        <span class="text-xs font-medium {{ ($settings['collaboration']['status'] ?? 'Buka') === 'Buka' ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $settings['collaboration']['status'] ?? 'Buka' }}
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 mb-3 line-clamp-2">
                        {{ $settings['collaboration']['description'] ?? 'Tautan formulir kemitraan dan kolaborasi program.' }}
                    </div>
                    <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-link text-[10px]"></i>
                        <span class="truncate">{{ !empty($settings['collaboration']['form_link']) ? 'Link Form Terpasang' : 'Belum Ada Link' }}</span>
                    </div>
                </div>
                <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-[#0A2373]">
                    <span>Kelola Link Formulir</span>
                    <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-1 transition"></i>
                </div>
            </a>
        </div>

    @elseif ($edit === 'volunteer')
        <!-- ============================================================== -->
        <!-- VIEW: EDIT LINK IKUT VOLUNTEER (MIRIP KERJA SAMA KOLABORASI)   -->
        <!-- ============================================================== -->
        <a href="{{ route('admin.join-us.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-4">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Menu Pilihan</span>
        </a>

        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            Pengaturan Tautan Formulir Ikut Volunteer
        </h1>

        <div class="max-w-2xl bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
            <form action="{{ route('admin.join-us.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="section" value="volunteer">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Program <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $settings['volunteer']['title'] ?? 'Ikut Volunteer (Relawan Komunitas)') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Formulir Pendaftaran (Google Form / Link) <span class="text-rose-500">*</span></label>
                    <input type="url" name="form_link" required placeholder="https://docs.google.com/forms/d/... atau https://forms.gle/..." value="{{ old('form_link', $settings['volunteer']['form_link'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Buka" @selected(old('status', $settings['volunteer']['status'] ?? '') === 'Buka')>Buka (Pendaftaran Aktif)</option>
                        <option value="Tutup" @selected(old('status', $settings['volunteer']['status'] ?? '') === 'Tutup')>Tutup (Pendaftaran Ditutup)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">{{ old('description', $settings['volunteer']['description'] ?? '') }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.join-us.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

    @elseif ($edit === 'event')
        <!-- ============================================================== -->
        <!-- VIEW: DAFTAR EVENT RELAWAN (TAMBAH, EDIT, HAPUS)               -->
        <!-- ============================================================== -->
        <a href="{{ route('admin.join-us.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-4">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Menu Pilihan</span>
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Kelola Event Sukarelawan
                </h1>
            </div>
            <div>
                <button 
                    type="button" 
                    onclick="openModal('create-event-modal')" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Event Baru</span>
                </button>
            </div>
        </div>

        <!-- Event Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden mb-8">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="text-xs sm:text-sm font-semibold text-slate-800">
                    Total Event: <span class="text-[#0A2373] font-['Poppins'] font-bold">{{ count($settings['events'] ?? []) }}</span> kegiatan
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5 w-14 text-center">No</th>
                            <th class="px-3 sm:px-4 py-3.5 w-16 text-center">Poster</th>
                            <th class="px-4 sm:px-6 py-3.5">Nama Event &amp; Area</th>
                            <th class="px-4 sm:px-6 py-3.5 w-36">Jadwal</th>
                            <th class="px-4 sm:px-6 py-3.5 w-32">Kuota</th>
                            <th class="px-4 sm:px-6 py-3.5 w-24 text-center">Status</th>
                            <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($settings['events'] ?? [] as $index => $event)
                            <tr class="hover:bg-slate-50/60 transition group">
                                <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                    {{ sprintf('%02d', $index + 1) }}
                                </td>
                                <td class="px-3 sm:px-4 py-3 text-center">
                                    @if (!empty($event['poster_image']) && file_exists(public_path('assets/images/events/'.$event['poster_image'])))
                                        <img src="{{ asset('assets/images/events/'.$event['poster_image']) }}" alt="{{ $event['title'] }}" class="w-11 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs mx-auto">
                                    @else
                                        <div class="w-11 h-14 rounded-lg bg-[#E8EEF8] text-[#0A2373] flex flex-col items-center justify-center text-[10px] font-bold mx-auto border border-slate-200">
                                            <i class="fa-solid fa-image text-xs mb-0.5"></i>
                                            <span>Poster</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition">
                                        {{ $event['title'] }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-location-dot text-[10px]"></i>
                                        <span>{{ $event['area'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                    <i class="fa-regular fa-calendar text-[11px] text-slate-400 mr-1"></i>
                                    {{ $event['schedule'] ?? 'Akan diumumkan' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-xs font-medium text-slate-700 whitespace-nowrap">
                                    {{ $event['quota'] ?? '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-center text-xs font-medium {{ ($event['status'] ?? 'Buka') === 'Buka' ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $event['status'] ?? 'Buka' }}
                                </td>
                                <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <button 
                                            type="button" 
                                            onclick="openEditEventModal({{ json_encode($event) }})" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                            title="Edit event"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.join-us.events.destroy', $event['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event {{ addslashes($event['title']) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus event">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                                    Belum ada event sukarelawan. Klik tombol "+ Tambah Event Baru" di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form: Tambah Event Baru -->
        <div id="create-event-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Event Sukarelawan</h3>
                    </div>
                    <button type="button" onclick="closeModal('create-event-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form action="{{ route('admin.join-us.events.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="Contoh: Membaca Bersama Gerobak Angkasa" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Area / Lokasi <span class="text-rose-500">*</span></label>
                            <input type="text" name="area" required placeholder="Contoh: Tambun Selatan, Bekasi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                            <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                                <option value="Buka">Buka (Pendaftaran Aktif)</option>
                                <option value="Tutup">Tutup (Pendaftaran Ditutup)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jadwal Pelaksanaan</label>
                            <input type="text" name="schedule" placeholder="Contoh: Akan diumumkan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Batas Pendaftaran</label>
                            <input type="text" name="deadline" placeholder="Contoh: Belum ditentukan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kuota Relawan</label>
                            <input type="text" name="quota" placeholder="Contoh: 15 / 20 Relawan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Link Formulir (URL)</label>
                            <input type="url" name="form_link" placeholder="https://forms.gle/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <!-- Upload Poster Kegiatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Poster Kegiatan (Gambar)</label>
                        <input type="file" name="poster_image" accept="image/png,image/jpeg,image/webp,image/avif" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] transition cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format didukung: JPG, PNG, WebP hingga 5MB. Tampil di bagian visual kartu event.</span>
                    </div>

                    <!-- Upload Dokumen Juknis PDF -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Dokumen Juknis PDF <span class="text-slate-400 font-normal">(Tidak Wajib / Opsional)</span></label>
                        <input type="file" name="juknis_file" accept=".pdf,application/pdf" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format: PDF hingga 10MB. Menjadi tautan aktif pada tombol "Unduh Juknis" di halaman publik.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Kegiatan</label>
                        <textarea name="description" rows="3" placeholder="Tuliskan ringkasan kegiatan event..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeModal('create-event-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] transition cursor-pointer">
                            Simpan Event
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Form: Edit Event -->
        <div id="edit-event-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Event Sukarelawan</h3>
                    </div>
                    <button type="button" onclick="closeModal('edit-event-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form id="edit-event-form" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                        <input id="edit-event-title" type="text" name="title" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Area / Lokasi <span class="text-rose-500">*</span></label>
                            <input id="edit-event-area" type="text" name="area" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                            <select id="edit-event-status" name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                                <option value="Buka">Buka (Pendaftaran Aktif)</option>
                                <option value="Tutup">Tutup (Pendaftaran Ditutup)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jadwal Pelaksanaan</label>
                            <input id="edit-event-schedule" type="text" name="schedule" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Batas Pendaftaran</label>
                            <input id="edit-event-deadline" type="text" name="deadline" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kuota Relawan</label>
                            <input id="edit-event-quota" type="text" name="quota" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Link Formulir (URL)</label>
                            <input id="edit-event-link" type="url" name="form_link" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                        </div>
                    </div>

                    <!-- Upload Poster Kegiatan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Poster Kegiatan (Gambar)</label>
                        <div id="edit-event-poster-preview" class="hidden mb-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-3.5">
                            <img id="edit-event-poster-thumb" src="" class="w-12 h-16 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                            <div class="text-xs text-slate-600 truncate flex-1">
                                <span class="font-medium text-slate-900 block truncate" id="edit-event-poster-name"></span>
                                <span class="text-[11px] text-[#0A2373] font-semibold">✓ Poster saat ini terpasang</span>
                            </div>
                        </div>
                        <input type="file" name="poster_image" accept="image/png,image/jpeg,image/webp,image/avif" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] transition cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format: JPG, PNG, WebP (maks. 5MB). Biarkan kosong jika tidak mengubah poster.</span>
                    </div>

                    <!-- Upload Dokumen Juknis PDF -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Dokumen Juknis PDF <span class="text-slate-400 font-normal">(Tidak Wajib / Opsional)</span></label>
                        <div id="edit-event-juknis-preview" class="hidden mb-2 p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 text-slate-700 truncate">
                                <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i>
                                <span id="edit-event-juknis-name" class="font-medium truncate max-w-[200px]"></span>
                            </div>
                            <a id="edit-event-juknis-link" href="#" target="_blank" class="text-xs text-[#0A2373] font-semibold hover:underline shrink-0">
                                Unduh / Lihat File
                            </a>
                        </div>
                        <input type="file" name="juknis_file" accept=".pdf,application/pdf" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition cursor-pointer">
                        <span class="text-[11px] text-slate-400 mt-1 block">Format: PDF (maks. 10MB). Biarkan kosong jika tidak mengubah dokumen.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Kegiatan</label>
                        <textarea id="edit-event-desc" name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeModal('edit-event-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] transition cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @elseif ($edit === 'collaboration')
        <!-- ============================================================== -->
        <!-- VIEW: EDIT DETAIL AJUKAN KERJA SAMA KOLABORASI                 -->
        <!-- ============================================================== -->
        <a href="{{ route('admin.join-us.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-4">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Menu Pilihan</span>
        </a>

        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            Pengaturan Ajukan Kerja Sama Kolaborasi
        </h1>

        <div class="max-w-2xl bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8">
            <form action="{{ route('admin.join-us.update') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="section" value="collaboration">

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Program Kemitraan <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $settings['collaboration']['title'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Formulir Kerja Sama (Google Form / Link) <span class="text-rose-500">*</span></label>
                    <input type="url" name="form_link" required placeholder="https://docs.google.com/forms/d/... atau https://forms.gle/..." value="{{ old('form_link', $settings['collaboration']['form_link'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Narahubung / PIC Kerja Sama</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['collaboration']['contact_email'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">WhatsApp Narahubung</label>
                        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['collaboration']['contact_whatsapp'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Penerimaan Kolaborasi <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Buka" @selected(old('status', $settings['collaboration']['status'] ?? '') === 'Buka')>Buka (Menerima Pengajuan Kerja Sama)</option>
                        <option value="Tutup" @selected(old('status', $settings['collaboration']['status'] ?? '') === 'Tutup')>Tutup (Pengajuan Ditutup Sementara)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi / Ketentuan Kerja Sama</label>
                    <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">{{ old('description', $settings['collaboration']['description'] ?? '') }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.join-us.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('hidden');
        setTimeout(() => {
            m.classList.remove('opacity-0');
            m.firstElementChild?.classList.remove('scale-95');
            m.firstElementChild?.classList.add('scale-100');
        }, 10);
    }

    function closeModal(id) {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.add('opacity-0');
        m.firstElementChild?.classList.remove('scale-100');
        m.firstElementChild?.classList.add('scale-95');
        setTimeout(() => m.classList.add('hidden'), 200);
    }

    function openEditEventModal(event) {
        const form = document.getElementById('edit-event-form');
        form.action = '{{ url('/admin/join-us/events') }}/' + event.id;

        document.getElementById('edit-event-title').value = event.title || '';
        document.getElementById('edit-event-area').value = event.area || '';
        document.getElementById('edit-event-status').value = event.status || 'Buka';
        document.getElementById('edit-event-schedule').value = event.schedule || '';
        document.getElementById('edit-event-deadline').value = event.deadline || '';
        document.getElementById('edit-event-quota').value = event.quota || '';
        document.getElementById('edit-event-link').value = event.form_link || '';
        document.getElementById('edit-event-desc').value = event.description || '';

        // Poster image preview
        const posterPreview = document.getElementById('edit-event-poster-preview');
        const posterThumb = document.getElementById('edit-event-poster-thumb');
        const posterName = document.getElementById('edit-event-poster-name');
        if (event.poster_image) {
            posterThumb.src = '{{ asset('assets/images/events') }}/' + event.poster_image;
            posterName.textContent = event.poster_image;
            posterPreview.classList.remove('hidden');
        } else {
            posterPreview.classList.add('hidden');
        }

        // Juknis PDF preview
        const juknisPreview = document.getElementById('edit-event-juknis-preview');
        const juknisName = document.getElementById('edit-event-juknis-name');
        const juknisLink = document.getElementById('edit-event-juknis-link');
        if (event.juknis_file) {
            juknisName.textContent = event.juknis_file;
            juknisLink.href = '{{ asset('assets/documents/juknis') }}/' + event.juknis_file;
            juknisPreview.classList.remove('hidden');
        } else {
            juknisPreview.classList.add('hidden');
        }

        openModal('edit-event-modal');
    }
</script>
@endpush
