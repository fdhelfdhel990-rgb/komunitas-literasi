@extends('layouts.admin')

@section('title', 'Kelola Publikasi')
@section('page_title', 'Publication')

@section('content')
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-6 h-6 rounded-md bg-[#E8EEF8] text-[#0A2373] text-xs font-mono font-bold flex items-center justify-center">02</span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#0A2373]">Manajemen Konten</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Publikasi &amp; Kabar Komunitas
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Kelola artikel berita, kegiatan lapangan, dan status tayang yang tampil di web publik.
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <button 
                type="button" 
                onclick="openModal('create-publication-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Publikasi</span>
            </button>
        </div>
    </div>

    @php
        // Data artikel yang 100% selaras dengan web publik (publications/index.blade.php)
        $articles = [
            [
                'id' => 1,
                'image' => 'publication-1.jpg',
                'category' => 'Sosial',
                'date' => '05 Oktober 2026',
                'title' => 'Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan',
                'summary' => 'Tambun Selatan - Memiliki visi bersama dalam memajukan literasi, 20 relawan Komunitas Literasi Remaja Tambun Selatan secara aktif mendampingi....',
                'status' => 'Tayang',
                'author' => 'Tim Media',
                'slug' => 'semangat-20-relawan'
            ],
            [
                'id' => 2,
                'image' => 'publication-2.jpg',
                'category' => 'Pendidikan',
                'date' => '15 Agustus 2026',
                'title' => 'Semangat Membaca Anak-anak bersama Gerobak Angkasa',
                'summary' => 'Relawan mengajak anak-anak menikmati bacaan dan belajar bersama melalui perpustakaan keliling Gerobak Angkasa....',
                'status' => 'Tayang',
                'author' => 'Admin Literasi',
                'slug' => 'semangat-membaca-anak'
            ],
            [
                'id' => 3,
                'image' => 'publication-3.jpg',
                'category' => 'Sosial',
                'date' => '25 Agustus 2026',
                'title' => 'Gerobak Angkasa Hadirkan Perpustakaan Keliling',
                'summary' => 'Koleksi buku dan ruang membaca kini hadir lebih dekat dengan anak-anak di berbagai desa Tambun Selatan....',
                'status' => 'Tayang',
                'author' => 'Admin Literasi',
                'slug' => 'gerobak-angkasa-keliling'
            ],
            [
                'id' => 4,
                'image' => 'publication-1.jpg',
                'category' => 'Pendidikan',
                'date' => '12 Agustus 2026',
                'title' => 'Ruang Baca Bersama Tumbuhkan Minat Literasi Anak',
                'summary' => 'Relawan mendampingi anak-anak mengenal buku dan menikmati kegiatan membaca bersama di lingkungan mereka....',
                'status' => 'Tayang',
                'author' => 'Fadhel',
                'slug' => 'ruang-baca-bersama'
            ],
            [
                'id' => 5,
                'image' => 'publication-2.jpg',
                'category' => 'Sosial',
                'date' => '08 Agustus 2026',
                'title' => 'Relawan Hadir Membuka Akses Bacaan di Desa',
                'summary' => 'Kegiatan komunitas mendekatkan bacaan dan pendampingan belajar kepada lebih banyak keluarga....',
                'status' => 'Tayang',
                'author' => 'Tim Humas',
                'slug' => 'relawan-hadir-desa'
            ],
            [
                'id' => 6,
                'image' => 'publication-3.jpg',
                'category' => 'Pendidikan',
                'date' => '02 Agustus 2026',
                'title' => 'Perpustakaan Keliling Menjangkau Pembaca Muda',
                'summary' => 'Gerobak Angkasa menjadi ruang belajar bergerak yang menyenangkan bagi anak-anak Tambun Selatan....',
                'status' => 'Tayang',
                'author' => 'Tim Media',
                'slug' => 'perpustakaan-keliling-menjangkau'
            ],
            [
                'id' => 7,
                'image' => 'publication-1.jpg',
                'category' => 'Sosial',
                'date' => '28 Juli 2026',
                'title' => 'Kolaborasi Warga dan Relawan untuk Literasi',
                'summary' => 'Dukungan warga membantu kegiatan membaca tumbuh menjadi kebiasaan bersama di lingkungan desa....',
                'status' => 'Tayang',
                'author' => 'Admin Literasi',
                'slug' => 'kolaborasi-warga-relawan'
            ],
            [
                'id' => 8,
                'image' => 'publication-2.jpg',
                'category' => 'Pendidikan',
                'date' => '21 Juli 2026',
                'title' => 'Belajar dan Bercerita Bersama Gerobak Angkasa',
                'summary' => 'Sesi membaca dan bercerita memberi anak-anak kesempatan belajar dalam suasana akrab....',
                'status' => 'Tayang',
                'author' => 'Tim Humas',
                'slug' => 'belajar-bercerita-gerobak'
            ],
            [
                'id' => 9,
                'image' => 'publication-3.jpg',
                'category' => 'Sosial',
                'date' => '14 Juli 2026',
                'title' => 'Gerakan Literasi dari Tambun Selatan',
                'summary' => 'Komunitas terus mengajak remaja dan masyarakat mendukung akses bacaan yang lebih luas....',
                'status' => 'Tayang',
                'author' => 'Admin Literasi',
                'slug' => 'gerakan-literasi-tamsel'
            ],
        ];

        $filterQ = mb_strtolower((string) request('q', ''));
        $filterCat = (string) request('category', '');
        $filterStatus = (string) request('status', '');

        $filtered = array_values(array_filter($articles, function($item) use ($filterQ, $filterCat, $filterStatus) {
            $matchQ = $filterQ === '' || str_contains(mb_strtolower($item['title'].' '.$item['summary'].' '.$item['author']), $filterQ);
            $matchCat = $filterCat === '' || $item['category'] === $filterCat;
            $matchStatus = $filterStatus === '' || $item['status'] === $filterStatus;
            return $matchQ && $matchCat && $matchStatus;
        }));

        // Pagination interaktif (6 artikel per halaman)
        $perPage = 6;
        $totalItems = count($filtered);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $currentPage = min(max((int) request('page', 1), 1), $totalPages);
        $startIndex = ($currentPage - 1) * $perPage;
        $paginatedArticles = array_slice($filtered, $startIndex, $perPage);
    @endphp

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
                    placeholder="Cari berdasarkan judul, cuplikan berita, atau penulis..." 
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
                    <option value="Pendidikan" @selected(request('category') === 'Pendidikan')>Pendidikan</option>
                    <option value="Sosial" @selected(request('category') === 'Sosial')>Sosial</option>
                </select>

                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="px-3 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer"
                >
                    <option value="">Semua Status</option>
                    <option value="Tayang" @selected(request('status') === 'Tayang')>Tayang</option>
                    <option value="Draf" @selected(request('status') === 'Draf')>Draf</option>
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
                Total Publikasi: <span class="text-[#0A2373] font-bold">{{ $totalItems }}</span> artikel
                @if(request('q') || request('category') || request('status'))
                    <span class="text-xs text-slate-400 font-normal">(difilter dari {{ count($articles) }} total)</span>
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
                        <th class="px-4 sm:px-6 py-3.5">Artikel</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Kategori</th>
                        <th class="px-4 sm:px-6 py-3.5 w-36">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($paginatedArticles as $index => $article)
                        <tr class="hover:bg-slate-50/60 transition group">
                            <!-- No -->
                            <td class="px-4 sm:px-6 py-4 text-center font-mono text-xs text-slate-400">
                                {{ sprintf('%02d', $startIndex + $index + 1) }}
                            </td>

                            <!-- Article Info -->
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-start gap-3.5 max-w-lg">
                                    <img 
                                        src="{{ asset('assets/images/publications/'.$article['image']) }}" 
                                        alt="Sampul artikel" 
                                        class="w-14 h-14 rounded-lg object-cover border border-slate-200 shrink-0"
                                    >
                                    <div class="min-w-0">
                                        <div class="font-semibold text-slate-900 group-hover:text-[#0A2373] transition line-clamp-1">
                                            {{ $article['title'] }}
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                            {{ $article['summary'] }}
                                        </p>
                                        <div class="text-[11px] text-slate-400 mt-1">
                                            Penulis: <span class="font-medium text-slate-600">{{ $article['author'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#E8EEF8] text-[#0A2373]">
                                    {{ $article['category'] }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="px-4 sm:px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $article['date'] }}
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 sm:px-6 py-4 text-center">
                                @if($article['status'] === 'Tayang')
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
                                    <a 
                                        href="{{ $article['slug'] === 'semangat-20-relawan' ? route('publications.show') : route('publications.index', ['q' => $article['title']]) }}" 
                                        target="_blank" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition" 
                                        title="Pratinjau di web publik"
                                    >
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <button 
                                        type="button" 
                                        onclick="openEditModal({{ json_encode($article) }})" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-[#0A2373] hover:bg-slate-100 transition cursor-pointer" 
                                        title="Edit artikel"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button 
                                        type="button" 
                                        onclick="confirmDelete('{{ $article['title'] }}')" 
                                        class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer" 
                                        title="Hapus artikel"
                                    >
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
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
        @if ($totalPages > 1)
            <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    Menampilkan {{ $startIndex + 1 }} sampai {{ min($startIndex + $perPage, $totalItems) }} dari {{ $totalItems }} entri
                </div>
                <div class="flex items-center gap-1 self-end sm:self-auto">
                    <!-- Tombol Prev -->
                    @if ($currentPage > 1)
                        <a 
                            href="{{ route('admin.publications.index', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" 
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
                                href="{{ route('admin.publications.index', array_merge(request()->query(), ['page' => $p])) }}" 
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                            >
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    <!-- Tombol Next -->
                    @if ($currentPage < $totalPages)
                        <a 
                            href="{{ route('admin.publications.index', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" 
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
                Menampilkan seluruh {{ $totalItems }} entri publikasi
            </div>
        @endif
    </div>

    <!-- Modal Form: Tambah Publikasi -->
    <div id="create-publication-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Publikasi Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi formulir untuk menerbitkan artikel atau kabar kegiatan.</p>
                </div>
                <button type="button" onclick="closeModal('create-publication-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form onsubmit="handleFormSubmit(event, 'Publikasi baru berhasil disimpan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Artikel <span class="text-rose-500">*</span></label>
                    <input type="text" required placeholder="Contoh: Semangat Membaca di Gerobak Angkasa" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                        <select required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                            <option value="">Pilih Kategori</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Sosial">Sosial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Tayang <span class="text-rose-500">*</span></label>
                        <select required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                            <option value="Tayang">Tayang (Publik)</option>
                            <option value="Draf">Draf (Disimpan)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sampul Artikel (Gambar)</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-[#0A2373] transition cursor-pointer bg-slate-50/50">
                        <i class="fa-regular fa-image text-2xl text-slate-400 mb-1"></i>
                        <div class="text-xs text-slate-600 font-medium">Klik untuk unggah atau seret gambar ke sini</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">PNG, JPG, WebP hingga 2MB</div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ringkasan Singkat <span class="text-rose-500">*</span></label>
                    <textarea rows="2" required placeholder="Tuliskan 1-2 kalimat pengantar artikel yang menarik..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konten Lengkap <span class="text-rose-500">*</span></label>
                    <textarea rows="5" required placeholder="Tuliskan narasi lengkap artikel di sini..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('create-publication-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition cursor-pointer">
                        Simpan Publikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Form: Edit Publikasi -->
    <div id="edit-publication-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto transform scale-95 transition-transform duration-200">
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Publikasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi artikel atau ubah status tayang.</p>
                </div>
                <button type="button" onclick="closeModal('edit-publication-modal')" class="p-2 -mr-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Modal Body Form -->
            <form onsubmit="handleFormSubmit(event, 'Perubahan publikasi berhasil disimpan!')" class="p-5 sm:p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Artikel <span class="text-rose-500">*</span></label>
                    <input id="edit-title" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori <span class="text-rose-500">*</span></label>
                        <select id="edit-category" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Sosial">Sosial</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Tayang <span class="text-rose-500">*</span></label>
                        <select id="edit-status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:border-[#0A2373] transition cursor-pointer">
                            <option value="Tayang">Tayang (Publik)</option>
                            <option value="Draf">Draf (Disimpan)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ringkasan Singkat <span class="text-rose-500">*</span></label>
                    <textarea id="edit-summary" rows="3" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-800 focus:outline-none focus:border-[#0A2373] focus:ring-1 focus:ring-[#0A2373]/20 transition"></textarea>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal('edit-publication-modal')" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
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

    function openEditModal(article) {
        document.getElementById('edit-title').value = article.title || '';
        document.getElementById('edit-category').value = article.category || 'Pendidikan';
        document.getElementById('edit-status').value = article.status || 'Tayang';
        document.getElementById('edit-summary').value = article.summary || '';
        openModal('edit-publication-modal');
    }

    function confirmDelete(title) {
        if (confirm(`Apakah Anda yakin ingin menghapus artikel "${title}"?`)) {
            alert('Artikel berhasil dihapus (Mockup).');
        }
    }

    function handleFormSubmit(e, message) {
        e.preventDefault();
        alert(message);
        closeModal('create-publication-modal');
        closeModal('edit-publication-modal');
    }
</script>
@endpush
