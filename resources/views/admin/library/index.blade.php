@extends('layouts.admin')

@section('title', 'Kelola Digital Library')
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

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Katalog Digital Library
            </h1>
        </div>
        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('admin.library.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Buku</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs mb-6">
        <form method="GET" action="{{ route('admin.library.index') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input 
                    type="search" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Cari berdasarkan judul buku, penulis, atau penerbit..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"
                >
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">
                <select 
                    name="genre" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Genre</option>
                    @foreach ($genres as $genreName)
                        <option value="{{ $genreName }}" @selected(request('genre') === $genreName)>{{ $genreName }}</option>
                    @endforeach
                </select>

                <select 
                    name="publisher" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Penerbit</option>
                    @foreach ($publishers as $pubName)
                        <option value="{{ $pubName }}" @selected(request('publisher') === $pubName)>{{ $pubName }}</option>
                    @endforeach
                </select>

                @if(request('q') || request('genre') || request('publisher'))
                    <a href="{{ route('admin.library.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs text-rose-600 hover:bg-rose-50 transition flex items-center gap-1.5" title="Reset filter">
                        <i class="fa-solid fa-xmark"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card (Kolom Ketersediaan sudah dihapus) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs sm:text-sm font-semibold text-slate-800">
                Total Koleksi: <span class="text-[#0A2373] font-bold">{{ $books->total() }}</span> buku
                @if(request('q') || request('genre') || request('publisher'))
                    <span class="text-xs text-slate-400 font-normal">(difilter)</span>
                @endif
            </div>
            <div class="text-xs text-slate-500">
                Halaman {{ $books->currentPage() }} dari {{ max(1, $books->lastPage()) }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 font-medium border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 sm:px-6 py-3.5 w-14 text-center">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Buku &amp; Penulis</th>
                        <th class="px-4 sm:px-6 py-3.5 w-44">Genre</th>
                        <th class="px-4 sm:px-6 py-3.5 w-40">Penerbit</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($books as $index => $book)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- No Urut Sesuai Halaman -->
                            <td class="px-4 sm:px-6 py-4 text-center font-['Poppins'] font-bold text-xs text-slate-400">
                                {{ sprintf('%02d', ($books->firstItem() ?? 1) + $index) }}
                            </td>

                            <!-- Book Cover & Info -->
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3.5 max-w-md">
                                    <div class="w-11 h-14 bg-slate-100 rounded-md border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center p-0.5">
                                        <img 
                                            src="{{ $book->cover_path ? asset('assets/images/books/'.$book->cover_path) : 'https://placehold.co/400x600?text=No+Cover' }}" 
                                            alt="{{ $book->title }}" 
                                            class="w-full h-full object-contain"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition line-clamp-1">
                                            {{ $book->title }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                            <span><i class="fa-regular fa-user text-[11px] text-slate-400"></i> {{ $book->author->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Genre (Teks biasa tanpa background warna biru) -->
                            <td class="px-4 sm:px-6 py-4 text-xs font-medium text-slate-700 whitespace-nowrap">
                                {{ $book->genre->name ?? '-' }}
                            </td>

                            <!-- Publisher -->
                            <td class="px-4 sm:px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                <i class="fa-solid fa-building text-[11px] text-slate-400 mr-1.5"></i>
                                {{ $book->publisher->name ?? '-' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <!-- Halaman detail khusus pengeditan -->
                                    <a 
                                        href="{{ route('admin.library.edit', $book->id) }}" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition" 
                                        title="Edit buku (Halaman Detail)"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="confirmDeleteBook({{ $book->id }}, '{{ addslashes($book->title) }}')" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" 
                                        title="Hapus buku"
                                    >
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs sm:text-sm">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-book-open text-lg"></i>
                                </div>
                                Tidak ada koleksi buku yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Interaktif -->
        @if ($books->hasPages())
            <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $books->firstItem() ?? 0 }} sampai {{ $books->lastItem() ?? 0 }} dari {{ $books->total() }} entri
                </div>
                <div class="flex items-center gap-1 self-end sm:self-auto">
                    <!-- Tombol Prev -->
                    @if ($books->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            &lt;
                        </span>
                    @else
                        <a 
                            href="{{ $books->previousPageUrl() }}" 
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            title="Halaman sebelumnya"
                        >
                            &lt;
                        </a>
                    @endif

                    <!-- Nomor Halaman -->
                    @for ($p = 1; $p <= $books->lastPage(); $p++)
                        @if ($p == $books->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-[#0A2373] text-white font-['Poppins'] font-bold text-xs">
                                {{ $p }}
                            </span>
                        @else
                            <a 
                                href="{{ $books->url($p) }}" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition font-['Poppins'] font-bold text-xs"
                            >
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    <!-- Tombol Next -->
                    @if ($books->hasMorePages())
                        <a 
                            href="{{ $books->nextPageUrl() }}" 
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
                Menampilkan seluruh {{ $books->total() }} entri buku
            </div>
        @endif
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-book-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Form: Tambah Buku Baru (Tanpa Kolom Ketersediaan) -->
    <div id="create-book-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Koleksi Buku</h3>
                </div>
                <button type="button" onclick="closeModal('create-book-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form (Real Backend POST) -->
            <form action="{{ route('admin.library.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required placeholder="Contoh: Negeri 5 Menara" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" name="author_name" required placeholder="Contoh: Ahmad Fuadi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penerbit <span class="text-rose-500">*</span></label>
                        <input type="text" name="publisher_name" required placeholder="Contoh: Gramedia" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Genre Buku <span class="text-rose-500">*</span></label>
                    <input type="text" name="genre_name" list="create-genre-list" required placeholder="Pilih atau ketik genre (Contoh: Novel, Memoar, dsb)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    <datalist id="create-genre-list">
                        @foreach ($genres as $genreName)
                            <option value="{{ $genreName }}"></option>
                        @endforeach
                        <option value="Novel"></option>
                        <option value="Memoar"></option>
                        <option value="Pengembangan Diri"></option>
                    </datalist>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Unggah Sampul Buku (Opsional)</label>
                    <input type="file" name="cover_image" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WebP (maks. 3MB). Jika kosong, akan memakai sampul default.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sinopsis / Deskripsi Buku</label>
                    <textarea name="description" rows="4" placeholder="Tuliskan gambaran isi buku dan pesan utama..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('create-book-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: Edit Buku (Tanpa Kolom Ketersediaan) -->
    <div id="edit-book-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Informasi Buku</h3>
                </div>
                <button type="button" onclick="closeModal('edit-book-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form (Real Backend PUT) -->
            <form id="edit-book-form" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-rose-500">*</span></label>
                    <input id="edit-book-title" type="text" name="title" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penulis <span class="text-rose-500">*</span></label>
                        <input id="edit-book-author" type="text" name="author_name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penerbit <span class="text-rose-500">*</span></label>
                        <input id="edit-book-publisher" type="text" name="publisher_name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Genre Buku <span class="text-rose-500">*</span></label>
                    <input id="edit-book-genre" type="text" name="genre_name" list="edit-genre-list" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    <datalist id="edit-genre-list">
                        @foreach ($genres as $genreName)
                            <option value="{{ $genreName }}"></option>
                        @endforeach
                        <option value="Novel"></option>
                        <option value="Memoar"></option>
                        <option value="Pengembangan Diri"></option>
                    </datalist>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ganti Sampul Buku (Opsional)</label>
                    <input type="file" name="cover_image" accept="image/png, image/jpeg, image/jpg, image/webp" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#E8EEF8] file:text-[#0A2373] hover:file:bg-[#d6e2f5] cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah sampul saat ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sinopsis / Deskripsi Buku</label>
                    <textarea id="edit-book-desc" name="description" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('edit-book-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
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

    function openEditBookModal(book) {
        const form = document.getElementById('edit-book-form');
        form.action = '{{ url('/admin/library') }}/' + book.id;

        document.getElementById('edit-book-title').value = book.title || '';
        document.getElementById('edit-book-author').value = book.author || '';
        document.getElementById('edit-book-publisher').value = book.publisher || '';
        document.getElementById('edit-book-genre').value = book.genre || '';
        document.getElementById('edit-book-desc').value = book.description || '';
        openModal('edit-book-modal');
    }

    function confirmDeleteBook(id, title) {
        if (confirm(`Apakah Anda yakin ingin menghapus buku "${title}" dari katalog? Data akan dihapus secara permanen.`)) {
            const form = document.getElementById('delete-book-form');
            form.action = '{{ url('/admin/library') }}/' + id;
            form.submit();
        }
    }
    
</script>
@endpush
