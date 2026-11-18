@extends('layouts.admin')

@section('title', 'Edit Media Partner - ' . ($partner['name'] ?? ''))
@section('page_title', 'Partnership')

@section('content')
    <!-- Flash Messages & Errors -->
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
        <a href="{{ route('admin.media-partners.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Media Partner</span>
        </a>

        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Edit Logo Media Partner
            </h1>
        </div>
    </div>

    <!-- Main Edit Form -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 max-w-2xl">
        <form action="{{ route('admin.media-partners.update', $partner['id']) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Mitra -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Instansi<span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    required 
                    value="{{ old('name', $partner['name'] ?? '') }}" 
                    placeholder="Contoh: PT Kereta Api Indonesia (Persero)" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- Upload File Logo -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">File Logo Mitra</label>
                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                    @php
                        $hasLogo = !empty($partner['logo']) && file_exists(public_path('assets/images/partners/'.$partner['logo']));
                    @endphp
                    <div id="logo-preview-box" class="{{ $hasLogo ? 'flex' : 'hidden' }} flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-20 h-14 rounded-lg bg-slate-50 border border-slate-200 p-2 flex items-center justify-center shrink-0">
                                <img 
                                    id="logo-thumb" 
                                    src="{{ $hasLogo ? asset('assets/images/partners/'.$partner['logo']) : '' }}" 
                                    alt="Logo Preview" 
                                    class="max-h-full max-w-full object-contain"
                                >
                            </div>
                            <div class="min-w-0 space-y-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[10px]"></i> Logo Terpasang
                                </span>
                                <div id="logo-filename" class="text-xs text-slate-600 font-mono truncate max-w-xs">
                                    {{ $partner['logo'] ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('logo_image_input').click()" class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 transition cursor-pointer shadow-2xs">
                                Ganti Logo
                            </button>
                            <button type="button" onclick="removeLogo()" class="px-3.5 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-xs font-medium text-rose-600 transition cursor-pointer shadow-2xs">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div id="logo-placeholder" class="{{ $hasLogo ? 'hidden' : '' }} text-center py-6 cursor-pointer hover:bg-slate-50/70 rounded-lg transition border border-dashed border-slate-200" onclick="document.getElementById('logo_image_input').click()">
                        <i class="fa-regular fa-image text-3xl text-slate-300 mb-2"></i>
                        <div class="text-xs font-medium text-slate-700">Pilih File Logo Mitra Baru</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Format: SVG, PNG, WebP, JPG hingga 3MB. Disarankan background transparan.</div>
                    </div>

                    <input 
                        type="file" 
                        id="logo_image_input" 
                        name="logo_image" 
                        accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp" 
                        class="hidden" 
                        onchange="handleLogoSelected(this)"
                    >
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.media-partners.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs flex items-center gap-2"
                >
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function handleLogoSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-thumb').src = e.target.result;
                document.getElementById('logo-filename').textContent = file.name;
                document.getElementById('logo-preview-box').classList.remove('hidden');
                document.getElementById('logo-preview-box').classList.add('flex');
                document.getElementById('logo-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeLogo() {
        const input = document.getElementById('logo_image_input');
        input.value = '';
        document.getElementById('logo-thumb').src = '';
        document.getElementById('logo-filename').textContent = '';
        document.getElementById('logo-preview-box').classList.add('hidden');
        document.getElementById('logo-preview-box').classList.remove('flex');
        document.getElementById('logo-placeholder').classList.remove('hidden');
    }
</script>
@endpush
