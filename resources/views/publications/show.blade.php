@extends('layouts.app', ['title' => 'Semangat 20 Relawan Membina Perpustakaan Desa'])

@section('content')
<section class="publication-detail-section">
    <div class="container">
        <nav class="page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('publications.index') }}">Publication</a>
            <span aria-hidden="true">&gt;</span>
            <span aria-current="page">Detail</span>
        </nav>
        <div class="publication-detail-layout">
            <article class="publication-article">
                <img class="publication-feature-image" src="{{ asset('assets/images/publications/publication-1.jpg') }}" alt="Relawan dan anak-anak dalam kegiatan perpustakaan keliling" fetchpriority="high">
                <div class="publication-share">
                    <span>Bagikan:</span>
                    <div>
                        <a class="share-whatsapp" href="https://wa.me/?text={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                        <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i> Facebook</a>
                        <a class="share-instagram" href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagram</a>
                        <button class="share-copy" type="button" data-copy-link><i class="fa-solid fa-link" aria-hidden="true"></i> Copy Link</button>
                    </div>
                    <span class="copy-feedback" aria-live="polite"></span>
                </div>
                <h1>Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan</h1>
                <p class="publication-article-date"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Kamis, 20 Agustus 2026</p>
                <div class="publication-article-copy">
                    <p>Gerobak Angkasa merupakan perpustakaan keliling yang menjadi ujung tombak pergerakan Komunitas Literasi Remaja Tambun Selatan. Gerobak ini membawa ratusan buku bacaan anak langsung ke lingkungan tempat tinggal anak-anak di 6 desa dan 1 kelurahan.</p>
                    <p>Dipandu oleh 20 sukarelawan aktif, kegiatan Gerobak Angkasa tidak hanya menyajikan buku, namun juga menghidupkan ruang membaca bersama, pendampingan belajar, dan cerita gembira bagi lebih dari 400 anak-anak yang telah merasakan dampak positifnya.</p>
                    <p>Melalui dua perpustakaan desa binaan, para relawan terus menjaga semangat belajar dan memastikan setiap anak dapat menemukan bacaan yang menyenangkan dan bermakna.</p>
                </div>
            </article>
            <aside class="publication-sidebar">
                <section class="sidebar-box">
                    <h2>Pencarian</h2>
                    <form action="{{ route('publications.index') }}" method="GET" role="search">
                        <label class="sr-only" for="detail-search">Cari kata kunci</label>
                        <input id="detail-search" type="search" name="q" placeholder="Kata Kunci ...">
                    </form>
                </section>
                <section class="sidebar-box recent-posts">
                    <h2>Berita Terbaru</h2>
                    @foreach ([
                        ['publication-1.jpg', 'Semangat 20 Relawan Membina 2 Perpustakaan Desa', 'Kamis, 20 Agustus 2026'],
                        ['publication-2.jpg', 'Semangat Membaca Anak-anak bersama Gerobak Angkasa', 'Sabtu, 15 Agustus 2026'],
                        ['publication-1.jpg', 'Kegiatan Literasi Bersama Relawan Tambun Selatan', 'Senin, 10 Agustus 2026'],
                        ['publication-3.jpg', 'Gerobak Angkasa Hadirkan Perpustakaan Keliling', 'Selasa, 4 Agustus 2026'],
                    ] as [$image, $title, $date])
                        <a class="recent-post" href="{{ route('publications.show') }}">
                            <img src="{{ asset('assets/images/publications/'.$image) }}" alt="" loading="lazy" decoding="async">
                            <span><strong>{{ $title }}</strong><small>{{ $date }}</small></span>
                        </a>
                    @endforeach
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection
