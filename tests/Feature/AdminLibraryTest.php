<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLibraryTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'status' => 'approved',
        ]);
    }

    private function seedTestBook(string $title = 'Laskar Pelangi'): Book
    {
        $author = Author::firstOrCreate(['name' => 'Andrea Hirata']);
        $publisher = Publisher::firstOrCreate(['name' => 'Bentang Pustaka']);
        $genre = Genre::firstOrCreate(['name' => 'Novel']);

        return Book::create([
            'title' => $title,
            'slug' => 'laskar-pelangi',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'cover_path' => 'book-1.jpg',
            'description' => 'Kisah kehidupan 10 anak di Belitung.',
            'is_published' => true,
            'created_by' => 1,
        ]);
    }

    public function test_guest_cannot_access_admin_library(): void
    {
        $response = $this->get('/admin/library');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_admin_library(): void
    {
        $admin = $this->createAdminUser();
        $this->seedTestBook('Bumi Manusia');

        $response = $this->actingAs($admin)->get('/admin/library');

        $response->assertStatus(200);
        $response->assertSee('Katalog Digital Library');
        $response->assertSee('Bumi Manusia');
        $response->assertSee('Andrea Hirata');
        $response->assertSee('Bentang Pustaka');
        // Pastikan kolom ketersediaan sudah dihapus
        $response->assertDontSee('<th>Ketersediaan</th>', false);
    }

    public function test_admin_can_create_new_book(): void
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->post('/admin/library', [
            'title' => 'Filosofi Teras',
            'author_name' => 'Henry Manampiring',
            'publisher_name' => 'Kompas',
            'genre_name' => 'Filosofi',
            'description' => 'Penerapan filsafat Stoisisme dalam kehidupan sehari-hari.',
        ]);

        $response->assertRedirect('/admin/library');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'title' => 'Filosofi Teras',
            'description' => 'Penerapan filsafat Stoisisme dalam kehidupan sehari-hari.',
        ]);
        $this->assertDatabaseHas('authors', ['name' => 'Henry Manampiring']);
        $this->assertDatabaseHas('publishers', ['name' => 'Kompas']);
        $this->assertDatabaseHas('genres', ['name' => 'Filosofi']);

        // Pastikan buku langsung muncul di web publik Digital Library
        $publicResponse = $this->get('/digital-library');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Filosofi Teras');
        $publicResponse->assertSee('Henry Manampiring');
    }

    public function test_admin_can_update_book(): void
    {
        $admin = $this->createAdminUser();
        $book = $this->seedTestBook('Buku Lama');

        $response = $this->actingAs($admin)->put('/admin/library/'.$book->id, [
            'title' => 'Buku Baru Diupdate',
            'author_name' => 'Penulis Baru',
            'publisher_name' => 'Penerbit Baru',
            'genre_name' => 'Sains',
            'description' => 'Deskripsi yang telah diperbarui.',
        ]);

        $response->assertRedirect('/admin/library');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Buku Baru Diupdate',
            'description' => 'Deskripsi yang telah diperbarui.',
        ]);
        $this->assertDatabaseMissing('books', [
            'title' => 'Buku Lama',
        ]);
    }

    public function test_admin_can_delete_book(): void
    {
        $admin = $this->createAdminUser();
        $book = $this->seedTestBook('Buku Untuk Dihapus');

        $response = $this->actingAs($admin)->delete('/admin/library/'.$book->id);

        $response->assertRedirect('/admin/library');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}
