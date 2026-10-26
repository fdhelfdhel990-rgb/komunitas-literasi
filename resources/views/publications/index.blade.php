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

        @php
            $articles = [
                ['image' => 'publication-1.jpg', 'category' => 'Sosial', 'date' => '05 Oktober 2026', 'title' => 'Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan', 'summary' => 'Tambun Selatan - Memiliki visi bersama dalam memajukan literasi, 20 relawan Komunitas Literasi Remaja Tambun Selatan secara aktif mendampingi....'],
                ['image' => 'publication-2.jpg', 'category' => 'Pendidikan', 'date' => '15 Agustus 2026', 'title' => 'Semangat Membaca Anak-anak bersama Gerobak Angkasa', 'summary' => 'Relawan mengajak anak-anak menikmati bacaan dan belajar bersama melalui perpustakaan keliling Gerobak Angkasa....'],
                ['image' => 'publication-3.jpg', 'category' => 'Sosial', 'date' => '25 Agustus 2026', 'title' => 'Gerobak Angkasa Hadirkan Perpustakaan Keliling', 'summary' => 'Koleksi buku dan ruang membaca kini hadir lebih dekat dengan anak-anak di berbagai desa Tambun Selatan....'],
                ['image' => 'publication-1.jpg', 'category' => 'Pendidikan', 'date' => '12 Agustus 2026', 'title' => 'Ruang Baca Bersama Tumbuhkan Minat Literasi Anak', 'summary' => 'Relawan mendampingi anak-anak mengenal buku dan menikmati kegiatan membaca bersama di lingkungan mereka....'],
                ['image' => 'publication-2.jpg', 'category' => 'Sosial', 'date' => '08 Agustus 2026', 'title' => 'Relawan Hadir Membuka Akses Bacaan di Desa', 'summary' => 'Kegiatan komunitas mendekatkan bacaan dan pendampingan belajar kepada lebih banyak keluarga....'],
                ['image' => 'publication-3.jpg', 'category' => 'Pendidikan', 'date' => '02 Agustus 2026', 'title' => 'Perpustakaan Keliling Menjangkau Pembaca Muda', 'summary' => 'Gerobak Angkasa menjadi ruang belajar bergerak yang menyenangkan bagi anak-anak Tambun Selatan....'],
                ['image' => 'publication-1.jpg', 'category' => 'Sosial', 'date' => '28 Juli 2026', 'title' => 'Kolaborasi Warga dan Relawan untuk Literasi', 'summary' => 'Dukungan warga membantu kegiatan membaca tumbuh menjadi kebiasaan bersama di lingkungan desa....'],
                ['image' => 'publication-2.jpg', 'category' => 'Pendidikan', 'date' => '21 Juli 2026', 'title' => 'Belajar dan Bercerita Bersama Gerobak Angkasa', 'summary' => 'Sesi membaca dan bercerita memberi anak-anak kesempatan belajar dalam suasana akrab....'],
                ['image' => 'publication-3.jpg', 'category' => 'Sosial', 'date' => '14 Juli 2026', 'title' => 'Gerakan Literasi dari Tambun Selatan', 'summary' => 'Komunitas terus mengajak remaja dan masyarakat mendukung akses bacaan yang lebih luas....'],
            ];
            $query = mb_strtolower((string) request('q'));
            $category = (string) request('category', '');
            $articles = array_values(array_filter($articles, fn ($article) => ($query === '' || str_contains(mb_strtolower($article['title'].' '.$article['summary'].' '.$article['category']), $query)) && ($category === '' || $article['category'] === $category)));
        @endphp

        <form class="publication-filter-bar" action="{{ route('publications.index') }}" method="GET">
            <span>Menampilkan <strong>{{ count($articles) }}</strong> publikasi</span>
            <label for="publication-category">Jenis publikasi</label>
            <select id="publication-category" name="category" onchange="this.form.submit()">
                <option value="">Semua publikasi</option>
                <option value="Pendidikan" @selected(request('category') === 'Pendidikan')>Pendidikan</option>
                <option value="Sosial" @selected(request('category') === 'Sosial')>Sosial</option>
                <option value="Test" @selected(request('category') === 'Test')>Test</option>
            </select>
            @if (request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
            @endif
        </form>

        @if (count($articles))
            <div class="publication-grid">
                @foreach ($articles as $article)
                    <a class="publication-card" href="{{ route('publications.show') }}">
                        <img src="{{ asset('assets/images/publications/'.$article['image']) }}" alt="Kegiatan Komunitas Literasi Remaja Tambun Selatan" loading="lazy" decoding="async">
                        <div class="publication-card-body">
                            <span class="publication-category">{{ $article['category'] }}</span>
                            <span class="card-date">{{ $article['date'] }}</span>
                            <h2>{{ $article['title'] }}</h2>
                            <p>{{ $article['summary'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="publication-empty">Berita dengan kata kunci tersebut belum ditemukan.</p>
        @endif

        <nav class="publication-pagination" aria-label="Navigasi halaman publikasi">
            <a class="current" href="{{ route('publications.index', request()->query()) }}" aria-current="page">1</a>
            <a href="{{ route('publications.index', array_merge(request()->query(), ['page' => 2])) }}">2</a>
            <span aria-hidden="true">…</span>
            <a href="{{ route('publications.index', array_merge(request()->query(), ['page' => 30])) }}">30</a>
            <a class="pagination-next" href="{{ route('publications.index', array_merge(request()->query(), ['page' => 2])) }}" aria-label="Halaman berikutnya">›</a>
        </nav>
    </div>
</section>
@endsection
