<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Publication;
use Illuminate\Support\Str;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::first()->id ?? 1;

        $articles = [
            [
                'image' => 'publication-1.jpg',
                'category' => 'Sosial',
                'date' => '2026-10-05',
                'title' => 'Semangat 20 Relawan Membina 2 Perpustakaan Desa di Wilayah Tambun Selatan',
                'summary' => 'Tambun Selatan - Memiliki visi bersama dalam memajukan literasi, 20 relawan Komunitas Literasi Remaja Tambun Selatan secara aktif mendampingi....',
                'content' => '<p>Gerobak Angkasa merupakan perpustakaan keliling yang menjadi ujung tombak pergerakan Komunitas Literasi Remaja Tambun Selatan. Gerobak ini membawa ratusan buku bacaan anak langsung ke lingkungan tempat tinggal anak-anak di 6 desa dan 1 kelurahan.</p><p>Dipandu oleh 20 sukarelawan aktif, kegiatan Gerobak Angkasa tidak hanya menyajikan buku, namun juga menghidupkan ruang membaca bersama, pendampingan belajar, dan cerita gembira bagi lebih dari 400 anak-anak yang telah merasakan dampak positifnya.</p><p>Melalui dua perpustakaan desa binaan, para relawan terus menjaga semangat belajar dan memastikan setiap anak dapat menemukan bacaan yang menyenangkan dan bermakna.</p>'
            ],
            [
                'image' => 'publication-2.jpg',
                'category' => 'Pendidikan',
                'date' => '2026-08-15',
                'title' => 'Semangat Membaca Anak-anak bersama Gerobak Angkasa',
                'summary' => 'Relawan mengajak anak-anak menikmati bacaan dan belajar bersama melalui perpustakaan keliling Gerobak Angkasa....',
                'content' => '<p>Kegiatan membaca bersama anak-anak di desa binaan berlangsung meriah. Puluhan anak berkumpul mengelilingi gerobak baca dengan antusiasme tinggi untuk memilih buku kesukaan mereka.</p><p>Para relawan turut mendampingi proses membaca, memberikan penjelasan cerita, dan memfasilitasi sesi tanya jawab singkat yang mengasah pemahaman anak-anak.</p>'
            ],
            [
                'image' => 'publication-3.jpg',
                'category' => 'Sosial',
                'date' => '2026-08-25',
                'title' => 'Gerobak Angkasa Hadirkan Perpustakaan Keliling',
                'summary' => 'Koleksi buku dan ruang membaca kini hadir lebih dekat dengan anak-anak di berbagai desa Tambun Selatan....',
                'content' => '<p>Perpustakaan keliling menjadi solusi efektif dalam menjangkau wilayah pemukiman yang minim akses fasilitas membaca umum. Dengan armada yang fleksibel, buku-buku diposisikan lebih dekat dengan keseharian anak-anak.</p>'
            ],
            [
                'image' => 'publication-1.jpg',
                'category' => 'Pendidikan',
                'date' => '2026-08-12',
                'title' => 'Ruang Baca Bersama Tumbuhkan Minat Literasi Anak',
                'summary' => 'Relawan mendampingi anak-anak mengenal buku dan menikmati kegiatan membaca bersama di lingkungan mereka....',
                'content' => '<p>Pendampingan membaca secara intensif membantu anak-anak memahami isi bacaan dengan suasana yang leluasa dan komunikatif. Pendekatan ini terbukti meningkatkan daya ingat dan minat baca secara bertahap.</p>'
            ],
            [
                'image' => 'publication-2.jpg',
                'category' => 'Sosial',
                'date' => '2026-08-08',
                'title' => 'Relawan Hadir Membuka Akses Bacaan di Desa',
                'summary' => 'Kegiatan komunitas mendekatkan bacaan dan pendampingan belajar kepada lebih banyak keluarga....',
                'content' => '<p>Dukungan nyata diberikan oleh para relawan dengan membuka ruang belajar terbuka di pelataran balai warga. Orang tua dan anak-anak dapat hadir bersama menikmati fasilitas buku gratis.</p>'
            ],
            [
                'image' => 'publication-3.jpg',
                'category' => 'Pendidikan',
                'date' => '2026-08-02',
                'title' => 'Perpustakaan Keliling Menjangkau Pembaca Muda',
                'summary' => 'Gerobak Angkasa menjadi ruang belajar bergerak yang menyenangkan bagi anak-anak Tambun Selatan....',
                'content' => '<p>Program rutin mingguan ini secara konsisten membawa keceriaan dan pengalaman belajar baru bagi pembaca usia sekolah dasar hingga remaja awal.</p>'
            ],
            [
                'image' => 'publication-1.jpg',
                'category' => 'Sosial',
                'date' => '2026-07-28',
                'title' => 'Kolaborasi Warga dan Relawan untuk Literasi',
                'summary' => 'Dukungan warga membantu kegiatan membaca tumbuh menjadi kebiasaan bersama di lingkungan desa....',
                'content' => '<p>Sinergi antara tokoh masyarakat, warga lokal, dan jaringan relawan memperkuat ekosistem literasi desa sehingga kegiatan membaca berjalan berkelanjutan.</p>'
            ],
            [
                'image' => 'publication-2.jpg',
                'category' => 'Pendidikan',
                'date' => '2026-07-21',
                'title' => 'Belajar dan Bercerita Bersama Gerobak Angkasa',
                'summary' => 'Sesi membaca dan bercerita memberi anak-anak kesempatan belajar dalam suasana akrab....',
                'content' => '<p>Sesi mendongeng (storytelling) interaktif disajikan untuk melatih keberanian serta kemampuan mendengarkan dan mengemukakan pendapat bagi anak-anak.</p>'
            ],
            [
                'image' => 'publication-3.jpg',
                'category' => 'Sosial',
                'date' => '2026-07-14',
                'title' => 'Gerakan Literasi dari Tambun Selatan',
                'summary' => 'Komunitas terus mengajak remaja dan masyarakat mendukung akses bacaan yang lebih luas....',
                'content' => '<p>Gerakan ini lahir dari kepedulian pemuda lokal untuk memastikan generasi penerus memiliki kecintaan pada ilmu pengetahuan dan bahan bacaan berkualitas.</p>'
            ],
        ];

        foreach ($articles as $item) {
            Publication::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'category' => $item['category'],
                'published_at' => $item['date'],
                'excerpt' => $item['summary'],
                'content' => $item['content'],
                'featured_image_path' => $item['image'],
                'status' => 'published',
                'created_by' => $userId,
            ]);
        }
    }
}
