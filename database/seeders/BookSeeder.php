<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id ?? 1;

        $dummyBooks = [
            ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah persahabatan dan perjuangan enam santri dalam mengejar cita-cita. Sebuah cerita tentang pendidikan, ketekunan, dan keyakinan bahwa impian dapat membawa seseorang melampaui batas.'],
            ['image' => 'book-2.png', 'title' => 'Educated', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar tentang perjalanan seorang perempuan mencari pendidikan dan membangun pemahaman baru tentang dirinya serta dunia di sekitarnya.'],
            ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Buku pengembangan diri yang mengajak pembaca memahami masa transisi dewasa muda dan menyusun langkah dengan lebih sadar.'],
            ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Perjalanan Impian', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Novel inspiratif tentang persahabatan, pendidikan, dan semangat untuk berusaha meraih cita-cita.'],
            ['image' => 'book-2.png', 'title' => 'Educated: Sebuah Memoar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Memoar mengenai kekuatan pendidikan dan perjalanan menemukan jati diri.'],
            ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Panduan Bertumbuh', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Panduan reflektif untuk menghadapi perubahan dan pilihan pada masa dewasa muda.'],
            ['image' => 'book-1.jpg', 'title' => 'Negeri 5 Menara: Edisi Komunitas', 'author' => 'Ahmad Fuadi', 'genre' => 'Novel', 'publisher' => 'Gramedia', 'description' => 'Kisah tentang belajar, hidup bersama, dan keyakinan yang menguatkan perjalanan meraih masa depan.'],
            ['image' => 'book-2.png', 'title' => 'Educated: Perjalanan Belajar', 'author' => 'Tara Westover', 'genre' => 'Memoar', 'publisher' => 'Gramedia', 'description' => 'Cerita perjalanan belajar yang membuka perspektif dan memberi ruang untuk bertumbuh.'],
            ['image' => 'book-3.png', 'title' => 'Quarter-Life Crisis: Mengenal Diri', 'author' => 'Maudy Ayunda', 'genre' => 'Pengembangan Diri', 'publisher' => 'Mizan', 'description' => 'Bacaan tentang mengenali nilai diri dan membuat keputusan dengan lebih terarah.'],
        ];

        foreach ($dummyBooks as $item) {
            $author = Author::firstOrCreate(['name' => $item['author']]);
            $publisher = Publisher::firstOrCreate(['name' => $item['publisher']]);
            $genre = Genre::firstOrCreate(['name' => $item['genre']]);

            Book::create([
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'author_id' => $author->id,
                'publisher_id' => $publisher->id,
                'genre_id' => $genre->id,
                'description' => $item['description'],
                'cover_path' => $item['image'],
                'is_published' => true,
                'created_by' => $userId,
            ]);
        }
    }
}
