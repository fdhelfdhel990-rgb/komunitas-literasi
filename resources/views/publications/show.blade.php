@extends('layouts.app', ['title' => $publication->title])

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
                <img class="publication-feature-image" src="{{ $publication->featured_image_path ? asset('assets/images/publications/'.$publication->featured_image_path) : 'https://placehold.co/800x400?text=No+Image' }}" alt="{{ $publication->title }}" fetchpriority="high">
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
                <h1>{{ $publication->title }}</h1>
                <p class="publication-article-date"><i class="fa-regular fa-calendar" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($publication->published_at)->translatedFormat('l, d F Y') }}</p>
                <div class="publication-article-copy">
                    {!! $publication->content !!}
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
                    @foreach ($recentPosts as $recent)
                        <a class="recent-post" href="{{ route('publications.show', $recent->slug) }}">
                            <img src="{{ $recent->featured_image_path ? asset('assets/images/publications/'.$recent->featured_image_path) : 'https://placehold.co/150x150?text=No+Image' }}" alt="{{ $recent->title }}" loading="lazy" decoding="async">
                            <span><strong>{{ $recent->title }}</strong><small>{{ \Carbon\Carbon::parse($recent->published_at)->translatedFormat('l, d F Y') }}</small></span>
                        </a>
                    @endforeach
                </section>
            </aside>
        </div>
    </div>
</section>
@endsection