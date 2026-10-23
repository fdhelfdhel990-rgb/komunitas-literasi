<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Komunitas Literasi Remaja">
            <p>Berdiri sejak 3 Oktober 2021</p>
            <p>Tambun Selatan, Bekasi, Jawa Barat</p>
            <p>Gerobak Angkasa: Perpustakaan Keliling</p>
            <div class="social-links" aria-label="Media sosial">
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube" aria-hidden="true"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook" aria-hidden="true"></i></a>
            </div>
        </div>

        <div>
            <h3>Jelajahi</h3>
            <a href="{{ url('/') }}">Beranda Utama</a>
            <a href="{{ url('/about') }}">Tentang Kami</a>
            <a href="{{ route('library.index') }}">Digital Library</a>
            <a href="{{ route('publications.index') }}">Publikasi &amp; Berita</a>
        </div>

        <div>
            <h3>Kontak Kami</h3>
            <p class="contact-item">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span>Nomor WhatsApp belum diisi</span>
            </p>
            <p class="contact-item">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <span>Tambun Selatan, Kabupaten Bekasi, Jawa Barat</span>
            </p>
            <p class="contact-item">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <span>Email belum diisi</span>
            </p>
        </div>
    </div>

    <div class="container footer-bottom">
        <p>© 2026 Komunitas Literasi Remaja Tambun Selatan.</p>
    </div>
</footer>
