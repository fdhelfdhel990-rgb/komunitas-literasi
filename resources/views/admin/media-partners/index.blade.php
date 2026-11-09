@extends('layouts.admin')

@section('title', 'Kelola Media Partner')
@section('page_title', 'Media Partner')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">05</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Manajemen Konten</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Mitra Kolaborasi &amp; Media Partner
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola kemitraan dan logo media partner yang tayang pada section beranda web publik.
            </p>
        </div>
        <div>
            <button 
                type="button" 
                onclick="openModal('create-partner-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Mitra</span>
            </button>
        </div>
    </div>

    @php
        // 4 Media Partner nyata yang tampil di carousel beranda (home.blade.php)
        $partners = [
            [
                'id' => 1,
                'name' => 'Kementerian Pendidikan',
                'category' => 'Pemerintah / Institusi',
                'logo' => 'kementrian.jpg',
                'status' => 'Aktif',
                'description' => 'Dukungan program pembinaan dan pengembangan literasi anak di wilayah desa binaan.'
            ],
            [
                'id' => 2,
                'name' => 'Universitas Gadjah Mada',
                'category' => 'Perguruan Tinggi',
                'logo' => 'ugm.png',
                'status' => 'Aktif',
                'description' => 'Kolaborasi pengabdian masyarakat dan program pendampingan relawan literasi.'
            ],
            [
                'id' => 3,
                'name' => 'BRI Peduli',
                'category' => 'Mitra CSR / Korporasi',
                'logo' => 'bri-peduli.png',
                'status' => 'Aktif',
                'description' => 'Dukungan pengadaan buku bacaan dan operasional armada Gerobak Angkasa.'
            ],
            [
                'id' => 4,
                'name' => 'KMTEDI',
                'category' => 'Organisasi Mahasiswa',
                'logo' => 'kmtedi.jpg',
                'status' => 'Aktif',
                'description' => 'Kerja sama kegiatan kemahasiswaan dan relawan pengajar di ruang baca komunitas.'
            ],
        ];
    @endphp

    <!-- Partner Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-5 mb-8">
        @foreach ($partners as $partner)
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-16 rounded-xl bg-slate-50 border border-slate-200 p-2 flex items-center justify-center shrink-0">
                        <img 
                            src="{{ asset('assets/images/partners/'.$partner['logo']) }}" 
                            alt="{{ $partner['name'] }}" 
                            class="max-w-full max-h-full object-contain"
                        >
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 truncate">{{ $partner['name'] }}</h3>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $partner['status'] }}
                            </span>
                        </div>
                        <div class="text-xs text-[#0A2373] font-medium mb-1.5">{{ $partner['category'] }}</div>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $partner['description'] }}</p>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ url('/#media-partner') }}" target="_blank" class="text-xs font-semibold text-[#0A2373] hover:underline flex items-center gap-1">
                        <span>Lihat di Beranda</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="alert('Form edit mitra {{ $partner['name'] }}')" class="p-1.5 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" title="Edit mitra">
                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                        </button>
                        <button type="button" onclick="if(confirm('Hapus mitra ini?')) alert('Mitra berhasil dihapus')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" title="Hapus mitra">
                            <i class="fa-regular fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form: Tambah Mitra Baru -->
    <div id="create-partner-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-lg overflow-y-auto transform scale-95 transition-transform duration-200">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Mitra Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Logo mitra akan tampil di carousel beranda web user.</p>
                </div>
                <button type="button" onclick="closeModal('create-partner-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form onsubmit="handleFormSubmit(event, 'Mitra kerja sama baru berhasil ditambahkan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lembaga / Mitra <span class="text-rose-500">*</span></label>
                    <input type="text" required placeholder="Contoh: Universitas Gadjah Mada" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Mitra <span class="text-rose-500">*</span></label>
                    <select required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="">Pilih Kategori</option>
                        <option value="Pemerintah / Institusi">Pemerintah / Institusi</option>
                        <option value="Perguruan Tinggi">Perguruan Tinggi</option>
                        <option value="Mitra CSR / Korporasi">Mitra CSR / Korporasi</option>
                        <option value="Organisasi Kemasyarakatan">Organisasi Kemasyarakatan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Unggah Logo Mitra</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-[#0A2373] transition cursor-pointer bg-slate-50/50">
                        <i class="fa-regular fa-image text-2xl text-slate-400 mb-1"></i>
                        <div class="text-xs text-slate-600 font-medium">Klik untuk unggah logo mitra</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">PNG transparan direkomendasikan</div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Keterangan Kolaborasi</label>
                    <textarea rows="3" placeholder="Deskripsikan bentuk kerja sama..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] transition"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('create-partner-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] transition cursor-pointer">
                        Simpan Mitra
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
        closeModal('create-partner-modal');
    }
</script>
@endpush
