@extends('layouts.admin')

@section('title', 'Kelola Join Us & Relawan')
@section('page_title', 'Join Us')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">04</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Manajemen Konten</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Kegiatan Relawan &amp; Join Us
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola kartu kegiatan relawan, peran penugasan, dan status pendaftaran di web publik.
            </p>
        </div>
        <div>
            <button 
                type="button" 
                onclick="openModal('create-event-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Event</span>
            </button>
        </div>
    </div>

    @php
        // Event yang selaras dengan halaman publik (join-us/events.blade.php & event-detail.blade.php)
        $events = [
            [
                'id' => 1,
                'title' => 'Membaca Bersama Gerobak Angkasa',
                'category' => 'Volunteer Event',
                'schedule' => 'Akan diumumkan',
                'area' => 'Tambun Selatan, Kabupaten Bekasi',
                'role' => 'Mendampingi anak memilih buku, membaca bersama, dan mendukung aktivitas literasi.',
                'quota' => '15 / 20 Relawan',
                'status' => 'Buka',
                'route' => route('join-us.event-detail')
            ],
            [
                'id' => 2,
                'title' => 'Pembinaan Ruang Baca & Perpustakaan Desa',
                'category' => 'Volunteer Reguler',
                'schedule' => 'Setiap Akhir Pekan',
                'area' => '2 Lokasi Desa Binaan',
                'role' => 'Membantu katalogisasi buku, mengajar membaca, dan mendampingi kelompok belajar anak.',
                'quota' => '10 / 12 Relawan',
                'status' => 'Buka',
                'route' => route('join-us.events')
            ]
        ];
    @endphp

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs sm:text-sm font-semibold text-slate-800">
                Total Kegiatan Relawan: <span class="text-[#0A2373] font-bold">{{ count($events) }}</span> agenda
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-14 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Nama Kegiatan &amp; Lokasi</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Jadwal</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Kuota Terisi</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach ($events as $index => $event)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <td class="px-4 sm:px-6 py-4 text-center font-mono text-xs text-slate-400">
                                {{ sprintf('%02d', $index + 1) }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition">
                                    {{ $event['title'] }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-[11px] text-slate-400"></i>
                                    <span>{{ $event['area'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                <i class="fa-regular fa-calendar text-[11px] text-slate-400 mr-1"></i>
                                {{ $event['schedule'] }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-xs font-medium text-slate-700 whitespace-nowrap">
                                <i class="fa-solid fa-users text-[11px] text-slate-400 mr-1"></i>
                                {{ $event['quota'] }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $event['status'] }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a 
                                        href="{{ $event['route'] }}" 
                                        target="_blank" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition" 
                                        title="Pratinjau di web publik"
                                    >
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="alert('Edit kegiatan {{ $event['title'] }}')" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                        title="Edit event"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form: Tambah Event Baru -->
    <div id="create-event-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg overflow-y-auto transform scale-95 transition-transform duration-200">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Kegiatan Relawan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Event akan tampil pada halaman Join Us web publik.</p>
                </div>
                <button type="button" onclick="closeModal('create-event-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form onsubmit="handleFormSubmit(event, 'Kegiatan relawan baru berhasil disimpan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kegiatan <span class="text-rose-500">*</span></label>
                    <input type="text" required placeholder="Contoh: Membaca Bersama Gerobak Angkasa" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Area / Lokasi <span class="text-rose-500">*</span></label>
                        <input type="text" required placeholder="Contoh: Tambun Selatan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jadwal Pelaksanaan</label>
                        <input type="text" placeholder="Contoh: 25 Oktober 2026" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Peran Relawan <span class="text-rose-500">*</span></label>
                    <textarea rows="3" required placeholder="Deskripsikan tugas dan tanggung jawab relawan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition"></textarea>
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

    function handleFormSubmit(e, msg) {
        e.preventDefault();
        alert(msg);
        closeModal('create-event-modal');
    }
</script>
@endpush
