# Komunitas Literasi Remaja Tambun Selatan

## Tujuan dokumen

Dokumen ini menjadi acuan utama untuk perubahan tampilan dan slicing halaman. Perubahan berikutnya harus mempertahankan sistem desain, margin, tipografi, dan komponen bersama di bawah ini.

## Tech stack dan batasan

- Framework: Laravel dengan Blade views.
- Styling: Tailwind CSS v4 tersedia melalui Vite; gaya proyek yang sudah ada berada di `resources/css/app.css`. Ikuti pola tersebut dan jangan menambahkan framework CSS lain.
- JavaScript interaktif berada di `resources/js/app.js`.
- Jangan mengubah struktur atau susunan global navbar dan footer yang sudah disepakati. Perubahan visual pada keduanya harus tetap menjaga struktur dan konten.
- Gunakan komponen/pola yang ada sebelum membuat pola baru.
- Konten katalog saat ini bersifat statis di Blade. Jangan menganggap pagination atau data katalog sebagai data backend.

## Sistem desain

### Tipografi

- Font utama: Poppins dengan fallback sans-serif.
- Heading halaman: tegas, umumnya bobot 700; heading kartu 600–700.
- Teks isi: bobot 400–500 dengan kontras yang cukup.
- Hindari heading display yang terlalu besar. Gunakan ukuran responsif dan hierarki yang jelas.

### Warna

Gunakan token CSS di `resources/css/app.css` sebagai sumber nilai yang diterapkan:

| Peran | Nilai acuan |
| --- | --- |
| Footer/navy gelap | `#010134` |
| Biru utama dan indikator aktif | `#1F237E` |
| Latar navbar dan permukaan halaman | `#FFFFFF` |
| Permukaan kartu | `#FFFFFF` |
| Teks utama | `#292D39` |
| Teks sekunder | `#6F7480` |

Header utama mengikuti referensi proyek: permukaan putih, teks navigasi gelap, dan garis aktif biru. Footer memakai navy gelap. Warna tersebut sudah tersedia sebagai custom properties di `:root`; gunakan token yang sesuai alih-alih menambahkan nilai warna baru.

Kartu memakai radius `--card-radius` (16px), bayangan `--card-shadow`, dan bayangan hover `--card-shadow-hover`. Gunakan token ini untuk kartu publikasi, buku, event, panel filter, dan sidebar agar bobot visual konsisten.

## Margin, lebar, dan skala halaman

- Semua halaman menggunakan `.container` yang sama, termasuk detail berita/buku/event dan area isi navbar/footer.
- Lebar maksimum konten desktop: `1160px`.
- Gutter horizontal: `40px` per sisi desktop, `24px` tablet (maksimum `900px`), `16px` ponsel (maksimum `700px`). Nilai diatur dengan `--content-max-width` dan `--page-gutter` pada `:root`.
- Semua section utama dan detail memakai jarak vertikal bersama `--page-section-space` (`clamp(32px, 4vw, 48px)`).
- Heading halaman dan detail memakai `--page-title-size` (`clamp(26px, 2.6vw, 32px)`) dengan bobot 700. Hero boleh memiliki skala khusus sesuai komposisi gambar.
- Seluruh halaman memakai skala global `--page-scale: .92` pada `body`. Jangan menambahkan scale atau zoom khusus per halaman.
- Jangan membatasi lebar section tertentu secara lokal jika isinya harus sejajar dengan gutter halaman. Gunakan modifier `.narrow` hanya bila ada alasan visual khusus.
- Jangan memberi `max-width`, margin horizontal, atau negative margin khusus pada halaman detail. Pengecualian hanya untuk elemen dalam konten yang memang sengaja lebih sempit, seperti paragraf bacaan, form pencarian, atau blok statistik.
- Jangan membuat konten melampaui gutter halaman. Carousel buku pada layar kecil tetap berada di dalam container dan dapat digulir horizontal.
- Hero dan latar section boleh memenuhi lebar layar, tetapi isi teksnya tetap disejajarkan ke `.container`.
- Jaga skala heading dan ruang antarbagian agar halaman utama dan halaman detail tidak terasa membesar saat berpindah halaman.

## Komponen dan pola bersama

- Navbar: logo di kiri dan navigasi utama; halaman aktif memakai garis bawah biru. Pertahankan struktur global yang ada.
- Footer: latar navy, teks terang, logo, kontak, navigasi, dan hak cipta. Pertahankan struktur global yang ada.
- Kartu publikasi/buku: gambar atau cover, kategori/metadata seperlunya, judul tegas, teks sekunder, dan CTA yang jelas.
- Digital Library: filter berada di sisi katalog pada desktop dan tersusun sebelum katalog pada layar kecil. Sampul buku harus terlihat utuh dan tidak terpotong.
- Pagination katalog harus dihitung dari jumlah item yang sedang tampil setelah filter, mempertahankan query filter saat berpindah, menandai halaman aktif, dan menyediakan tombol halaman sebelumnya (`<`) serta berikutnya (`>`).
- Breadcrumb, tanggal, metadata, dan deskripsi menggunakan gaya teks sekunder yang terbaca.

## Responsif dan aksesibilitas

- Pertahankan gutter bersama pada desktop, tablet, dan ponsel.
- Grid boleh berubah jumlah kolom sesuai breakpoint, tetapi tidak boleh menyebabkan konten melewati lebar viewport.
- Tombol dan link harus memiliki label yang jelas; gambar sampul memakai alt yang sesuai.
- Gunakan `loading="lazy"` dan `decoding="async"` untuk gambar konten yang berada di bawah lipatan.

## Daftar halaman

- Home: `/`
- About Us: `/about`
- Publication: `/publication`
- Detail Publication: `/publication/semangat-20-relawan`
- Digital Library: `/digital-library`
- Join Us: `/join-us`
- Volunteer Event: `/join-us/volunteer-event`
- Detail Event: `/join-us/volunteer-event/gerobak-angkasa`

## Menjalankan proyek

```bash
npm install
npm run dev
php artisan serve
```

Build aset produksi dengan `npm run build`.
