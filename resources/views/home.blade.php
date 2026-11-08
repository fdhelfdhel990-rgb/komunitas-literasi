@extends('layouts.app')

@section('content')
<!-- 1. Hero Section -->
<section class="hero hero-home">
    <img class="hero-image" src="{{ asset('assets/images/home/hero.jpg') }}" alt="" fetchpriority="high">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <div class="hero-badge-wrap">
            <span class="hero-badge">
                <i class="fa-solid fa-sparkles text-amber-300 mr-1.5" aria-hidden="true"></i>
                Gerakan Literasi Anak &amp; Remaja
            </span>
        </div>
        <h1>Komunitas Literasi Remaja Tambun Selatan</h1>
        <p>
            Membuka dan menyebarluaskan akses buku bacaan anak berkualitas secara gratis untuk menumbuhkan minat baca dan membangun kepedulian generasi muda di lingkungan sekitar.
        </p>
        <div class="hero-actions">
            <a class="btn btn-light" href="{{ url('/about') }}">
                <i class="fa-solid fa-circle-info mr-2" aria-hidden="true"></i>
                <span>Tentang Kami</span>
            </a>
            <a class="btn btn-outline-light" href="{{ route('join-us') }}">
                <i class="fa-solid fa-hand-holding-heart mr-2" aria-hidden="true"></i>
                <span>Gabung Relawan</span>
            </a>
        </div>
    </div>
</section>

<!-- 2. Stats / Dampak Section -->
<section class="section stats-section">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">
                <i class="fa-solid fa-chart-line mr-1.5" aria-hidden="true"></i>
                Jejak &amp; Capaian Nyata
            </span>
            <h2>Data Gerakan Literasi di Lapangan</h2>
            <p class="section-subtitle">
                Hasil komitmen nyata para relawan muda dalam mendampingi anak-anak dan menghidupkan ruang baca bersama di Tambun Selatan.
            </p>
        </div>

        <div class="stats-grid">
            <!-- Stat 1 -->
            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <i class="fa-solid fa-children" aria-hidden="true"></i>
                </div>
                <div class="stat-number">
                    <span class="stat-prefix" aria-hidden="true">≥</span><span data-count-to="400">0</span><span class="sr-only">Lebih dari atau sama dengan 400</span>
                </div>
                <div class="stat-label">Anak Terdampak Positif</div>
                <div class="stat-desc">Didampingi membaca &amp; belajar secara gratis</div>
            </div>

            <!-- Stat 2 -->
            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <i class="fa-solid fa-book-bookmark" aria-hidden="true"></i>
                </div>
                <div class="stat-number">
                    <span class="stat-prefix" aria-hidden="true">±</span><span data-count-to="500">0</span><span class="sr-only">Sekitar 500</span>
                </div>
                <div class="stat-label">Koleksi Buku Bacaan</div>
                <div class="stat-desc">Buku cerita bergambar, novel, &amp; edukasi</div>
            </div>

            <!-- Stat 3 -->
            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <i class="fa-solid fa-heart-pulse" aria-hidden="true"></i>
                </div>
                <div class="stat-number">
                    <span data-count-to="20">0</span>
                </div>
                <div class="stat-label">Sukarelawan Aktif</div>
                <div class="stat-desc">Remaja penggerak kegiatan lapangan</div>
            </div>

            <!-- Stat 4 -->
            <div class="stat-card">
                <div class="stat-icon-wrap">
                    <i class="fa-solid fa-house-chimney-window" aria-hidden="true"></i>
                </div>
                <div class="stat-number">
                    <span data-count-to="2">0</span>
                </div>
                <div class="stat-label">Perpustakaan Desa</div>
                <div class="stat-desc">Titik ruang baca binaan komunitas</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Media Partner Section -->
<section class="section partner-section" id="media-partner">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">
                <i class="fa-solid fa-handshake mr-1.5" aria-hidden="true"></i>
                Kolaborasi &amp; Kemitraan
            </span>
            <h2>Didukung Mitra Terpercaya</h2>
            <p class="section-subtitle">
                Bersinergi bersama institusi pendidikan, kementerian, dan mitra sosial untuk memperluas jangkauan literasi anak.
            </p>
        </div>

        <div class="partner-carousel" data-partner-carousel role="region" aria-roledescription="carousel" aria-label="Logo media partner">
            <button class="carousel-arrow carousel-nav-btn" type="button" aria-label="Mitra sebelumnya" data-partner-direction="previous">
                <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div class="partner-viewport" data-partner-viewport>
                <div class="partner-track" data-partner-track>
                    <div class="partner-set" data-partner-set>
                        <div class="partner-card">
                            <img src="{{ asset('assets/images/partners/kementrian.jpg') }}" alt="Kementerian Pendidikan" loading="lazy" decoding="async">
                        </div>
                        <div class="partner-card">
                            <img src="{{ asset('assets/images/partners/ugm.png') }}" alt="Universitas Gadjah Mada" loading="lazy" decoding="async">
                        </div>
                        <div class="partner-card">
                            <img src="{{ asset('assets/images/partners/bri-peduli.png') }}" alt="BRI Peduli" loading="lazy" decoding="async">
                        </div>
                        <div class="partner-card">
                            <img src="{{ asset('assets/images/partners/kmtedi.jpg') }}" alt="KMTEDI" loading="lazy" decoding="async">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-arrow carousel-nav-btn" type="button" aria-label="Mitra berikutnya" data-partner-direction="next">
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>

