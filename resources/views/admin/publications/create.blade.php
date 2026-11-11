@extends('layouts.admin')

@section('title', 'Tambah Publikasi')
@section('page_title', 'Publication')

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

    <!-- Back Navigation & Header -->
    <div class="mb-6">
        <a href="{{ route('admin.publications.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Kelola Publikasi</span>
        </a>

        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Tambah Publikasi
            </h1>
        </div>
    </div>

    <!-- Main Full-Page Create Form -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 sm:p-8 max-w-4xl">
        <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="syncContent()">
            @csrf

            <!-- Judul Berita -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Berita <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    name="title" 
                    required 
                    value="{{ old('title') }}" 
                    placeholder="Masukkan judul berita atau kajian..." 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- 2 Kolom: Kategori Publikasi & Tanggal Publikasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Publikasi <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Publikasi <span class="text-rose-500">*</span></label>
                    <input 
                        type="date" 
                        name="published_at" 
                        required 
                        value="{{ old('published_at', now()->format('Y-m-d')) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                    >
                </div>
            </div>

            <!-- Foto Utama Berita -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Foto Utama Berita</label>
                
                <!-- Box Preview Container -->
                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                    <!-- State: Ketika Foto Dipilih -->
                    <div id="photo-preview-box" class="hidden flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <img 
                                id="photo-thumb" 
                                src="" 
                                alt="Thumbnail" 
                                class="w-24 h-16 rounded-lg object-cover border border-slate-200 shrink-0 bg-slate-50"
                            >
                            <div class="min-w-0 space-y-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check text-[10px]"></i> Foto Terpilih
                                </span>
                                <div id="photo-filename" class="text-xs text-slate-600 font-mono truncate max-w-sm sm:max-w-md">
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                            <button type="button" onclick="document.getElementById('featured_image_input').click()" class="px-3.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 transition cursor-pointer shadow-2xs">
                                Ganti Foto
                            </button>
                            <button type="button" onclick="removePhoto()" class="px-3.5 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-xs font-medium text-rose-600 transition cursor-pointer shadow-2xs">
                                Hapus
                            </button>
                        </div>
                    </div>

                    <!-- State: Ketika Foto Belum Ada -->
                    <div id="photo-placeholder" class="text-center py-6 cursor-pointer hover:bg-slate-50/70 rounded-lg transition border border-dashed border-slate-200" onclick="document.getElementById('featured_image_input').click()">
                        <i class="fa-regular fa-image text-3xl text-slate-300 mb-2"></i>
                        <div class="text-xs font-medium text-slate-700">Pilih Foto Utama Berita</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Klik untuk memilih file dari komputer Anda</div>
                    </div>

                    <!-- Real Hidden File Input -->
                    <input 
                        type="file" 
                        id="featured_image_input" 
                        name="featured_image" 
                        accept="image/jpeg,image/png,image/webp,image/avif" 
                        class="hidden" 
                        onchange="handlePhotoSelected(this)"
                    >
                </div>
                <div class="text-[11px] text-slate-400 mt-1.5">
                    Format yang didukung: JPG, PNG, WebP, AVIF hingga 10MB.
                </div>
            </div>

            <!-- Isi Berita dengan Toolbar WYSIWYG Real-time -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Isi Berita <span class="text-rose-500">*</span></label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#0A2373] focus-within:ring-1 focus-within:ring-[#0A2373]/20 transition">
                    <!-- Formatting Toolbar (Instant visual preview) -->
                    <div class="bg-slate-50/90 border-b border-slate-200 px-3 py-2 flex items-center gap-1 flex-wrap text-slate-600">
                        <button type="button" onmousedown="event.preventDefault(); execCmd('bold');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 font-bold text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Tebal (Ctrl+B)">
                            B
                        </button>
                        <button type="button" onmousedown="event.preventDefault(); execCmd('italic');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 italic text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition font-serif" title="Miring (Ctrl+I)">
                            I
                        </button>
                        <button type="button" onmousedown="event.preventDefault(); execCmd('underline');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 underline text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Garis Bawah (Ctrl+U)">
                            U
                        </button>
                        <div class="h-4 w-px bg-slate-300 mx-1"></div>
                        <button type="button" onmousedown="event.preventDefault(); execCmd('insertUnorderedList');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Daftar Butir">
                            <i class="fa-solid fa-list-ul"></i>
                        </button>
                        <button type="button" onmousedown="event.preventDefault(); execCmd('insertOrderedList');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Daftar Nomor">
                            <i class="fa-solid fa-list-ol"></i>
                        </button>
                        <button type="button" onmousedown="event.preventDefault(); execCmd('formatBlock', 'blockquote');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Kutipan">
                            <i class="fa-solid fa-quote-left"></i>
                        </button>
                        <button type="button" onmousedown="event.preventDefault(); promptLink();" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Sisipkan Tautan">
                            <i class="fa-solid fa-link"></i>
                        </button>
                    </div>

                    <!-- Visual Editable Area (WYSIWYG: Bold/Italic/Underline langsung terlihat) -->
                    <div 
                        id="wysiwyg-editor" 
                        contenteditable="true" 
                        class="w-full p-4 min-h-[300px] max-h-[600px] overflow-y-auto text-xs sm:text-sm text-slate-800 bg-white focus:outline-none leading-relaxed prose-sm"
                        oninput="syncContent()"
                    >{!! old('content') !!}</div>
                    
                    <!-- Hidden input to submit with form -->
                    <textarea id="hidden-content" name="content" class="hidden">{!! old('content') !!}</textarea>
                </div>
            </div>

            <!-- Status Publikasi -->
            <div class="max-w-xs">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Publikasi <span class="text-rose-500">*</span></label>
                <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                    <option value="published" @selected(old('status', 'published') === 'published')>Tayang (Publik)</option>
                    <option value="draft" @selected(old('status') === 'draft')>Draf (Disimpan)</option>
                </select>
            </div>

            <!-- Form Action Buttons -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.publications.index') }}" 
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs flex items-center gap-2"
                >
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Simpan Publikasi</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<style>
    #wysiwyg-editor blockquote {
        border-left: 3px solid #0A2373;
        padding-left: 12px;
        margin: 8px 0;
        color: #475569;
        font-style: italic;
    }
    #wysiwyg-editor ul {
        list-style-type: disc;
        margin-left: 20px;
        margin-bottom: 8px;
    }
    #wysiwyg-editor ol {
        list-style-type: decimal;
        margin-left: 20px;
        margin-bottom: 8px;
    }
    #wysiwyg-editor a {
        color: #0A2373;
        text-decoration: underline;
    }
    #wysiwyg-editor p {
        margin-bottom: 8px;
    }
