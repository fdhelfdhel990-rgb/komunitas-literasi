@extends('layouts.app')

@section('content')
<section class="hero hero-about">
    <img class="hero-image" src="{{ asset('assets/images/about/hero.jpg') }}" alt="" fetchpriority="high">
    <div class="hero-overlay"></div>
    <div class="container hero-content about-hero-content">
        <h1>Profil &amp; Perjalanan Komunitas</h1>
        <p>Komunitas Literasi Remaja Tambun Selatan berdiri sejak 3 Oktober 2021 di<br class="desktop-only">
            wilayah Tambun Selatan, Bekasi.</p>
        <p>Komunitas berawal dari kepedulian sekumpulan remaja terhadap<br class="desktop-only">
            permasalahan pendidikan di sekitar mereka dan menjalankan aktivitas<br class="desktop-only">
            nyata melalui perpustakaan keliling.</p>
        <p>Fokus utama komunitas adalah menyebarluaskan akses buku bacaan anak<br class="desktop-only">
            untuk membantu meningkatkan literasi.</p>
    </div>
</section>

<section class="section vision-section">
    <div class="container two-column">
        <div>
            <h2 class="small-heading">Visi</h2>
            <p class="vision-copy">Menyebarluaskan akses buku bacaan anak secara gratis dan memberikan pengaruh bagi remaja sekitar untuk turut peduli terhadap peningkatan literasi di Indonesia.</p>
        </div>
        <div>
            <h2 class="small-heading">Misi</h2>
            <ul class="mission-list">
                <li>Menyediakan akses bahan bacaan yang beragam dan berkualitas bagi masyarakat.</li>
                <li>Mendorong partisipasi aktif remaja dalam menyebarkan pentingnya literasi.</li>
                <li>Membangun jaringan dengan komunitas literasi lain.</li>
                <li>Berkolaborasi dengan berbagai pihak untuk kegiatan literasi.</li>
            </ul>
        </div>
    </div>
</section>

<section class="section journey-section">
    <div class="container">
        <p class="eyebrow">Perjalanan Kami</p>
        <h2>Gerakan yang tumbuh dari kepedulian</h2>
        <div class="card-grid two">
            <article class="journey-card">
                <img src="{{ asset('assets/images/about/journey-1.jpg') }}" alt="" loading="lazy" decoding="async">
                <div class="card-body">
                    <span class="card-label">Program</span>
                    <h3>Gerobak Angkasa: Perpustakaan Keliling Komunitas</h3>
                    <p>Gerobak Angkasa membawa ratusan buku bacaan anak langsung ke lingkungan tempat tinggal mereka di enam desa dan satu kelurahan.</p>
                    <a class="btn btn-primary" href="{{ route('join-us') }}">Bergabung mendampingi →</a>
                </div>
            </article>
            <article class="journey-card">
                <img src="{{ asset('assets/images/about/journey-2.jpg') }}" alt="" loading="lazy" decoding="async">
                <div class="card-body">
                    <span class="card-label">Kegiatan</span>
                    <h3>Gerobak Angkasa: Perpustakaan Keliling Komunitas</h3>
                    <p>Dipandu oleh 20 relawan aktif, kegiatan membaca bersama turut menghidupkan ruang belajar bagi lebih dari 400 anak.</p>
                    <a class="btn btn-primary" href="#publication">Lihat kegiatan →</a>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
