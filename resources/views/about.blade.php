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
        <p class="eyebrow">Publikasi Pilihan</p>
        <h2>Kabar &amp; Perjalanan Gerakan Literasi</h2>
        <div class="card-grid two">
            @forelse ($latestPublications ?? [] as $pub)
                <a class="journey-card" href="{{ route('publications.show', $pub->slug) }}" style="display: flex; flex-direction: column; text-decoration: none; color: inherit; cursor: pointer;">
                    <div style="overflow: hidden; background: #f1f5f9;">
                        <img 
                            src="{{ \Illuminate\Support\Str::startsWith($pub->featured_image_path, ['http://', 'https://']) ? $pub->featured_image_path : ($pub->featured_image_path ? asset('assets/images/publications/'.$pub->featured_image_path) : asset('assets/images/about/journey-1.jpg')) }}" 
                            alt="{{ $pub->title }}" 
                            loading="lazy" 
                            decoding="async"
                            style="width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block;"
                        >
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; flex: 1;">
                        <span class="card-label">{{ $pub->category ?? 'Publikasi' }} · {{ \Carbon\Carbon::parse($pub->published_at)->translatedFormat('d F Y') }}</span>
                        <h3>{{ $pub->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($pub->excerpt ?? strip_tags($pub->content), 130) }}</p>
                    </div>
                </a>
            @empty
                <a class="journey-card" href="{{ route('publications.index') }}" style="display: flex; flex-direction: column; text-decoration: none; color: inherit; cursor: pointer;">
                    <div style="overflow: hidden; background: #f1f5f9;">
                        <img src="{{ asset('assets/images/about/journey-1.jpg') }}" alt="Gerobak Angkasa Perpustakaan Keliling" loading="lazy" decoding="async" style="width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block;">
                    </div>
                    <div class="card-body">
                        <span class="card-label">Program Unggulan</span>
                        <h3>Gerobak Angkasa: Perpustakaan Keliling Komunitas</h3>
                        <p>Gerobak Angkasa membawa ratusan buku bacaan anak langsung ke lingkungan tempat tinggal mereka di Tambun Selatan.</p>
                    </div>
                </a>
                <a class="journey-card" href="{{ route('publications.index') }}" style="display: flex; flex-direction: column; text-decoration: none; color: inherit; cursor: pointer;">
                    <div style="overflow: hidden; background: #f1f5f9;">
                        <img src="{{ asset('assets/images/about/journey-2.jpg') }}" alt="Kelas Membaca dan Dokumentasi Relawan" loading="lazy" decoding="async" style="width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block;">
                    </div>
                    <div class="card-body">
                        <span class="card-label">Aktivitas Literasi</span>
                        <h3>Kelas Membaca &amp; Dokumentasi Relawan</h3>
                        <p>Kegiatan membaca bersama dan mendongeng rutin diselenggarakan untuk menghidupkan minat baca anak-anak binaan.</p>
                    </div>
                </a>
            @endforelse
        </div>
    </div>
</section>
@endsection
