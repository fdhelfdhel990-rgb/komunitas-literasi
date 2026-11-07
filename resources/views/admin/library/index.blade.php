@extends('layouts.admin')

@section('title', 'Kelola Digital Library')
@section('page_title', 'Digital Library')

@section('content')
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">03</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Manajemen Konten</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Katalog Digital Library
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola koleksi buku bacaan, penulis, penerbit, dan sinopsis buku yang tampil di web publik.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <button 
                type="button" 
                onclick="openModal('create-book-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Buku</span>
            </button>
        </div>
    </div>

    @php
        // Data buku yang 100% selaras dengan web publik (library/index.blade.php)
        $books = [
            ['id' => 1, 'image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah persahabatan dan perjuangan enam santri dalam mengejar cita-cita. Sebuah cerita tentang pendidikan, ketekunan, dan keyakinan bahwa impian dapat membawa seseorang melampaui batas.'],
            ['id' => 2, 'image' => 'book-2.png', 'title' => 'Educated', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar tentang perjalanan seorang perempuan mencari pendidikan dan membangun pemahaman baru tentang dirinya serta dunia di sekitarnya.'],
            ['id' => 3, 'image' => 'book-3.png', 'title' => 'Quarter-Life Crisis', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Buku pengembangan diri yang mengajak pembaca memahami masa transisi dewasa muda dan menyusun langkah dengan lebih sadar.'],
            ['id' => 4, 'image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Perjalanan Impian', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Novel inspiratif tentang persahabatan, pendidikan, dan semangat untuk berusaha meraih cita-cita.'],
            ['id' => 5, 'image' => 'book-2.png', 'title' => 'Educated: Sebuah Memoar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar mengenai kekuatan pendidikan dan perjalanan menemukan jati diri.'],
            ['id' => 6, 'image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Panduan Bertumbuh', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Panduan reflektif untuk menghadapi perubahan dan pilihan pada masa dewasa muda.'],
            ['id' => 7, 'image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Edisi Komunitas', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah tentang belajar, hidup bersama, dan keyakinan yang menguatkan perjalanan meraih masa depan.'],
            ['id' => 8, 'image' => 'book-2.png', 'title' => 'Educated: Perjalanan Belajar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Cerita perjalanan belajar yang membuka perspektif dan memberi ruang untuk bertumbuh.'],
            ['id' => 9, 'image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Mengenal Diri', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Bacaan tentang mengenali nilai diri dan membuat keputusan dengan lebih terarah.'],
            ['id' => 10, 'image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Semangat Belajar', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Data contoh untuk pratinjau katalog. Kisah tentang persahabatan dan semangat belajar dalam perjalanan meraih cita-cita.'],
            ['id' => 11, 'image' => 'book-2.png', 'title' => 'Educated: Catatan Perjalanan', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Data contoh untuk pratinjau katalog. Memoar tentang pengalaman belajar dan perubahan perspektif hidup.'],
            ['id' => 12, 'image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Langkah Bertumbuh', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Data contoh untuk pratinjau katalog. Bacaan reflektif untuk memahami perubahan dan menentukan langkah ke depan.'],
            ['id' => 13, 'image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Kisah Persahabatan', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Data contoh untuk pratinjau katalog. Cerita tentang kebersamaan, pendidikan, dan keyakinan dalam mencapai impian.'],
            ['id' => 14, 'image' => 'book-2.png', 'title' => 'Educated: Menemukan Jati Diri', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Data contoh untuk pratinjau katalog. Perjalanan menemukan pengetahuan dan membentuk pemahaman diri.'],
            ['id' => 15, 'image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Menata Tujuan', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Data contoh untuk pratinjau katalog. Panduan refleksi untuk menyusun tujuan pada masa dewasa muda.']
        ];

        $searchQ = mb_strtolower((string) request('q', ''));
        $genreQ = (string) request('genre', '');
        $publisherQ = (string) request('publisher', '');

        $filteredBooks = array_values(array_filter($books, function($book) use ($searchQ, $genreQ, $publisherQ) {
            $matchQ = $searchQ === '' || str_contains(mb_strtolower($book['title'].' '.$book['author'].' '.$book['publisher']), $searchQ);
            $matchGenre = $genreQ === '' || $book['genre'] === $genreQ;
            $matchPublisher = $publisherQ === '' || $book['publisher'] === $publisherQ;
            return $matchQ && $matchGenre && $matchPublisher;
        }));

        // Pagination interaktif (6 buku per halaman)
        $perPage = 6;
        $totalItems = count($filteredBooks);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = min(max((int) request('page', 1), 1), $totalPages);
        $startIndex = ($currentPage - 1) * $perPage;
        $paginatedBooks = array_slice($filteredBooks, $startIndex, $perPage);
    @endphp

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
                    <option value="Novel" @selected(request('genre') === 'Novel')>Novel</option>
                    <option value="Memoar" @selected(request('genre') === 'Memoar')>Memoar</option>
                    <option value="Pengembangan Diri" @selected(request('genre') === 'Pengembangan Diri')>Pengembangan Diri</option>
                </select>

                <select 
                    name="publisher" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Penerbit</option>
                    <option value="Gramedia" @selected(request('publisher') === 'Gramedia')>Gramedia</option>
                    <option value="Mizan" @selected(request('publisher') === 'Mizan')>Mizan</option>
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

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs sm:text-sm font-semibold text-slate-800">
                Total Koleksi: <span class="text-[#0A2373] font-bold">{{ $totalItems }}</span> buku
                @if(request('q') || request('genre') || request('publisher'))
                    <span class="text-xs text-slate-400 font-normal">(difilter dari {{ count($books) }} total)</span>
                @endif
            </div>
            <div class="text-xs text-slate-500">
                Halaman {{ $currentPage }} dari {{ $totalPages }}
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
                    @forelse ($paginatedBooks as $index => $book)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- No Urut Sesuai Halaman -->
                            <td class="px-4 sm:px-6 py-4 text-center font-mono text-xs text-slate-400">
                                {{ sprintf('%02d', $startIndex + $index + 1) }}
                            </td>

                            <!-- Book Cover & Info -->
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3.5 max-w-md">
                                    <div class="w-11 h-14 bg-slate-100 rounded-md border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center p-0.5">
                                        <img 
                                            src="{{ asset('assets/images/books/'.$book['image']) }}" 
                                            alt="{{ $book['title'] }}" 
                                            class="w-full h-full object-contain"
                                            loading="lazy"
                                        >
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition line-clamp-1">
                                            {{ $book['title'] }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                            <span><i class="fa-regular fa-user text-[11px] text-slate-400"></i> {{ $book['author'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Genre -->
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#E8EEF8] text-[#0A2373]">
                                    {{ $book['genre'] }}
                                </span>
                            </td>

                            <!-- Publisher -->
                            <td class="px-4 sm:px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                <i class="fa-solid fa-building text-[11px] text-slate-400 mr-1.5"></i>
                                {{ $book['publisher'] }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <!-- Link langsung ke web user dengan filter buku terpilih -->
                                    <a 
                                        href="{{ route('library.index', ['book' => $book['title']]) }}" 
                                        target="_blank" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition" 
                                        title="Pratinjau di web publik"
                                    >
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="openEditBookModal({{ json_encode($book) }})" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                        title="Edit buku"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button 
                                        type="button" 
                                        onclick="confirmDeleteBook('{{ $book['title'] }}')" 
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
        @if ($totalPages > 1)
            <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $startIndex + 1 }} sampai {{ min($startIndex + $perPage, $totalItems) }} dari {{ $totalItems }} entri
                </div>
                <div class="flex items-center gap-1 self-end sm:self-auto">
                    <!-- Tombol Prev -->
                    @if ($currentPage > 1)
                        <a 
                            href="{{ route('admin.library.index', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" 
                            class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            title="Halaman sebelumnya"
                        >
                            &lt;
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                            &lt;
                        </span>
                    @endif

                    <!-- Nomor Halaman -->
                    @for ($p = 1; $p <= $totalPages; $p++)
                        @if ($p == $currentPage)
                            <span class="px-3 py-1.5 rounded-lg bg-[#0A2373] text-white font-semibold">
                                {{ $p }}
                            </span>
                        @else
                            <a 
                                href="{{ route('admin.library.index', array_merge(request()->query(), ['page' => $p])) }}" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            >
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    <!-- Tombol Next -->
                    @if ($currentPage < $totalPages)
                        <a 
                            href="{{ route('admin.library.index', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" 
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
                Menampilkan seluruh {{ $totalItems }} entri buku
            </div>
        @endif
    </div>

    <!-- Modal Form: Tambah Buku Baru (Tanpa Kolom Ketersediaan) -->
    <div id="create-book-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Koleksi Buku</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi buku untuk katalog Digital Library.</p>
                </div>
                <button type="button" onclick="closeModal('create-book-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form onsubmit="handleFormSubmit(event, 'Buku baru berhasil ditambahkan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-rose-500">*</span></label>
                    <input type="text" required placeholder="Contoh: Negeri 5 Menara" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" required placeholder="Contoh: Ahmad Fuadi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penerbit <span class="text-rose-500">*</span></label>
                        <input type="text" required placeholder="Contoh: Gramedia" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Genre Buku <span class="text-rose-500">*</span></label>
                    <select required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="">Pilih Genre</option>
                        <option value="Novel">Novel</option>
                        <option value="Memoar">Memoar</option>
                        <option value="Pengembangan Diri">Pengembangan Diri</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sampul Buku</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-[#0A2373] transition cursor-pointer bg-slate-50/50">
                        <i class="fa-solid fa-book-bookmark text-2xl text-slate-400 mb-1"></i>
                        <div class="text-xs text-slate-600 font-medium">Klik untuk unggah sampul buku</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">JPG, PNG, WebP (Proporsional sesuai buku di web user)</div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sinopsis / Deskripsi Buku</label>
                    <textarea rows="4" placeholder="Tuliskan gambaran isi buku dan pesan utama..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
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
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui metadata katalog buku.</p>
                </div>
                <button type="button" onclick="closeModal('edit-book-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form onsubmit="handleFormSubmit(event, 'Perubahan buku berhasil disimpan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Buku <span class="text-rose-500">*</span></label>
                    <input id="edit-book-title" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penulis <span class="text-rose-500">*</span></label>
                        <input id="edit-book-author" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Penerbit <span class="text-rose-500">*</span></label>
                        <input id="edit-book-publisher" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Genre Buku <span class="text-rose-500">*</span></label>
                    <select id="edit-book-genre" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                        <option value="Novel">Novel</option>
                        <option value="Memoar">Memoar</option>
                        <option value="Pengembangan Diri">Pengembangan Diri</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sinopsis / Deskripsi Buku</label>
                    <textarea id="edit-book-desc" rows="4" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
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
        document.getElementById('edit-book-title').value = book.title || '';
        document.getElementById('edit-book-author').value = book.author || '';
        document.getElementById('edit-book-publisher').value = book.publisher || '';
        document.getElementById('edit-book-genre').value = book.genre || 'Novel';
        document.getElementById('edit-book-desc').value = book.description || '';
        openModal('edit-book-modal');
    }

    function confirmDeleteBook(title) {
        if (confirm(`Apakah Anda yakin ingin menghapus buku "${title}" dari katalog?`)) {
            alert('Buku berhasil dihapus dari katalog (Mockup).');
        }
    }

    function handleFormSubmit(e, message) {
        e.preventDefault();
        alert(message);
        closeModal('create-book-modal');
        closeModal('edit-book-modal');
    }
</script>
@endpush