<!-- 4. Publication Section -->
<section class="section publication-section" id="publication">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">
                <i class="fa-solid fa-newspaper mr-1.5" aria-hidden="true"></i>
                Kabar &amp; Dokumentasi
            </span>
            <h2>Publikasi &amp; Kabar Kegiatan</h2>
            <p class="section-subtitle">
                Dokumentasi perjalanan relawan, liputan media nasional, dan artikel edukatif seputar literasi anak.
            </p>
        </div>

        <div class="card-grid three">
            @php
                $homepagePublications = [
                    [
                        'img' => 'publication-1.jpg',
                        'category' => 'Sosial',
                        'date' => '20 Agustus 2026',
                        'title' => 'Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan',
                        'text' => 'Memiliki visi bersama dalam memajukan literasi, 20 relawan komunitas secara aktif mendampingi ruang baca desa.',
                    ],
                    [
                        'img' => 'publication-2.jpg',
                        'category' => 'Pendidikan',
                        'date' => '15 Agustus 2026',
                        'title' => 'Penyaluran Gerobak Angkasa: Menjangkau Pelosok Tambun Selatan dengan Buku',
                        'text' => 'Memperluas akses membaca bagi anak-anak melalui perpustakaan keliling Gerobak Angkasa yang ramah dan bergerak ke pelosok.',
                    ],
                    [
                        'img' => 'publication-3.jpg',
                        'category' => 'Liputan Media',
                        'date' => '25 Agustus 2026',
                        'title' => 'Liputan Media Nasional: Gerakan Nyata Literasi Berdampak Positif Bagi Anak',
                        'text' => 'Gerakan membaca yang diinisiasi pemuda Tambun Selatan mendapat apresiasi luas dari pemerintah dan komunitas literasi nasional.',
                    ],
                ];
            @endphp

            @foreach ($homepagePublications as $item)
                <a class="content-card publication-card" href="{{ route('publications.show') }}" aria-label="Baca berita: {{ $item['title'] }}">
                    <div class="card-image-wrap">
                        <img src="{{ asset('assets/images/publications/'.$item['img']) }}" alt="" loading="lazy" decoding="async">
                        <span class="card-badge-pill">{{ $item['category'] }}</span>
                    </div>
                    <div class="card-body">
                        <span class="card-date">
                            <i class="fa-regular fa-calendar-days mr-1 text-slate-400" aria-hidden="true"></i>
                            {{ $item['date'] }}
                        </span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                        <span class="card-action-link">
                            <span>Baca Selengkapnya</span>
                            <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="center-button">
            <a class="btn btn-primary" href="{{ route('publications.index') }}">
                <span>Lihat Semua Publikasi</span>
                <i class="fa-solid fa-arrow-right text-xs ml-2" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>

<!-- 5. Digital Library Section -->
<section class="section library-section" id="digital-library">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">
                <i class="fa-solid fa-book-open-reader mr-1.5" aria-hidden="true"></i>
                Ruang Baca Digital
            </span>
            <h2>Koleksi Buku Bacaan Terkini</h2>
            <p class="section-subtitle">
                Ragam buku cerita edukatif, novel inspiratif, dan memoar yang siap dibaca secara gratis oleh anak dan remaja.
            </p>
        </div>

        @php
            // Mengambil buku dari controller/database bila ada, dengan fallback yang rapi
            $displayBooks = (isset($recentBooks) && $recentBooks->count() > 0) 
                ? $recentBooks 
                : collect([
                    (object)['cover_path' => 'book-1.jpg', 'title' => 'Negeri 5 Menara', 'author' => (object)['name' => 'Ahmad Fuadi'], 'genre' => (object)['name' => 'Novel']],
                    (object)['cover_path' => 'book-2.png', 'title' => 'Educated', 'author' => (object)['name' => 'Tara Westover'], 'genre' => (object)['name' => 'Memoar']],
                    (object)['cover_path' => 'book-3.png', 'title' => 'Quarter-Life Crisis', 'author' => (object)['name' => 'Maudy Ayunda'], 'genre' => (object)['name' => 'Pengembangan Diri']],
                    (object)['cover_path' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Perjalanan Impian', 'author' => (object)['name' => 'Ahmad Fuadi'], 'genre' => (object)['name' => 'Novel']],
                ]);
        @endphp

        <div class="card-grid four">
            @foreach ($displayBooks as $book)
                <article class="book-card">
                    <div class="book-cover-container">
                        <img 
                            src="{{ !empty($book->cover_path) ? asset('assets/images/books/'.$book->cover_path) : 'https://placehold.co/400x600?text=No+Cover' }}" 
                            alt="Sampul buku {{ $book->title }}" 
                            loading="lazy" 
                            decoding="async"
                        >
                    </div>
                    <div class="book-body">
                        <span class="book-genre-badge">{{ $book->genre->name ?? 'Bacaan' }}</span>
                        <h3 class="book-title">{{ $book->title }}</h3>
                        <p class="book-author">
                            <i class="fa-regular fa-user text-slate-400 mr-1.5" aria-hidden="true"></i>
                            {{ $book->author->name ?? 'Penulis Komunitas' }}
                        </p>
                        <a class="book-action-link" href="{{ route('library.index', ['book' => $book->title]) }}">
                            <span>Lihat Detail</span>
                            <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="center-button">
            <a class="btn btn-primary" href="{{ route('library.index') }}">
                <span>Jelajahi Digital Library</span>
                <i class="fa-solid fa-arrow-right text-xs ml-2" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>
@endsection
