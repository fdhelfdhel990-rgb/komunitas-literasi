@extends('layouts.app', ['title' => 'Volunteer Event | Komunitas Literasi Remaja Tambun Selatan'])

@section('content')
<section class="join-event-page">
    <div class="container">
        <nav class="page-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('join-us') }}">Join Us</a>
            <span aria-hidden="true">&gt;</span>
            <span aria-current="page">Volunteer Event</span>
        </nav>

        <header class="volunteer-heading">
            <h1>Event yang sedang dan akan berlangsung</h1>
            <p>Pilih kegiatan untuk melihat informasi dan peran relawannya.</p>
        </header>

        <article class="event-card">
            <div class="event-art event-art-reading" role="img" aria-label="Ilustrasi kegiatan membaca bersama">
                <span class="event-art-label">GEROBAK<br>ANGKASA</span>
                <i class="fa-solid fa-book-open-reader" aria-hidden="true"></i>
                <span class="event-art-caption">RUANG BACA BERGERAK</span>
            </div>
            <div class="event-copy">
                <h2>Membaca Bersama Gerobak Angkasa</h2>
                <p>Dampingi anak-anak memilih buku dan membaca bersama dalam kegiatan literasi komunitas di Tambun Selatan.</p>
                <p class="event-deadline"><strong>Batas Pendaftaran</strong><br>Belum ditentukan</p>
                <div class="event-actions">
                    <button class="btn event-register" type="button" disabled title="Tautan pendaftaran belum tersedia">Daftar Sekarang</button>
                    <button class="btn event-share" type="button" data-event-share><i class="fa-solid fa-share-nodes" aria-hidden="true"></i> Bagikan</button>
                    <button class="event-link event-download-link" type="button" disabled title="Juknis belum tersedia">Unduh Juknis</button>
                </div>
                <p class="event-feedback" data-event-feedback aria-live="polite"></p>
            </div>
        </article>
    </div>
</section>
@endsection
