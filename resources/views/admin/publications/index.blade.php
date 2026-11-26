@extends('layouts.admin')

@section('title', 'Kelola Publikasi')
@section('page_title', 'Publication')

@section('content')
    @php
        $canManagePublications = Auth::user()->canManage('publikasi');
    @endphp

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
                Publikasi &amp; Kabar Komunitas
            </h1>
            @unless ($canManagePublications)
                <p class="text-xs text-slate-500 mt-1">Mode lihat saja (Anda tidak memiliki izin untuk menambah, mengubah, atau menghapus data ini).</p>
            @endunless
        </div>

        @if ($canManagePublications)
            <div class="flex items-center gap-2.5">
                <a 
                    href="{{ route('admin.publications.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0A2373] text-white text-xs sm:text-sm font-semibold hover:bg-[#071a57] active:scale-[0.98] transition shadow-xs cursor-pointer whitespace-nowrap"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Publikasi</span>
                </a>
            </div>
        @endif
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
                        @if ($canManagePublications)
                            <th class="px-4 sm:px-6 py-3.5 w-28 text-center">Aksi</th>
                        @endif
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
                            @if ($canManagePublications)
                                <td class="px-4 sm:px-6 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
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
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canManagePublications ? 6 : 5 }}" class="px-6 py-12 text-center text-slate-400 text-xs sm:text-sm">
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
@endsection