</style>

<script>
    function handlePhotoSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-thumb').src = e.target.result;
                document.getElementById('photo-filename').textContent = file.name;
                document.getElementById('photo-preview-box').classList.remove('hidden');
                document.getElementById('photo-preview-box').classList.add('flex');
                document.getElementById('photo-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removePhoto() {
        document.getElementById('featured_image_input').value = '';
        document.getElementById('photo-thumb').src = '';
        document.getElementById('photo-filename').textContent = '';
        document.getElementById('photo-preview-box').classList.add('hidden');
        document.getElementById('photo-preview-box').classList.remove('flex');
        document.getElementById('photo-placeholder').classList.remove('hidden');
    }

    // Synchronize contenteditable to hidden textarea
    function syncContent() {
        const editor = document.getElementById('wysiwyg-editor');
        const hidden = document.getElementById('hidden-content');
        if (editor && hidden) {
            hidden.value = editor.innerHTML;
        }
    }

    // Real-time WYSIWYG command execution
    function execCmd(command, value = null) {
        document.getElementById('wysiwyg-editor').focus();
        document.execCommand(command, false, value);
        syncContent();
    }

    function promptLink() {
        const url = prompt('Masukkan tautan URL (misal: https://example.com):', 'https://');
        if (url) {
            execCmd('createLink', url);
        }
    }

    // Keyboard Shortcuts (Ctrl+B, Ctrl+I, Ctrl+U)
    document.addEventListener('DOMContentLoaded', function () {
        const editor = document.getElementById('wysiwyg-editor');
        if (!editor) return;

        editor.addEventListener('keydown', function (e) {
            if (e.ctrlKey || e.metaKey) {
                const key = e.key.toLowerCase();
                if (key === 'b') {
                    e.preventDefault();
                    execCmd('bold');
                } else if (key === 'i') {
                    e.preventDefault();
                    execCmd('italic');
                } else if (key === 'u') {
                    e.preventDefault();
                    execCmd('underline');
                }
            }
        });

        syncContent();
    });
</script>
@endpush
