@extends('layouts.admin')

@section('title', 'Edit Buku - ' . $book->title)
@section('page_title', 'Digital Library')

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
        <a href="{{ route('admin.library.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0A2373] hover:underline mb-3">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Katalog Buku</span>
        </a>

        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Edit Buku
            </h1>
        </div>
    </div>

    <!-- Main Edit Form -->
    <form action="{{ route('admin.library.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="remove_cover" id="remove_cover" value="0">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Book Details Form (2 Cols on lg) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-5">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Informasi Utama Buku
                    </h2>

                    <!-- Judul Buku -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            value="{{ old('title', $book->title) }}" 
                            placeholder="Contoh: Negeri 5 Menara"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                        >
                    </div>

                    <!-- 2 Kolom: Penulis & Penerbit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Penulis <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="author_name" 
                                list="authors-list"
                                required 
                                value="{{ old('author_name', $book->author->name ?? '') }}" 
                                placeholder="Contoh: Ahmad Fuadi"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                            >
                            <datalist id="authors-list">
                                @foreach ($authors as $a)
                                    <option value="{{ $a }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Penerbit <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="publisher_name" 
                                list="publishers-list"
                                required 
                                value="{{ old('publisher_name', $book->publisher->name ?? '') }}" 
                                placeholder="Contoh: Gramedia"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                            >
                            <datalist id="publishers-list">
                                @foreach ($publishers as $p)
                                    <option value="{{ $p }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <!-- Genre Buku -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Genre Buku <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="genre_name" 
                            list="genres-list"
                            required 
                            value="{{ old('genre_name', $book->genre->name ?? '') }}" 
                            placeholder="Pilih atau ketik genre buku..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition"
                        >
                        <datalist id="genres-list">
                            @foreach ($genres as $g)
                                <option value="{{ $g }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Sinopsis / Deskripsi Buku -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Ringkasan / Sinopsis Buku</label>
                        <textarea 
                            name="description" 
                            rows="9" 
                            placeholder="Tuliskan sinopsis buku, pengantar alur cerita, atau ringkasan topik bacaan..." 
                            class="w-full p-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition resize-y leading-relaxed"
                        >{{ old('description', $book->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Cover Image & Actions (1 Col on lg) -->
            <div class="space-y-6">
                <!-- Sampul Buku Card -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 sm:p-6 space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
                        Sampul Buku
                    </h2>

                    <!-- Preview Container -->
                    <div class="border border-slate-200 rounded-xl p-3.5 bg-slate-50/50 flex flex-col items-center text-center">
                        <div id="cover-preview-box" class="{{ $book->cover_path ? '' : 'hidden' }} space-y-3 w-full">
                            <div class="w-36 h-48 mx-auto rounded-lg overflow-hidden border border-slate-200 shadow-xs bg-white flex items-center justify-center">
                                <img 
                                    id="cover-thumb" 
                                    src="{{ $book->cover_path ? asset('assets/images/books/' . $book->cover_path) : '' }}" 
                                    alt="Sampul buku" 
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1">
                                    <i class="fa-solid fa-check text-[10px]"></i> Sampul Terunggah
                                </span>
                                <div id="cover-filename" class="text-xs text-slate-500 font-mono truncate max-w-xs mx-auto">
                                    {{ $book->cover_path }}
                                </div>
                            </div>
                        </div>

                        <!-- Placeholder when no image -->
                        <div id="cover-placeholder" class="{{ $book->cover_path ? 'hidden' : '' }} py-8 cursor-pointer w-full text-center hover:bg-slate-100/50 rounded-lg transition" onclick="document.getElementById('cover_image_input').click()">
                            <i class="fa-regular fa-image text-3xl text-slate-300 mb-2"></i>
                            <div class="text-xs font-semibold text-slate-700">Pilih Sampul Buku</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WebP maks. 3MB</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200/80 w-full justify-center">
                            <input 
                                type="file" 
                                id="cover_image_input" 
                                name="cover_image" 
                                accept="image/jpeg,image/png,image/webp" 
                                class="hidden" 
                                onchange="handleCoverSelected(this)"
                            >
                            <button 
                                type="button" 
                                onclick="document.getElementById('cover_image_input').click()" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition cursor-pointer shadow-2xs"
                            >
                                Ganti Sampul
                            </button>
                            <button 
                                type="button" 
                                onclick="removeCover()" 
                                class="px-3 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-xs font-semibold text-rose-600 transition cursor-pointer shadow-2xs"
                            >
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div class="text-[11px] text-slate-400 leading-normal">
                        Format yang didukung: JPG, PNG, WebP hingga 3MB. Disarankan rasio sampul 3:4 agar proporsional di katalog.
                    </div>
                </div>

                <!-- Action Card -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-5 space-y-3">
                    <button 
                        type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a 
                        href="{{ route('admin.library.index') }}" 
                        class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition text-center block"
                    >
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function handleCoverSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-thumb').src = e.target.result;
                document.getElementById('cover-filename').textContent = file.name;
                document.getElementById('cover-preview-box').classList.remove('hidden');
                document.getElementById('cover-placeholder').classList.add('hidden');
                document.getElementById('remove_cover').value = '0';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeCover() {
        document.getElementById('cover_image_input').value = '';
        document.getElementById('remove_cover').value = '1';
        document.getElementById('cover-thumb').src = '';
        document.getElementById('cover-filename').textContent = '';
        document.getElementById('cover-preview-box').classList.add('hidden');
        document.getElementById('cover-placeholder').classList.remove('hidden');
    }
</script>
@endpush
