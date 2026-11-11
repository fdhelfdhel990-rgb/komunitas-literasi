@extends('layouts.app', ['title' => 'Join Us | Komunitas Literasi Remaja Tambun Selatan'])

@section('content')
<section class="join-intro section">
    <div class="container">
        <h1>Bergabung Dengan Komunitas</h1>
        <p>Mari berkontribusi nyata bagi pendidikan generasi muda. Pilih peran Anda: mendampingi kegiatan literasi atau mengajukan kolaborasi bersama kami.</p>

        <div class="join-choice-grid">
            <article class="join-choice-card">
                <i class="fa-solid fa-people-group" aria-hidden="true"></i>
                <h2>Ikut Volunteer</h2>
                <p>{{ $settings['volunteer']['description'] ?? 'Bergabung sebagai pendamping membaca dan penggerak kegiatan komunitas.' }}</p>
                <a class="btn btn-primary" href="{{ !empty($settings['volunteer']['form_link']) ? $settings['volunteer']['form_link'] : route('join-us.events') }}" @if(!empty($settings['volunteer']['form_link'])) target="_blank" rel="noopener" @endif>Daftar relawan</a>
            </article>
            <article class="join-choice-card">
                <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                <h2>Ikut Volunteer Event</h2>
                <p>Terlibat pada agenda literasi tertentu sesuai waktu dan kemampuan Anda.</p>
                <a class="btn btn-primary" href="{{ route('join-us.events') }}">Lihat agenda kegiatan</a>
            </article>
            <article class="join-choice-card">
                <i class="fa-solid fa-handshake" aria-hidden="true"></i>
                <h2>Ajukan Kerja Sama Kolaborasi</h2>
                <p>{{ $settings['collaboration']['description'] ?? 'Buka peluang kemitraan program, publikasi, atau dukungan operasional.' }}</p>
                <a class="btn btn-primary" href="{{ !empty($settings['collaboration']['form_link']) ? $settings['collaboration']['form_link'] : route('about') }}" @if(!empty($settings['collaboration']['form_link'])) target="_blank" rel="noopener" @endif>Ajukan kerja sama</a>
            </article>
        </div>
    </div>
</section>
@endsection
