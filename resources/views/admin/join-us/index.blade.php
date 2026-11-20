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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Ikut Volunteer -->
            <article class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:shadow-md transition flex flex-col items-center text-center">
                <i class="fa-solid fa-people-group text-3xl sm:text-4xl text-[#0A2373] mb-4" aria-hidden="true"></i>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 font-['Poppins']">
                    Ikut Volunteer
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed flex-1">
                    {{ $settings['volunteer']['description'] ?? 'Bergabung sebagai pendamping membaca dan penggerak kegiatan komunitas.' }}
                </p>
                <a href="{{ route('admin.join-us.index', ['edit' => 'volunteer']) }}" class="w-full py-2.5 px-4 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs">
                    Kelola Formulir
                </a>
            </article>

            <!-- Card 2: Volunteer Event -->
            <article class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:shadow-md transition flex flex-col items-center text-center">
                <i class="fa-regular fa-calendar-check text-3xl sm:text-4xl text-[#0A2373] mb-4" aria-hidden="true"></i>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 font-['Poppins']">
                    Ikut Volunteer Event
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed flex-1">
                    Terlibat pada agenda literasi tertentu sesuai waktu dan kemampuan Anda (tambah, edit, dan hapus event).
                </p>
                <a href="{{ route('admin.join-us.index', ['edit' => 'event']) }}" class="w-full py-2.5 px-4 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs">
                    Kelola Daftar Event
                </a>
            </article>

            <!-- Card 3: Ajukan Kerja Sama Kolaborasi -->
            <article class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:shadow-md transition flex flex-col items-center text-center">
                <i class="fa-solid fa-handshake text-3xl sm:text-4xl text-[#0A2373] mb-4" aria-hidden="true"></i>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 font-['Poppins']">
                    Ajukan Kerja Sama Kolaborasi
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed flex-1">
                    {{ $settings['collaboration']['description'] ?? 'Buka peluang kemitraan program, publikasi, atau dukungan operasional.' }}
                </p>
                <a href="{{ route('admin.join-us.index', ['edit' => 'collaboration']) }}" class="w-full py-2.5 px-4 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs">
                    Kelola Kerja Sama
                </a>
            </article>
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Formulir Pendaftaran<span class="text-rose-500">*</span></label>
                    <input type="url" name="form_link" required placeholder="https://docs.google.com/forms/d/... atau https://forms.gle/..." value="{{ old('form_link', $settings['volunteer']['form_link'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Buka" @selected(old('status', $settings['volunteer']['status'] ?? '') === 'Buka')>Buka</option>
                        <option value="Tutup" @selected(old('status', $settings['volunteer']['status'] ?? '') === 'Tutup')>Berakhir</option>
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

    @elseif ($edit === 'collaboration')
        <!-- ============================================================== -->
        <!-- VIEW: EDIT LINK KERJA SAMA KOLABORASI                           -->
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
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Program <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title', $settings['collaboration']['title'] ?? 'Ajukan Kerja Sama Kolaborasi') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tautan Formulir Kerja Sama <span class="text-rose-500">*</span></label>
                    <input type="url" name="form_link" required placeholder="https://docs.google.com/forms/d/... atau https://forms.gle/..." value="{{ old('form_link', $settings['collaboration']['form_link'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Kontak Kolaborasi</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['collaboration']['contact_email'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">WhatsApp Kontak Kolaborasi</label>
                        <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['collaboration']['contact_whatsapp'] ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Buka" @selected(old('status', $settings['collaboration']['status'] ?? '') === 'Buka')>Buka (Penerimaan Aktif)</option>
                        <option value="Tutup" @selected(old('status', $settings['collaboration']['status'] ?? '') === 'Tutup')>Tutup (Penerimaan Ditutup)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
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
                <a 
                    href="{{ route('admin.join-us.events.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Event Baru</span>
                </a>
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
                                        <a 
                                            href="{{ route('admin.join-us.events.edit', $event['id']) }}" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                            title="Edit event"
                                        >
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>
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
    @endif
@endsection
