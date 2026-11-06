@extends('layouts.app', ['title' => 'Publikasi & Kabar Komunitas'])

@section('content')
<section class="publication-list-section">
    <div class="container">
        <header class="publication-heading">
            <h1>Publikasi &amp; Kabar Komunitas</h1>
            <p>Temukan liputan kegiatan, artikel literasi, publikasi internal, serta liputan media eksternal seputar<br class="desktop-only"> Komunitas Literasi Remaja Tambun Selatan.</p>
            <form class="publication-search" action="{{ route('publications.index') }}" method="GET" role="search">
                <label class="sr-only" for="publication-search">Cari berita</label>
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input id="publication-search" type="search" name="q" value="{{ request('q') }}" placeholder="Cari Berita...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </form>
        </header>

        <form class="publication-filter-bar" action="{{ route('publications.index') }}" method="GET">
            <span>Menampilkan <strong>{{ $articles->total() }}</strong> publikasi</span>
            <label for="publication-category">Jenis publikasi</label>
            <select id="publication-category" name="category" onchange="this.form.submit()">
                <option value="">Semua publikasi</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
        </form>

        @if ($articles->count())
            <div class="publication-grid">
                @foreach ($articles as $article)
                    <a class="publication-card" href="{{ route('publications.show', $article->slug) }}">
                        <img src="{{ $article->featured_image_path ? asset('assets/images/publications/'.$article->featured_image_path) : 'https://placehold.co/600x400?text=No+Image' }}" alt="{{ $article->title }}" loading="lazy" decoding="async">
                        <div class="publication-card-body">
                            <span class="publication-category">{{ $article->category }}</span>
                            <span class="card-date">{{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}</span>
                            <h2>{{ $article->title }}</h2>
                            <p>{{ $article->excerpt }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($articles->lastPage() > 1)
                <nav class="publication-pagination" aria-label="Navigasi halaman publikasi">
                    @if ($articles->currentPage() > 1)
                        <a href="{{ $articles->previousPageUrl() }}" aria-label="Halaman sebelumnya">‹</a>
                    @endif

                    @for ($i = 1; $i <= $articles->lastPage(); $i++)
                        <a class="{{ $i === $articles->currentPage() ? 'current' : '' }}" href="{{ $articles->url($i) }}">{{ $i }}</a>
                    @endfor

                    @if ($articles->hasMorePages())
                        <a class="pagination-next" href="{{ $articles->nextPageUrl() }}" aria-label="Halaman berikutnya">›</a>
                    @endif
                </nav>
            @endif
        @else
            <p class="publication-empty">Berita dengan kata kunci tersebut belum ditemukan.</p>
        @endif
    </div>
</section>
@endsection