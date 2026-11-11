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

        <div class="event-list-container">
            @forelse ($events ?? ($settings['events'] ?? []) as $event)
                <article class="event-card" style="margin-bottom: 20px;">
                    @if (!empty($event['poster_image']) && file_exists(public_path('assets/images/events/'.$event['poster_image'])))
                        <div class="event-art event-art-poster">
                            <img src="{{ asset('assets/images/events/'.$event['poster_image']) }}" alt="{{ $event['title'] }}" class="event-poster-img" loading="lazy">
                        </div>
                    @else
                        <div class="event-art event-art-reading" role="img" aria-label="Ilustrasi kegiatan membaca bersama">
                            <span class="event-art-label">{!! nl2br(e($event['badge_top'] ?? "GEROBAK\nANGKASA")) !!}</span>
                            <i class="fa-solid fa-book-open-reader" aria-hidden="true"></i>
                            <span class="event-art-caption">{{ $event['badge_bottom'] ?? 'RUANG BACA BERGERAK' }}</span>
                        </div>
                    @endif
                    <div class="event-copy">
                        <h2>{{ $event['title'] }}</h2>
                        <p>{{ $event['description'] }}</p>
                        <p class="event-deadline"><strong>Batas Pendaftaran</strong><br>{{ $event['deadline'] ?? 'Belum ditentukan' }}</p>
                        <div class="event-actions">
                            @if (!empty($event['form_link']) && ($event['status'] ?? 'Buka') === 'Buka')
                                <a class="btn event-register" href="{{ $event['form_link'] }}" target="_blank" rel="noopener">Daftar Sekarang</a>
                            @else
                                <button class="btn event-register" type="button" disabled title="Tautan pendaftaran belum tersedia">Daftar Sekarang</button>
                            @endif
                            <button class="btn event-share" type="button" data-event-share><i class="fa-solid fa-share-nodes" aria-hidden="true"></i> Bagikan</button>
                            @if (!empty($event['juknis_file']))
                                <a class="event-link event-download-link" href="{{ asset('assets/documents/juknis/'.$event['juknis_file']) }}" target="_blank" download title="Unduh Juknis Kegiatan">Unduh Juknis</a>
                            @else
                                <button class="event-link event-download-link" type="button" disabled title="Juknis belum tersedia">Unduh Juknis</button>
                            @endif
                        </div>
                        <p class="event-feedback" data-event-feedback aria-live="polite"></p>
                    </div>
                </article>
            @empty
                <div style="padding: 32px; text-align: center; color: #64748b; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0;">
                    Saat ini belum ada event sukarelawan aktif. Silakan pantau kembali secara berkala.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
