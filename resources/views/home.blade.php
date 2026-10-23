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
            <div class="stat-item"><span>Ber-dampak Positif ke</span><strong><span class="stat-prefix" aria-hidden="true">≥</span><span data-count-to="400">0</span><span class="sr-only">Lebih dari atau sama dengan 400</span></strong><small>Anak</small></div>
            <div class="stat-item"><span>Koleksi Bacaan</span><strong><span class="stat-prefix" aria-hidden="true">±</span><span data-count-to="500">0</span><span class="sr-only">Sekitar 500</span></strong><small>Buku</small></div>
            <div class="stat-item"><span>Sukarelawan Aktif</span><strong data-count-to="20">0</strong><small>Remaja</small></div>
            <div class="stat-item"><span>Perpustakaan Desa</span><strong data-count-to="2">0</strong><small>Lokasi Binaan</small></div>
        </div>
    </div>
</section>

<section class="section partner-section" id="media-partner">
    <div class="container">
        <h2>Media Partner</h2>
        <div class="partner-carousel" data-partner-carousel role="region" aria-roledescription="carousel" aria-label="Logo media partner">
            <button class="carousel-arrow" type="button" aria-label="Mitra sebelumnya" data-partner-direction="previous">‹</button>
            <div class="partner-viewport" data-partner-viewport>
                <div class="partner-track" data-partner-track>
                    <div class="partner-set" data-partner-set>
                        <div class="partner-logo"><img src="{{ asset('assets/images/partners/kementrian.jpg') }}" alt="Kementerian Pendidikan" loading="lazy" decoding="async"></div>
                        <div class="partner-logo"><img src="{{ asset('assets/images/partners/ugm.png') }}" alt="Universitas Gadjah Mada" loading="lazy" decoding="async"></div>
                        <div class="partner-logo"><img src="{{ asset('assets/images/partners/bri-peduli.png') }}" alt="BRI Peduli" loading="lazy" decoding="async"></div>
                        <div class="partner-logo"><img src="{{ asset('assets/images/partners/kmtedi.jpg') }}" alt="KMTEDI" loading="lazy" decoding="async"></div>
                    </div>
                </div>
            </div>
            <button class="carousel-arrow" type="button" aria-label="Mitra berikutnya" data-partner-direction="next">›</button>
        </div>
    </div>
</section>

<section class="section publication-section" id="publication">
    <div class="container">
        <h2>Publikasi &amp; Kabar Kegiatan</h2>
        <p class="section-subtitle">Dokumentasi perjalanan, artikel edukatif, dan liputan berita komunitas.</p>
        <div class="card-grid three">
            @foreach ([
                ['img'=>'publication-1.jpg','date'=>'Kamis, 20 Agustus 2026','title'=>'Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan','text'=>'Tambun Selatan – Memiliki visi bersama dalam memajukan literasi, 20 relawan Komunitas Literasi Remaja Tambun Selatan secara aktif mendampingi....'],
                ['img'=>'publication-2.jpg','date'=>'Sabtu, 15 Agustus 2026','title'=>'Penyaluran Gerobak Angkasa: Menjangkau Pelosok Tambun Selatan dengan Buku','text'=>'Tambun Selatan – Dalam rangka memperluas akses membaca bagi anak-anak, Komunitas Literasi Remaja Tambun Selatan secara resmi mengoperasikan Gerobak Angkasa sebagai perpustakaan keliling....'],
                ['img'=>'publication-3.jpg','date'=>'Selasa, 25 Agustus 2026','title'=>'Liputan Media Nasional: Gerakan Nyata Literasi Berdampak......','text'=>'Kabar Literasi – Gerakan membaca yang diinisiasi oleh Komunitas Literasi Remaja Tambun Selatan mendapat apresiasi dari pemerintah pendidikan nasional......'],
            ] as $item)
                <a class="content-card publication-card" href="{{ route('publications.show') }}" aria-label="Baca berita: {{ $item['title'] }}">
                    <img src="{{ asset('assets/images/publications/'.$item['img']) }}" alt="" loading="lazy" decoding="async">
                    <div class="card-body">
                        <span class="card-date">{{ $item['date'] }}</span>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="center-button"><a class="btn btn-primary" href="#">Publikasi →</a></div>
    </div>
</section>

<section class="section library-section" id="digital-library">
    <div class="container">
        <h2>Koleksi Buku Terkini</h2>
        <p class="section-subtitle">Pilihan buku terbaru yang baru ditambahkan oleh komunitas.</p>
        <div class="card-grid four">
            @foreach ([
                ['img'=>'book-3.png','title'=>'Quarter-Life Crisis','author'=>'Maudy Ayunda','type'=>'Pengembangan Diri'],
                ['img'=>'book-1.jpg','title'=>'Negeri 5 Menara','author'=>'Ahmad Fuadi','type'=>'Novel'],
                ['img'=>'book-2.png','title'=>'Educated','author'=>'Tara Westover','type'=>'Memoar'],
                ['img'=>'book-3.png','title'=>'Quarter-Life Crisis','author'=>'Maudy Ayunda','type'=>'Pengembangan Diri'],
            ] as $book)
                <article class="book-card">
                    <img src="{{ asset('assets/images/books/'.$book['img']) }}" alt="" loading="lazy" decoding="async">
                    <div class="book-body">
                        <span>{{ $book['title'] }}</span>
                        <strong>{{ $book['author'] }}</strong>
                        <small>{{ $book['type'] }}</small>
                        <a href="{{ route('library.index', ['book' => $book['title']]) }}">Lihat Detail</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="center-button"><a class="btn btn-primary" href="{{ route('library.index') }}">Digital Library →</a></div>
    </div>
</section>
@endsection
