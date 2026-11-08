@extends('layouts.app', ['title' => 'Digital Library'])

@section('content')
<section class="library-page-section">
    <div class="container">
        @if ($selectedBook)
            <nav class="page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('library.index') }}">Digital Library</a>
                <span aria-hidden="true">&gt;</span>
                <span aria-current="page">Detail</span>
            </nav>

            <article class="book-detail-card">
                <!-- Menggunakan gambar dummy jika cover_path kosong -->
                <img src="{{ $selectedBook->cover_path ? asset('assets/images/books/'.$selectedBook->cover_path) : 'https://placehold.co/400x600?text=No+Cover' }}" alt="Sampul buku {{ $selectedBook->title }}">
                <div class="book-detail-copy">
                    <h1>{{ $selectedBook->title }}</h1>
                    <dl class="book-detail-facts">
                        <div><dt>Penulis</dt><dd><i class="fa-regular fa-user" aria-hidden="true"></i> {{ $selectedBook->author->name }}</dd></div>
                        <div><dt>Penerbit</dt><dd><i class="fa-solid fa-building" aria-hidden="true"></i> {{ $selectedBook->publisher->name }}</dd></div>
                        <div><dt>Genre</dt><dd>{{ $selectedBook->genre->name }}</dd></div>
                        <div><dt>Kondisi Fisik</dt><dd>Baik</dd></div>
                    </dl>
                    <h2>Deskripsi Ringkasan Buku</h2>
                    <p>{{ $selectedBook->description ?? 'Belum ada deskripsi.' }}</p>
                </div>
            </article>
        @else
            <header class="library-catalog-heading">
                <h1>Digital Library</h1>
            </header>
            <div class="library-catalog-layout">
                <aside class="book-filter-panel">
                    <div class="book-filter-title"><h1>Filter Buku</h1><a href="{{ route('library.index') }}">Reset</a></div>
                    <form action="{{ route('library.index') }}" method="GET">
                        <label class="book-filter-label" for="book-search">Pencarian</label>
                        <input id="book-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari buku atau penulis...">
                        
                        <fieldset>
                            <legend>Genre</legend>
                            <label class="book-radio">
                                <input type="radio" name="genre" value="" @checked(request('genre', '') === '') onchange="this.form.submit()">
                                <span>Semua</span>
                            </label>
                            <!-- Looping Genre Dinamis dari Database -->
                            @foreach ($genres as $bookGenre)
                                <label class="book-radio">
                                    <input type="radio" name="genre" value="{{ $bookGenre }}" @checked(request('genre') === $bookGenre) onchange="this.form.submit()">
                                    <span>{{ $bookGenre }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <label class="book-filter-label" for="book-publisher">Penerbit</label>
                        <select id="book-publisher" name="publisher" onchange="this.form.submit()">
                            <option value="">Semua penerbit</option>
                            <!-- Looping Penerbit Dinamis dari Database -->
                            @foreach ($publishers as $pub)
                                <option value="{{ $pub }}" @selected(request('publisher') === $pub)>{{ $pub }}</option>
                            @endforeach
                        </select>
                    </form>
                </aside>

                <div class="book-catalog-content">
                    @if ($paginatedBooks->count())
                        <div class="book-catalog-grid">
                            @foreach ($paginatedBooks as $book)
                                <article class="book-catalog-card">
                                    <img src="{{ $book->cover_path ? asset('assets/images/books/'.$book->cover_path) : 'https://placehold.co/400x600?text=No+Cover' }}" alt="Sampul buku {{ $book->title }}" loading="lazy" decoding="async">
                                    <div class="book-catalog-body">
                                        <h2>{{ $book->title }}</h2>
                                        <p><i class="fa-regular fa-user" aria-hidden="true"></i> {{ $book->author->name }}</p>
                                        <p><i class="fa-solid fa-book-open" aria-hidden="true"></i> {{ $book->genre->name }}</p>
                                        <a href="{{ route('library.index', array_merge(request()->query(), ['book' => $book->title])) }}">Lihat Detail <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        
                        @if ($paginatedBooks->lastPage() > 1)
                            <nav class="book-pagination" aria-label="Navigasi halaman katalog buku">
                                @if ($paginatedBooks->currentPage() > 1)
                                    <a href="{{ $paginatedBooks->previousPageUrl() }}" aria-label="Halaman sebelumnya">&lt;</a>
                                @else
                                    <span class="disabled" aria-disabled="true" aria-label="Halaman sebelumnya">&lt;</span>
                                @endif
                                
                                @for ($page = 1; $page <= $paginatedBooks->lastPage(); $page++)
                                    @if ($page === $paginatedBooks->currentPage())
                                        <span class="current" aria-current="page">{{ $page }}</span>
                                    @else
                                        <a href="{{ $paginatedBooks->url($page) }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                                    @endif
                                @endfor

                                @if ($paginatedBooks->hasMorePages())
                                    <a href="{{ $paginatedBooks->nextPageUrl() }}" aria-label="Halaman berikutnya">&gt;</a>
                                @else
                                    <span class="disabled" aria-disabled="true" aria-label="Halaman berikutnya">&gt;</span>
                                @endif
                            </nav>
                        @endif
                    @else
                        <p class="book-empty">Buku tidak ditemukan. Coba ubah kata kunci atau filter.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection