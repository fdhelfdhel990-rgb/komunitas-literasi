@extends('layouts.app', ['title' => 'Detail Event Volunteer | Komunitas Literasi Remaja Tambun Selatan'])

@section('content')
<section class="join-event-page">
    <div class="container">
        <nav class="page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('join-us') }}">Join Us</a>
            <span aria-hidden="true">&gt;</span>
            <a href="{{ route('join-us.events') }}">Volunteer Event</a>
            <span aria-hidden="true">&gt;</span>
            <span aria-current="page">Detail Event</span>
        </nav>

        <article class="event-detail-card">
            <div class="event-art event-art-reading" role="img" aria-label="Ilustrasi kegiatan membaca bersama">
                <span class="event-art-label">GEROBAK<br>ANGKASA</span>
                <i class="fa-solid fa-book-open-reader" aria-hidden="true"></i>
                <span class="event-art-caption">RUANG BACA BERGERAK</span>
            </div>
            <div class="event-detail-copy">
                <span class="join-badge">VOLUNTEER EVENT</span>
                <h1>Membaca Bersama Gerobak Angkasa</h1>
                <p class="event-detail-description">Kegiatan membaca bersama mengajak anak-anak menikmati buku dan membuka ruang belajar yang menyenangkan melalui perpustakaan keliling Gerobak Angkasa.</p>
                <dl class="event-facts event-detail-facts">
                    <div><dt><i class="fa-regular fa-calendar" aria-hidden="true"></i> Jadwal kegiatan</dt><dd>Akan diumumkan</dd></div>
                    <div><dt><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Area kegiatan</dt><dd>Tambun Selatan, Kabupaten Bekasi</dd></div>
                    <div><dt><i class="fa-solid fa-user-group" aria-hidden="true"></i> Peran relawan</dt><dd>Mendampingi anak memilih buku, membaca bersama, dan mendukung aktivitas literasi.</dd></div>
                    <div><dt><i class="fa-regular fa-calendar-xmark" aria-hidden="true"></i> Batas pendaftaran</dt><dd>Belum ditentukan</dd></div>
                </dl>
                <div class="event-detail-actions">
                    <button class="btn event-register" type="button" disabled title="Tautan pendaftaran belum tersedia">Daftar Sekarang</button>
                    <a class="event-link" href="{{ route('join-us.events') }}">Kembali ke daftar event</a>
                </div>
                <p class="event-footnote">Informasi ini merupakan gambaran kegiatan. Jadwal, pendaftaran, dan juknis akan diperbarui setelah tersedia informasi resmi.</p>
            </div>
        </article>
    </div>
</section>
@endsection
