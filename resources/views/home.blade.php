@extends('layouts.app')

@section('content')
<section class="hero hero-home">
    <img class="hero-image" src="{{ asset('assets/images/home/hero.jpg') }}" alt="" fetchpriority="high">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1>Komunitas Literasi Remaja Tambun Selatan</h1>
        <p>Fokus Kami adalah menyebarluaskan akses buku bacaan anak<br class="desktop-only">
            secara gratis untuk membantu meningkatkan literasi dan kepedulian<br class="desktop-only">
            generasi muda di sekitar kita.</p>
        <div class="hero-actions">
            <a class="btn btn-light" href="{{ url('/about') }}">About Us</a>
            <a class="btn btn-outline-light" href="{{ route('join-us') }}">Join Us</a>
        </div>
    </div>
</section>

<section class="section stats-section">
    <div class="container narrow">
        <p class="eyebrow">Jejak &amp; Capaian Nyata</p>
        <h2>Data Komunitas Berdasarkan Fakta Lapangan</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <img src="{{ asset('assets/images/media-partner/icon-park--positive-dynamics.svg') }}" alt="Berdampak Positif ke" class="stat-svg-icon" width="26" height="26">
                </div>
                <div class="stat-number">
                    <span class="stat-prefix" aria-hidden="true">&ge;</span><span data-count-to="{{ $settings['stats']['impact_number'] ?? 400 }}">0</span>
                    <span class="sr-only">Lebih dari atau sama dengan 400</span>
                </div>
                <div class="stat-label">Berdampak Positif ke</div>
                <div class="stat-desc">Anak</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <img src="{{ asset('assets/images/media-partner/andrsrxn--library.svg') }}" alt="Koleksi Bacaan" class="stat-svg-icon" width="26" height="26">
                </div>
                <div class="stat-number">
                    <span class="stat-prefix" aria-hidden="true">&plusmn;</span><span data-count-to="{{ $settings['stats']['book_number'] ?? 500 }}">0</span>
                    <span class="sr-only">Sekitar 500</span>
                </div>
                <div class="stat-label">Koleksi Bacaan</div>
                <div class="stat-desc">Buku</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <img src="{{ asset('assets/images/media-partner/boxicons--community-filled.svg') }}" alt="Sukarelawan Aktif" class="stat-svg-icon" width="26" height="26">
                </div>
                <div class="stat-number">
                    <span data-count-to="{{ $settings['stats']['volunteers_number'] ?? 20 }}">0</span>
                </div>
                <div class="stat-label">Sukarelawan Aktif</div>
                <div class="stat-desc">Remaja</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <img src="{{ asset('assets/images/media-partner/cil--library-building.svg') }}" alt="Perpustakaan Desa" class="stat-svg-icon" width="26" height="26">
                </div>
                <div class="stat-number">
                    <span data-count-to="{{ $settings['stats']['libraries_number'] ?? 2 }}">0</span>
                </div>
                <div class="stat-label">Perpustakaan Desa</div>
                <div class="stat-desc">Lokasi Binaan</div>
            </div>
        </div>
    </div>
</section>

<section class="section partner-section" id="media-partner">
    <div class="container">
        <h2>Media Partner</h2>
        <div class="partner-carousel" data-partner-carousel role="region" aria-roledescription="carousel" aria-label="Logo media partner">
            <button class="carousel-nav-btn carousel-arrow" type="button" aria-label="Mitra sebelumnya" data-partner-direction="previous">
                <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div class="partner-viewport" data-partner-viewport>
                <div class="partner-track" data-partner-track>
                    <div class="partner-set" data-partner-set>
                        @foreach ($mediaPartners ?? \App\Http\Controllers\MediaPartnerController::getPartners() as $partner)
                            <div class="partner-card partner-logo">
                                <img src="{{ asset('assets/images/partners/'.$partner['logo']) }}" alt="{{ $partner['name'] }}" title="{{ $partner['name'] }}" loading="lazy" decoding="async">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button class="carousel-nav-btn carousel-arrow" type="button" aria-label="Mitra berikutnya" data-partner-direction="next">
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>

<section class="section publication-section" id="publication">
    <div class="container">
        <h2>Publikasi &amp; Kabar Kegiatan</h2>
        <p class="section-subtitle">Dokumentasi perjalanan, artikel edukatif, dan liputan berita komunitas.</p>
        <div class="card-grid three">
            @forelse ($latestPublications ?? [] as $pub)
                <a class="content-card publication-card" href="{{ route('publications.show', $pub->slug) }}" aria-label="Baca berita: {{ $pub->title }}">
                    <img src="{{ \Illuminate\Support\Str::startsWith($pub->featured_image_path, ['http://', 'https://']) ? $pub->featured_image_path : ($pub->featured_image_path ? asset('assets/images/publications/'.$pub->featured_image_path) : 'https://placehold.co/600x400?text=No+Image') }}" alt="{{ $pub->title }}" loading="lazy" decoding="async">
                    <div class="card-body">
                        <span class="card-date">{{ \Carbon\Carbon::parse($pub->published_at)->translatedFormat('l, d F Y') }}</span>
                        <h3>{{ $pub->title }}</h3>
                        <p>{{ $pub->excerpt }}</p>
                    </div>
                </a>
            @empty
                <p>Belum ada publikasi terbaru.</p>
            @endforelse
        </div>
        <div class="center-button"><a class="btn btn-primary" href="{{ route('publications.index') }}">Publikasi →</a></div>
    </div>
</section>

<section class="section library-section" id="digital-library">
    <div class="container">
        <h2>Koleksi Buku Terkini</h2>
        <p class="section-subtitle">Pilihan buku terbaru yang baru ditambahkan oleh komunitas.</p>
        <div class="card-grid four">
            @forelse ($latestBooks ?? [] as $book)
                <article class="book-card">
                    <div class="book-cover-wrap">
                        <img src="{{ \Illuminate\Support\Str::startsWith($book->cover_path, ['http://', 'https://']) ? $book->cover_path : ($book->cover_path ? asset('assets/images/books/'.$book->cover_path) : 'https://placehold.co/400x600?text=No+Cover') }}" alt="Sampul buku {{ $book->title }}" loading="lazy" decoding="async">
                    </div>
                    <div class="book-body">
                        <span class="book-title" title="{{ $book->title }}">{{ $book->title }}</span>
                        <strong class="book-author" title="{{ $book->author->name }}">{{ $book->author->name }}</strong>
                        <small class="book-genre">{{ $book->genre->name }}</small>
                        <a class="book-detail-link" href="{{ route('library.index', ['book' => $book->title]) }}">
                            <span>Lihat Detail</span>
                            <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @empty
                <p>Belum ada koleksi buku terbaru.</p>
            @endforelse
        </div>
        <div class="center-button"><a class="btn btn-primary" href="{{ route('library.index') }}">Digital Library →</a></div>
    </div>
</section>
@endsection
