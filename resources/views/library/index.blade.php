@extends('layouts.app', ['title' => 'Digital Library'])

@section('content')
<section class="library-page-section">
    <div class="container">
        @php
            $books = [
                ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah persahabatan dan perjuangan enam santri dalam mengejar cita-cita. Sebuah cerita tentang pendidikan, ketekunan, dan keyakinan bahwa impian dapat membawa seseorang melampaui batas.'],
                ['image' => 'book-2.png', 'title' => 'Educated', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar tentang perjalanan seorang perempuan mencari pendidikan dan membangun pemahaman baru tentang dirinya serta dunia di sekitarnya.'],
                ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Buku pengembangan diri yang mengajak pembaca memahami masa transisi dewasa muda dan menyusun langkah dengan lebih sadar.'],
                ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Perjalanan Impian', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Novel inspiratif tentang persahabatan, pendidikan, dan semangat untuk berusaha meraih cita-cita.'],
                ['image' => 'book-2.png', 'title' => 'Educated: Sebuah Memoar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar mengenai kekuatan pendidikan dan perjalanan menemukan jati diri.'],
                ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Panduan Bertumbuh', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Panduan reflektif untuk menghadapi perubahan dan pilihan pada masa dewasa muda.'],
                ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Edisi Komunitas', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah tentang belajar, hidup bersama, dan keyakinan yang menguatkan perjalanan meraih masa depan.'],
                ['image' => 'book-2.png', 'title' => 'Educated: Perjalanan Belajar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Cerita perjalanan belajar yang membuka perspektif dan memberi ruang untuk bertumbuh.'],
                ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Mengenal Diri', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Bacaan tentang mengenali nilai diri dan membuat keputusan dengan lebih terarah.'],
            ];
            $search = mb_strtolower((string) request('q'));
            $genre = (string) request('genre', '');
            $publisher = (string) request('publisher', '');
            $filteredBooks = array_values(array_filter($books, fn ($book) => ($search === '' || str_contains(mb_strtolower($book['title'].' '.$book['author']), $search)) && ($genre === '' || $book['genre'] === $genre) && ($publisher === '' || $book['publisher'] === $publisher)));
            $sortOrder = (string) request('sort', 'az');
            if ($sortOrder === 'az') {
                usort($filteredBooks, fn ($firstBook, $secondBook) => strcasecmp($firstBook['title'], $secondBook['title']));
            } elseif ($sortOrder === 'za') {
                usort($filteredBooks, fn ($firstBook, $secondBook) => strcasecmp($secondBook['title'], $firstBook['title']));
            }
            $booksPerPage = 6;
            $totalPages = max(1, (int) ceil(count($filteredBooks) / $booksPerPage));
            $currentPage = min(max((int) request('page', 1), 1), $totalPages);
            $paginatedBooks = array_slice($filteredBooks, ($currentPage - 1) * $booksPerPage, $booksPerPage);
            $selectedBook = collect($books)->firstWhere('title', request('book'));
            $genres = collect($books)->pluck('genre')->unique()->values();
        @endphp

        @if ($selectedBook)
            <nav class="page-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('library.index') }}">Digital Library</a>
                <span aria-hidden="true">&gt;</span>
                <span aria-current="page">Detail</span>
            </nav>
        @endif

        @if ($selectedBook)
            <article class="book-detail-card">
                <img src="{{ asset('assets/images/books/'.$selectedBook['image']) }}" alt="Sampul buku {{ $selectedBook['title'] }}">
                <div class="book-detail-copy">
                    <h1>{{ $selectedBook['title'] }}</h1>
                    <dl class="book-detail-facts">
                        <div><dt>Penulis</dt><dd><i class="fa-regular fa-user" aria-hidden="true"></i> {{ $selectedBook['author'] }}</dd></div>
                        <div><dt>Penerbit</dt><dd><i class="fa-solid fa-building" aria-hidden="true"></i> {{ $selectedBook['publisher'] }}</dd></div>
                        <div><dt>Genre</dt><dd>{{ $selectedBook['genre'] }}</dd></div>
                        <div><dt>Kondisi Fisik</dt><dd>Baik</dd></div>
                    </dl>
                    <h2>Deskripsi Ringkasan Buku</h2>
                    <p>{{ $selectedBook['description'] }}</p>
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
                            <label class="book-radio"><input type="radio" name="genre" value="" @checked($genre === '') onchange="this.form.submit()"><span>Semua</span></label>
                            @foreach ($genres as $bookGenre)
                                <label class="book-radio"><input type="radio" name="genre" value="{{ $bookGenre }}" @checked($genre === $bookGenre) onchange="this.form.submit()"><span>{{ $bookGenre }}</span></label>
                            @endforeach
                        </fieldset>
                        <label class="book-filter-label" for="book-publisher">Penerbit</label>
                        <select id="book-publisher" name="publisher" onchange="this.form.submit()">
                            <option value="">Semua penerbit</option>
                            <option value="Gramedia" @selected($publisher === 'Gramedia')>Gramedia</option>
                            <option value="Mizan" @selected($publisher === 'Mizan')>Mizan</option>
                        </select>
                    </form>
                </aside>

                <div class="book-catalog-content">
                    @if (count($filteredBooks))
                        <div class="book-catalog-grid">
                            @foreach ($paginatedBooks as $book)
                                <article class="book-catalog-card">
                                    <img src="{{ asset('assets/images/books/'.$book['image']) }}" alt="Sampul buku {{ $book['title'] }}" loading="lazy" decoding="async">
                                    <div class="book-catalog-body">
                                        <h2>{{ $book['title'] }}</h2>
                                        <p><i class="fa-regular fa-user" aria-hidden="true"></i> {{ $book['author'] }}</p>
                                        <p><i class="fa-solid fa-book-open" aria-hidden="true"></i> {{ $book['genre'] }}</p>
                                        <a href="{{ route('library.index', array_merge(request()->query(), ['book' => $book['title']])) }}">Lihat Detail <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        @if ($totalPages > 1)
                            <nav class="book-pagination" aria-label="Navigasi halaman katalog buku">
                                @if ($currentPage > 1)
                                    <a href="{{ route('library.index', array_merge(request()->except('page'), ['page' => $currentPage - 1])) }}" aria-label="Halaman sebelumnya">&lt;</a>
                                @else
                                    <span class="disabled" aria-disabled="true" aria-label="Halaman sebelumnya">&lt;</span>
                                @endif
                                @for ($page = 1; $page <= $totalPages; $page++)
                                    @if ($page === $currentPage)
                                        <span class="current" aria-current="page">{{ $page }}</span>
                                    @else
                                        <a href="{{ route('library.index', array_merge(request()->except('page'), ['page' => $page])) }}" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                                    @endif
                                @endfor
                                @if ($currentPage < $totalPages)
                                    <a href="{{ route('library.index', array_merge(request()->except('page'), ['page' => $currentPage + 1])) }}" aria-label="Halaman berikutnya">&gt;</a>
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
