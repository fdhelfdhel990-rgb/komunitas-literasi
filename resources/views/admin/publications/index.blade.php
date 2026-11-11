@extends('layouts.admin')

@section('title', 'Kelola Publikasi')
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

    <!-- Page Header & Action (Tanpa badge nomor/kategori page) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Publikasi &amp; Kabar Komunitas
            </h1>
        </div>
        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('admin.publications.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer whitespace-nowrap"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Publikasi</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.publications.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input 
                    type="search" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari berdasarkan judul, cuplikan berita, atau kategori..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">
                <select 
                    name="category" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Status</option>
                    <option value="Tayang" @selected(request('status') === 'Tayang' || request('status') === 'published')>Tayang</option>
                    <option value="Draf" @selected(request('status') === 'Draf' || request('status') === 'draft')>Draf</option>
                </select>

                @if(request('q') || request('category') || request('status'))
                    <a href="{{ route('admin.publications.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5" title="Reset filter">
                        <i class="fa-solid fa-xmark"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs sm:text-sm font-semibold text-slate-800">
                Total Publikasi: <span class="text-[#0A2373] font-bold">{{ $articles->total() }}</span> artikel
                @if(request('q') || request('category') || request('status'))
                    <span class="text-xs text-slate-400 font-normal">(difilter)</span>
                @endif
            </div>
            <div class="text-xs text-slate-500 font-['Poppins']">
                Halaman {{ $articles->currentPage() }} dari {{ max(1, $articles->lastPage()) }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-14 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Artikel</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Kategori</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($articles as $index => $article)
                        @php
                            $rowNumber = ($articles->currentPage() - 1) * $articles->perPage() + $loop->iteration;
                            $articleImage = $article->featured_image_path ? asset('assets/images/publications/'.$article->featured_image_path) : 'https://placehold.co/600x400?text=No+Image';
                            $authorName = $article->user->name ?? 'Admin Literasi';
                            $formattedDate = $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') : '-';
                            $isPublished = in_array($article->status, ['published', 'Tayang']);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- No -->
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', $rowNumber) }}
                            </td>

                            <!-- Article Info -->
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-start gap-3.5 max-w-lg">
                                    <img 
                                        src="{{ $articleImage }}" 
                                        alt="Sampul artikel" 
                                        class="w-14 h-14 rounded-lg object-cover border border-slate-200 shrink-0 bg-slate-100"
                                        onerror="this.src='https://placehold.co/600x400?text=No+Image'"
                                    >
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition line-clamp-1">
                                            {{ $article->title }}
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                            {{ $article->excerpt ?: Str::limit(strip_tags($article->content), 80) }}
                                        </p>
                                        <div class="text-[11px] text-slate-400 mt-1">
                                            Penulis: <span class="font-medium text-slate-600">{{ $authorName }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#E8EEF8] text-[#0A2373]">
                                    {{ $article->category }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 sm:px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $formattedDate }}
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 sm:px-6 py-4 text-center">
                                @if($isPublished)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tayang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Draf
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <!-- Halaman Detail Khusus Pengeditan -->
                                    <a 
                                        href="{{ route('admin.publications.edit', $article->id) }}" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition" 
                                        title="Edit publikasi (Halaman Detail)"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.publications.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus publikasi &quot;{{ addslashes($article->title) }}&quot;?')">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" 
                                            title="Hapus artikel"
                                        >
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs sm:text-sm">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-file-circle-question text-lg"></i>
                                </div>
                                Tidak ada data publikasi yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Interaktif -->
        @if ($articles->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $articles->firstItem() ?? 0 }} sampai {{ $articles->lastItem() ?? 0 }} dari {{ $articles->total() }} entri
                </div>
                <div class="flex items-center gap-1 self-end sm:self-auto">
                    <!-- Tombol Prev -->
                    @if ($articles->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            &lt;
                        </span>
                    @else
                        <a 
                            href="{{ $articles->previousPageUrl() }}" 
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            title="Halaman sebelumnya"
                        >
                            &lt;
                        </a>
                    @endif

                    <!-- Nomor Halaman -->
                    @for ($p = 1; $p <= $articles->lastPage(); $p++)
                        @if ($p == $articles->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-[#0A2373] text-white font-['Poppins'] font-bold text-xs">
                                {{ $p }}
                            </span>
                        @else
                            <a 
                                href="{{ $articles->url($p) }}" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-['Poppins'] font-bold text-xs"
                            >
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    <!-- Tombol Next -->
                    @if ($articles->hasMorePages())
                        <a 
                            href="{{ $articles->nextPageUrl() }}" 
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            title="Halaman berikutnya"
                        >
                            &gt;
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            &gt;
                        </span>
                    @endif
                </div>
            </div>
        @else
            <div class="p-4 sm:p-5 border-t border-slate-100 text-xs text-slate-500">
                Menampilkan seluruh {{ $articles->total() }} entri publikasi
            </div>
        @endif
    </div>

    <!-- ============================================================== -->
    <!-- MODAL FORM: TAMBAH PUBLIKASI (DENGAN REALTIME WYSIWYG EDITOR)  -->
    <!-- ============================================================== -->
    <div id="create-publication-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-2xl max-h-[92vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Publikasi Baru</h3>
                </div>
                <button type="button" onclick="closeModal('create-publication-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4" onsubmit="syncCreateContent()">
                @csrf

                <!-- Judul Berita -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Berita <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="title" 
                        required 
                        placeholder="Masukkan judul publikasi atau berita..." 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                    >
                </div>

                <!-- 2 Kolom: Kategori Publikasi & Tanggal Publikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori Publikasi <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                            <option value="">Pilih Kategori</option>
                            <option value="Kajian">Kajian</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Sosial">Sosial</option>
                            <option value="Berita Komunitas">Berita Komunitas</option>
                            <option value="Opini & Literasi">Opini &amp; Literasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Publikasi <span class="text-rose-500">*</span></label>
                        <input 
                            type="date" 
                            name="published_at" 
                            required 
                            value="{{ date('Y-m-d') }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                        >
                    </div>
                </div>

                <!-- Foto Utama Berita (Sesuai Referensi Gambar) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Foto Utama Berita</label>
                    
                    <!-- Box Preview / Upload Container -->
                    <div class="border border-slate-200 rounded-xl p-3.5 sm:p-4 bg-white">
                        <!-- State: Ketika Foto Terpilih/Terunggah -->
                        <div id="create-photo-preview-box" class="hidden flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img id="create-photo-thumb" src="" alt="Thumbnail" class="w-20 h-14 sm:w-24 sm:h-16 rounded-lg object-cover border border-slate-200 shrink-0 bg-slate-50">
                                <div class="min-w-0 space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[10px]"></i> Foto Terunggah
                                    </span>
                                    <div id="create-photo-name" class="text-xs text-slate-600 font-mono truncate max-w-[200px] sm:max-w-xs"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                <button type="button" onclick="document.getElementById('create-featured-image-input').click()" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 transition cursor-pointer shadow-2xs">
                                    Ganti Foto
                                </button>
                                <button type="button" onclick="resetCreatePhoto()" class="px-3 py-1.5 rounded-lg border border-rose-200 bg-white hover:bg-rose-50 text-xs font-medium text-rose-600 transition cursor-pointer shadow-2xs">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <!-- State: Ketika Belum Ada Foto (Upload Trigger) -->
                        <div id="create-photo-upload-placeholder" class="text-center py-5 cursor-pointer hover:bg-slate-50/70 rounded-lg transition border border-dashed border-slate-200" onclick="document.getElementById('create-featured-image-input').click()">
                            <i class="fa-regular fa-image text-3xl text-slate-300 mb-2"></i>
                            <div class="text-xs font-medium text-slate-700">Pilih Foto Utama Berita</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Klik untuk memilih file dari komputer Anda</div>
                        </div>

                        <!-- Real Hidden File Input -->
                        <input 
                            type="file" 
                            id="create-featured-image-input" 
                            name="featured_image" 
                            accept="image/jpeg,image/png,image/webp,image/avif" 
                            class="hidden" 
                            onchange="handleCreatePhotoSelected(this)"
                        >
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1.5">
                        Format yang didukung: JPG, PNG, WebP, AVIF hingga 10MB.
                    </div>
                </div>

                <!-- Isi Berita dengan WYSIWYG Realtime Editor -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Isi Berita <span class="text-rose-500">*</span></label>
                    <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:border-[#0A2373] focus-within:ring-1 focus-within:ring-[#0A2373]/20 transition">
                        <!-- Formatting Toolbar -->
                        <div class="bg-slate-50/90 border-b border-slate-200 px-3 py-2 flex items-center gap-1 flex-wrap text-slate-600">
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('bold');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 font-bold text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Tebal (Ctrl+B)">
                                B
                            </button>
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('italic');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 italic text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition font-serif" title="Miring (Ctrl+I)">
                                I
                            </button>
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('underline');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 underline text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Garis Bawah (Ctrl+U)">
                                U
                            </button>
                            <div class="h-4 w-px bg-slate-300 mx-1"></div>
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('insertUnorderedList');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Daftar Butir">
                                <i class="fa-solid fa-list-ul"></i>
                            </button>
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('insertOrderedList');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Daftar Nomor">
                                <i class="fa-solid fa-list-ol"></i>
                            </button>
                            <button type="button" onmousedown="event.preventDefault(); execCreateCmd('formatBlock', 'blockquote');" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Kutipan">
                                <i class="fa-solid fa-quote-left"></i>
                            </button>
                            <button type="button" onmousedown="event.preventDefault(); promptCreateLink();" class="p-1.5 rounded hover:bg-slate-200 text-slate-700 text-xs w-7 h-7 flex items-center justify-center cursor-pointer transition" title="Sisipkan Tautan">
                                <i class="fa-solid fa-link"></i>
                            </button>
                        </div>
                        <!-- Contenteditable Visual Editor -->
                        <div 
                            id="create-wysiwyg-editor" 
                            contenteditable="true" 
                            class="w-full p-3.5 min-h-[220px] max-h-[400px] overflow-y-auto text-xs sm:text-sm text-slate-800 bg-white focus:outline-none leading-relaxed"
                            oninput="syncCreateContent()"
                        ></div>
                        <textarea id="create-hidden-content" name="content" class="hidden"></textarea>
                    </div>
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="published">Tayang (Publik)</option>
                        <option value="draft">Draf (Disimpan)</option>
                    </select>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('create-publication-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer shadow-xs">
                        Simpan Publikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    #create-wysiwyg-editor blockquote {
        border-left: 3px solid #0A2373;
        padding-left: 12px;
        margin: 8px 0;
        color: #475569;
        font-style: italic;
    }
    #create-wysiwyg-editor ul {
        list-style-type: disc;
        margin-left: 20px;
        margin-bottom: 8px;
    }
    #create-wysiwyg-editor ol {
        list-style-type: decimal;
        margin-left: 20px;
        margin-bottom: 8px;
    }
    #create-wysiwyg-editor a {
        color: #0A2373;
        text-decoration: underline;
    }
    #create-wysiwyg-editor p {
        margin-bottom: 8px;
    }
</style>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild?.classList.remove('scale-95');
            modal.firstElementChild?.classList.add('scale-100');
        }, 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.add('opacity-0');
        modal.firstElementChild?.classList.remove('scale-100');
        modal.firstElementChild?.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Create Modal Photo Handlers
    function handleCreatePhotoSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('create-photo-thumb').src = e.target.result;
                document.getElementById('create-photo-name').textContent = file.name;
                document.getElementById('create-photo-preview-box').classList.remove('hidden');
                document.getElementById('create-photo-preview-box').classList.add('flex');
                document.getElementById('create-photo-upload-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function resetCreatePhoto() {
        const input = document.getElementById('create-featured-image-input');
        input.value = '';
        document.getElementById('create-photo-thumb').src = '';
        document.getElementById('create-photo-name').textContent = '';
        document.getElementById('create-photo-preview-box').classList.add('hidden');
        document.getElementById('create-photo-preview-box').classList.remove('flex');
        document.getElementById('create-photo-upload-placeholder').classList.remove('hidden');
    }

    // Create Modal Real-time WYSIWYG Editor
    function syncCreateContent() {
        const editor = document.getElementById('create-wysiwyg-editor');
        const hidden = document.getElementById('create-hidden-content');
        if (editor && hidden) {
            hidden.value = editor.innerHTML;
        }
    }

    function execCreateCmd(command, value = null) {
        document.getElementById('create-wysiwyg-editor').focus();
        document.execCommand(command, false, value);
        syncCreateContent();
    }

    function promptCreateLink() {
        const url = prompt('Masukkan tautan URL (misal: https://example.com):', 'https://');
        if (url) {
            execCreateCmd('createLink', url);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const editor = document.getElementById('create-wysiwyg-editor');
        if (!editor) return;

        editor.addEventListener('keydown', function (e) {
            if (e.ctrlKey || e.metaKey) {
                const key = e.key.toLowerCase();
                if (key === 'b') {
                    e.preventDefault();
                    execCreateCmd('bold');
                } else if (key === 'i') {
                    e.preventDefault();
                    execCreateCmd('italic');
                } else if (key === 'u') {
                    e.preventDefault();
                    execCreateCmd('underline');
                }
            }
        });
    });
</script>
@endpush
