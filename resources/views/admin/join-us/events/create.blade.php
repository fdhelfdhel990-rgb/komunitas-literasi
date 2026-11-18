@extends('layouts.admin')

@section('title', 'Tambah Event Sukarelawan')
@section('page_title', 'Join Us')

@section('content')
    <!-- Flash & Error Messages -->
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

    <!-- Back Navigation & Header -->
    <div class="mb-6">
        <a href="{{ route('admin.join-us.index', ['edit' => 'event']) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Kelola Event</span>
        </a>

        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Tambah Event Sukarelawan
            </h1>
        </div>
    </div>

    <!-- Main Create Form -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 max-w-4xl">
        <form action="{{ route('admin.join-us.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Nama Event -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kegiatan Event <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    name="title" 
                    required 
                    value="{{ old('title') }}" 
                    placeholder="Contoh: Festival Membaca Anak Desa"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- 2 Kolom: Area / Lokasi & Status Pendaftaran -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi Kegiatan <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="area" 
                        required 
                        value="{{ old('area', 'Tambun Selatan') }}" 
                        placeholder="Contoh: Tambun Selatan, Kabupaten Bekasi"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pendaftaran <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Buka" @selected(old('status', 'Buka') === 'Buka')>Pendaftaran dibuka</option>
                        <option value="Tutup" @selected(old('status') === 'Tutup')>Pendaftaran Berakhir</option>
                    </select>
                </div>
            </div>

            <!-- 3 Kolom: Jadwal, Deadline, Kuota -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Pelaksanaan</label>
                    <input 
                        type="text" 
                        name="schedule" 
                        value="{{ old('schedule') }}" 
                        placeholder="Contoh: 30 November 2026"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Batas Pendaftaran</label>
                    <input 
                        type="text" 
                        name="deadline" 
                        value="{{ old('deadline') }}" 
                        placeholder="Contoh: 25 November 2026"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                    >
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kuota Relawan</label>
                    <input 
                        type="text" 
                        name="quota" 
                        value="{{ old('quota', '20 Relawan') }}" 
                        placeholder="Contoh: 20 Relawan"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                    >
                </div>
            </div>

            <!-- Link Formulir Pendaftaran -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Link Formulir Pendaftaran</label>
                <input 
                    type="url" 
                    name="form_link" 
                    value="{{ old('form_link') }}" 
                    placeholder="https://forms.google.com/..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- Deskripsi Singkat Kegiatan -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Singkat Kegiatan</label>
                <textarea 
                    name="description" 
                    rows="4" 
                    placeholder="Jelaskan peran sukarelawan, agenda kegiatan, dan manfaat bagi peserta..."
                    class="w-full p-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition resize-y leading-relaxed"
                >{{ old('description') }}</textarea>
            </div>

            <!-- Upload Poster Kegiatan -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Upload Poster Kegiatan</label>
                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                    <div id="poster-preview-box" class="hidden flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <img 
                                id="poster-thumb" 
                                src="" 
                                alt="Poster Preview" 
                                class="w-16 h-20 rounded-lg object-cover border border-slate-200 shrink-0 bg-slate-50"
                            >
                            <div class="min-w-0 space-y-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[10px]"></i> Poster Terpilih
                                </span>
                                <div id="poster-filename" class="text-xs text-slate-600 font-mono truncate max-w-sm"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('poster_input').click()" class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 transition cursor-pointer shadow-2xs">
                                Ganti Poster
                            </button>
                            <button type="button" onclick="removePoster()" class="px-3.5 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-xs font-medium text-rose-600 transition cursor-pointer shadow-2xs">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div id="poster-placeholder" class="text-center py-6 cursor-pointer hover:bg-slate-50/70 rounded-lg transition border border-dashed border-slate-200" onclick="document.getElementById('poster_input').click()">
                        <i class="fa-regular fa-image text-3xl text-slate-300 mb-2"></i>
                        <div class="text-xs font-medium text-slate-700">Pilih Poster Kegiatan</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WebP hingga 5MB. Rasio vertikal disarankan.</div>
                    </div>

                    <input 
                        type="file" 
                        id="poster_input" 
                        name="poster_image" 
                        accept="image/png,image/jpeg,image/jpg,image/webp" 
                        class="hidden" 
                        onchange="handlePosterSelected(this)"
                    >
                </div>
            </div>

            <!-- Upload Dokumen Juknis PDF -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Guide Book</label>
                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                    <input 
                        type="file" 
                        name="juknis_file" 
                        accept="application/pdf"
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] cursor-pointer"
                    >
                    <span class="text-[11px] text-slate-400 mt-1.5 block">Format: PDF hingga 10MB (opsional). Menjadi tautan aktif pada tombol "Unduh Juknis" di halaman publik.</span>
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.join-us.index', ['edit' => 'event']) }}" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs flex items-center gap-2"
                >
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Simpan Event</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function handlePosterSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('poster-thumb').src = e.target.result;
                document.getElementById('poster-filename').textContent = file.name;
                document.getElementById('poster-preview-box').classList.remove('hidden');
                document.getElementById('poster-preview-box').classList.add('flex');
                document.getElementById('poster-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removePoster() {
        document.getElementById('poster_input').value = '';
        document.getElementById('poster-thumb').src = '';
        document.getElementById('poster-filename').textContent = '';
        document.getElementById('poster-preview-box').classList.add('hidden');
        document.getElementById('poster-preview-box').classList.remove('flex');
        document.getElementById('poster-placeholder').classList.remove('hidden');
    }
</script>
@endpush
