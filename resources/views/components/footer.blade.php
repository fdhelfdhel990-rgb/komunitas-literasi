@php
    $communitySettings = \App\Http\Controllers\SettingController::getSettings();
    $contact = $communitySettings['contact'] ?? [];
    $rawWa = preg_replace('/[^0-9]/', '', $contact['whatsapp'] ?? '');
    if (str_starts_with($rawWa, '0')) {
        $waLink = 'https://wa.me/62' . substr($rawWa, 1);
    } elseif (str_starts_with($rawWa, '62')) {
        $waLink = 'https://wa.me/' . $rawWa;
    } else {
        $waLink = !empty($rawWa) ? 'https://wa.me/' . $rawWa : '#';
    }
@endphp

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Komunitas Literasi Remaja">
            <p>Berdiri sejak 3 Oktober 2021</p>
            <p>{{ $contact['address'] ?? 'Tambun Selatan, Bekasi, Jawa Barat' }}</p>
            <p>Gerobak Angkasa: Perpustakaan Keliling</p>
            <div class="social-links" aria-label="Media sosial">
                <a href="{{ !empty($contact['youtube']) ? $contact['youtube'] : '#' }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                <a href="{{ !empty($contact['instagram']) ? $contact['instagram'] : '#' }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="https://tiktok.com" target="_blank" rel="noopener" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a>
            </div>
        </div>

        <div>
            <h3>Jelajahi</h3>
            <a href="{{ url('/') }}">Beranda Utama</a>
            <a href="{{ url('/about') }}">Tentang Kami</a>
            <a href="{{ route('library.index') }}">Digital Library</a>
            <a href="{{ route('publications.index') }}">Publikasi &amp; Berita</a>
            <a href="{{ route('partnership') }}">Partnership</a>
            <a href="{{ route('join-us') }}">Join Us</a>
        </div>

        <div>
            <h3>Kontak Kami</h3>
            <p class="contact-item">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                @if (!empty($contact['whatsapp']))
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" style="color: inherit; text-decoration: underline;">{{ $contact['whatsapp'] }}</a>
                @else
                    <span>Nomor WhatsApp belum diisi</span>
                @endif
            </p>
            <p class="contact-item">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <span>{{ $contact['address'] ?? 'Tambun Selatan, Kabupaten Bekasi, Jawa Barat' }}</span>
            </p>
            <p class="contact-item">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                @if (!empty($contact['email']))
                    <a href="mailto:{{ $contact['email'] }}" style="color: inherit; text-decoration: underline;">{{ $contact['email'] }}</a>
                @else
                    <span>Email belum diisi</span>
                @endif
            </p>
        </div>
    </div>

    <div class="container footer-bottom">
        <p>© 2026 Komunitas Literasi Remaja Tambun Selatan.</p>
    </div>
</footer>
