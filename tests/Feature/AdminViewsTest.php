<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publication;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminViewsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'status' => 'approved',
            'email' => 'testadmin@literasitamsel.id',
        ]);
    }

    public function test_admin_publications_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/publications');
        $response->assertStatus(200);
        $response->assertSee('Publikasi &amp; Kabar Komunitas', false);
    }

    public function test_admin_users_displays_plain_text_without_badge_classes(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Admin');
        $response->assertSee('Approved');
        $response->assertDontSee('bg-purple-100');
        $response->assertDontSee('bg-emerald-50 text-emerald-700 border border-emerald-200');
    }

    public function test_admin_settings_does_not_contain_old_sub_descriptions(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertDontSee('Label deskripsi: "Anak yang terjangkau kegiatan baca"');
        $response->assertDontSee('Label deskripsi: "Buku bacaan fisik di perpustakaan"');
    }

    public function test_admin_join_us_loads_three_cards(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/join-us');
        $response->assertStatus(200);
        $response->assertSee('Ikut Volunteer');
        $response->assertSee('Volunteer Event');
        $response->assertSee('Ajukan Kerja Sama Kolaborasi');
    }

    public function test_admin_join_us_volunteer_and_collaboration_update(): void
    {
        // 1. Volunteer edit page
        $resVol = $this->actingAs($this->admin)->get('/admin/join-us?edit=volunteer');
        $resVol->assertStatus(200);
        $resVol->assertSee('Pengaturan Tautan Formulir Ikut Volunteer');
        $resVol->assertSee('Tautan Formulir Pendaftaran');

        // 2. Update volunteer form link
        $updateVol = $this->actingAs($this->admin)->post('/admin/join-us', [
            'section' => 'volunteer',
            'title' => 'Ikut Volunteer Komunitas',
            'form_link' => 'https://docs.google.com/forms/d/volunteer-test',
            'status' => 'Buka',
            'description' => 'Daftar sebagai relawan aktif.',
        ]);
        $updateVol->assertRedirect('/admin/join-us?edit=volunteer');

        // 3. Collaboration edit page
        $resCol = $this->actingAs($this->admin)->get('/admin/join-us?edit=collaboration');
        $resCol->assertStatus(200);
        $resCol->assertSee('Pengaturan Ajukan Kerja Sama Kolaborasi');
        $resCol->assertSee('Tautan Formulir Kerja Sama');

        // 4. Update collaboration form link
        $updateCol = $this->actingAs($this->admin)->post('/admin/join-us', [
            'section' => 'collaboration',
            'title' => 'Ajukan Kerja Sama Kolaborasi Baru',
            'form_link' => 'https://docs.google.com/forms/d/collab-test',
            'status' => 'Buka',
            'contact_email' => 'partner@literasitamsel.org',
            'description' => 'Buka peluang kemitraan literasi.',
        ]);
        $updateCol->assertRedirect('/admin/join-us?edit=collaboration');

        // 5. Verify public page reflects both links
        $publicRes = $this->get('/join-us');
        $publicRes->assertStatus(200);
        $publicRes->assertSee('https://docs.google.com/forms/d/volunteer-test');
        $publicRes->assertSee('https://docs.google.com/forms/d/collab-test');
    }

    public function test_admin_volunteer_events_crud(): void
    {
        // 1. Event list page
        $resEvt = $this->actingAs($this->admin)->get('/admin/join-us?edit=event');
        $resEvt->assertStatus(200);
        $resEvt->assertSee('Kelola Event Sukarelawan');

        // 2. Add new event
        $storeRes = $this->actingAs($this->admin)->post('/admin/join-us/events', [
            'title' => 'Festival Membaca Anak Desa',
            'area' => 'Tambun Selatan',
            'schedule' => '30 November 2026',
            'deadline' => '25 November 2026',
            'quota' => '20 Relawan',
            'status' => 'Buka',
            'form_link' => 'https://docs.google.com/forms/d/festival-test',
            'description' => 'Aktivitas literasi dan mendongeng.',
        ]);
        $storeRes->assertRedirect('/admin/join-us?edit=event');

        // 3. Verify event appears on public page
        $pubEvents = $this->get('/join-us/volunteer-event');
        $pubEvents->assertStatus(200);
        $pubEvents->assertSee('Festival Membaca Anak Desa');
        $pubEvents->assertSee('https://docs.google.com/forms/d/festival-test');
    }

    public function test_admin_media_partners_page_and_home_display(): void
    {
        // 1. Media partners admin page loads
        $res = $this->actingAs($this->admin)->get('/admin/media-partners');
        $res->assertStatus(200);
        $res->assertSee('Mitra Kolaborasi &amp; Partnership', false);

        // 2. Home page renders stats with SVG icons and media partners without border boxes
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('icon-park--positive-dynamics.svg');
        $homeRes->assertSee('andrsrxn--library.svg');
        $homeRes->assertSee('boxicons--community-filled.svg');
        $homeRes->assertSee('cil--library-building.svg');
        $homeRes->assertSee('Media Partner');
    }

    public function test_admin_publication_crud_operations(): void
    {
        // 1. Store publication
        $storeRes = $this->actingAs($this->admin)->post('/admin/publications', [
            'title' => 'New Meeting Hall MBS Purworejo; Ghirah dan Wajah Baru Kajian Ahad Pagi',
            'category' => 'Kajian',
            'published_at' => '2026-10-25',
            'content' => 'Narasi lengkap mengenai kajian ahad pagi dan fasilitas meeting hall baru.',
            'status' => 'published',
        ]);
        $storeRes->assertRedirect('/admin/publications');

        $this->assertDatabaseHas('publications', [
            'title' => 'New Meeting Hall MBS Purworejo; Ghirah dan Wajah Baru Kajian Ahad Pagi',
            'category' => 'Kajian',
            'status' => 'published',
        ]);

        $publication = Publication::where('category', 'Kajian')->first();
        $this->assertNotNull($publication);

        // 2. Update publication
        $updateRes = $this->actingAs($this->admin)->put('/admin/publications/'.$publication->id, [
            'title' => 'Judul Kajian Telah Diperbarui',
            'category' => 'Kajian',
            'published_at' => '2026-10-26',
            'content' => 'Konten yang telah direvisi.',
            'status' => 'published',
        ]);
        $updateRes->assertRedirect('/admin/publications');

        $this->assertDatabaseHas('publications', [
            'id' => $publication->id,
            'title' => 'Judul Kajian Telah Diperbarui',
        ]);

        // 3. Delete publication
        $deleteRes = $this->actingAs($this->admin)->delete('/admin/publications/'.$publication->id);
        $deleteRes->assertRedirect('/admin/publications');

        $this->assertDatabaseMissing('publications', [
            'id' => $publication->id,
        ]);
    }

    public function test_admin_library_edit_page_and_genre_plain_text(): void
    {
        $author = Author::create(['name' => 'Tara Westover']);
        $publisher = Publisher::create(['name' => 'Gramedia']);
        $genre = Genre::create(['name' => 'Memoar']);
        $book = Book::create([
            'title' => 'Educated',
            'slug' => 'educated',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'description' => 'Sebuah memoar yang menginspirasi.',
            'is_published' => true,
            'created_by' => $this->admin->id,
        ]);

        // 1. Library table renders genre as plain text without blue badge and links to create/edit
        $indexRes = $this->actingAs($this->admin)->get('/admin/library');
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Memoar');
        $indexRes->assertSee(route('admin.library.create'));
        $indexRes->assertSee(route('admin.library.edit', $book->id));

        // 2. Dedicated create page for book
        $createRes = $this->actingAs($this->admin)->get('/admin/library/create');
        $createRes->assertStatus(200);
        $createRes->assertSee('Tambah Buku');
        $createRes->assertSee('Informasi Utama Buku');
        $createRes->assertSee('Sampul Buku');

        // 3. Dedicated edit page for book
        $editRes = $this->actingAs($this->admin)->get('/admin/library/'.$book->id.'/edit');
        $editRes->assertStatus(200);
        $editRes->assertSee('Edit Buku');
        $editRes->assertSee($book->title);
        $editRes->assertSee('Informasi Utama Buku');
        $editRes->assertSee('Sampul Buku');
    }

    public function test_admin_publication_dedicated_edit_and_create_pages(): void
    {
        $publication = Publication::create([
            'title' => 'Artikel Berita Literasi',
            'slug' => 'artikel-berita-literasi',
            'category' => 'Pendidikan',
            'published_at' => '2026-10-25',
            'content' => 'Narasi lengkap artikel berita literasi.',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);

        // Publications index has create link
        $indexRes = $this->actingAs($this->admin)->get('/admin/publications');
        $indexRes->assertStatus(200);
        $indexRes->assertSee(route('admin.publications.create'));

        // Dedicated create page for publication
        $createRes = $this->actingAs($this->admin)->get('/admin/publications/create');
        $createRes->assertStatus(200);
        $createRes->assertSee('Tambah Publikasi');
        $createRes->assertSee('Foto Utama Berita');
        $createRes->assertSee('Isi Berita');

        // Dedicated edit page for publication
        $editRes = $this->actingAs($this->admin)->get('/admin/publications/'.$publication->id.'/edit');
        $editRes->assertStatus(200);
        $editRes->assertSee('Edit Publikasi');
        $editRes->assertSee($publication->title);
        $editRes->assertSee('Foto Utama Berita');
        $editRes->assertSee('Isi Berita');
    }

    public function test_partnership_public_page_loads_successfully(): void
    {
        $response = $this->get('/partnership');
        $response->assertStatus(200);
        $response->assertSee('Partnership');
        $response->assertSee('Logo Media Partner');
        $response->assertSee('Ruang Baca Nusantara');
        $response->assertSee('Pertamina');
        $response->assertSee('DOKUMENTASI KERJA SAMA');
        $response->assertSee('Ajukan Kerja Sama Kolaborasi');
    }

    public function test_home_page_book_cards_are_patent_and_neat(): void
    {
        $author = Author::create(['name' => 'Tara Westover']);
        $publisher = Publisher::create(['name' => 'Gramedia']);
        $genre = Genre::create(['name' => 'Memoar']);
        $user = $this->admin ?? User::factory()->create();
        Book::create([
            'title' => 'Educated',
            'slug' => 'educated',
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'genre_id' => $genre->id,
            'description' => 'Sebuah memoar.',
            'is_published' => true,
            'created_by' => $user->id,
        ]);

        $res = $this->get('/');
        $res->assertStatus(200);
        $res->assertSee('book-cover-wrap');
        $res->assertSee('book-title');
        $res->assertSee('book-author');
        $res->assertSee('book-genre');
        $res->assertSee('book-detail-link');
    }

    public function test_admin_can_crud_users_and_toggle_status(): void
    {
        // 1. Create a new user
        $storeRes = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Relawan Staff',
            'username' => 'relawan_staff',
            'email' => 'relawan@literasitamsel.org',
            'password' => 'secret123',
            'role' => 'admin',
            'status' => 'pending',
        ]);
        $storeRes->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'relawan@literasitamsel.org',
            'status' => 'pending',
        ]);

        $user = User::where('email', 'relawan@literasitamsel.org')->first();

        // 2. Toggle status to approved
        $toggleRes = $this->actingAs($this->admin)->patch('/admin/users/'.$user->id.'/toggle-status');
        $toggleRes->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'approved',
        ]);

        // 3. Update user details
        $updateRes = $this->actingAs($this->admin)->put('/admin/users/'.$user->id, [
            'name' => 'Relawan Senior',
            'username' => 'relawan_senior',
            'email' => 'relawan_senior@literasitamsel.org',
            'role' => 'super-admin',
            'status' => 'approved',
        ]);
        $updateRes->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Relawan Senior',
            'role' => 'super-admin',
        ]);

        // 4. Delete user
        $deleteRes = $this->actingAs($this->admin)->delete('/admin/users/'.$user->id);
        $deleteRes->assertRedirect('/admin/users');
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_pending_user_cannot_login_until_approved(): void
    {
        $pendingUser = User::create([
            'name' => 'Calon Admin',
            'username' => 'calon_admin',
            'email' => 'calon@literasitamsel.org',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'pending',
        ]);

        // Attempt login while pending
        $loginRes = $this->post('/login', [
            'email' => 'calon@literasitamsel.org',
            'password' => 'password123',
        ]);
        $loginRes->assertSessionHasErrors('email');
        $this->assertGuest();

        // Approve user
        $pendingUser->update(['status' => 'approved']);

        // Attempt login while approved
        $approvedLogin = $this->post('/login', [
            'email' => 'calon@literasitamsel.org',
            'password' => 'password123',
        ]);
        $approvedLogin->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($pendingUser);
    }

    public function test_about_page_two_cards_functionality(): void
    {
        $pub1 = Publication::create([
            'title' => 'Kabar Literasi Desa Pertama',
            'slug' => 'kabar-literasi-desa-pertama',
            'category' => 'Kegiatan',
            'published_at' => '2026-10-20',
            'content' => 'Narasi berita pertama.',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);

        $pub2 = Publication::create([
            'title' => 'Kabar Literasi Desa Kedua',
            'slug' => 'kabar-literasi-desa-kedua',
            'category' => 'Program',
            'published_at' => '2026-10-21',
            'content' => 'Narasi berita kedua.',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);

        $res = $this->get('/about');
        $res->assertStatus(200);
        $res->assertSee('Kabar Literasi Desa Pertama');
        $res->assertSee('Kabar Literasi Desa Kedua');
        $res->assertSee(route('publications.show', $pub1->slug));
        $res->assertSee(route('publications.show', $pub2->slug));
        $res->assertSee(route('publications.index'));
    }

    public function test_admin_settings_update_and_public_reflection(): void
    {
        $res = $this->actingAs($this->admin)->post('/admin/settings', [
            'impact_kids' => '≥ 650 Anak',
            'book_collection' => '± 800 Buku',
            'active_volunteers' => '35 Remaja',
            'village_libraries' => '4 Lokasi Binaan',
            'address' => 'Jl. Tridaya Sakti, Tambun Selatan',
            'whatsapp' => '+62 811-2233-4455',
            'email' => 'info@literasitamsel.org',
            'instagram' => 'https://instagram.com/literasitamsel_official',
            'youtube' => 'https://youtube.com/@literasitamsel_channel',
        ]);
        $res->assertRedirect('/admin/settings');

        // Check settings reflected on home page
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('data-count-to="650"', false);
        $home->assertSee('data-count-to="800"', false);
        $home->assertSee('data-count-to="35"', false);
        $home->assertSee('data-count-to="4"', false);
        $home->assertSee('+62 811-2233-4455');
        $home->assertSee('info@literasitamsel.org');
    }

    public function test_admin_views_do_not_contain_public_navigation_except_bottom_left_sidebar_link(): void
    {
        $book = Book::first();
        $publication = Publication::first();

        // 1. Digital Library Index & Create
        $libRes = $this->actingAs($this->admin)->get('/admin/library');
        $libRes->assertStatus(200);
        $libRes->assertDontSee('Pratinjau di web publik');
        $libRes->assertSee('Lihat website publik'); // in bottom left sidebar

        $libCreateRes = $this->actingAs($this->admin)->get('/admin/library/create');
        $libCreateRes->assertStatus(200);
        $libCreateRes->assertDontSee('Lihat di Web Publik');
        $libCreateRes->assertSee('Lihat website publik');

        // 2. Digital Library Edit
        if ($book) {
            $libEditRes = $this->actingAs($this->admin)->get('/admin/library/'.$book->id.'/edit');
            $libEditRes->assertStatus(200);
            $libEditRes->assertDontSee('Lihat di Web Publik');
            $libEditRes->assertSee('Lihat website publik');
        }

        // 3. Publications Index & Create
        $pubRes = $this->actingAs($this->admin)->get('/admin/publications');
        $pubRes->assertStatus(200);
        $pubRes->assertDontSee('Lihat Web Publik');
        $pubRes->assertDontSee('Pratinjau di web publik');
        $pubRes->assertSee('Lihat website publik');

        $pubCreateRes = $this->actingAs($this->admin)->get('/admin/publications/create');
        $pubCreateRes->assertStatus(200);
        $pubCreateRes->assertDontSee('Lihat di Web Publik');
        $pubCreateRes->assertSee('Lihat website publik');

        // 4. Publications Edit
        if ($publication) {
            $pubEditRes = $this->actingAs($this->admin)->get('/admin/publications/'.$publication->id.'/edit');
            $pubEditRes->assertStatus(200);
            $pubEditRes->assertDontSee('Lihat di Web Publik');
            $pubEditRes->assertSee('Lihat website publik');
        }

        // 5. Join Us
        $joinRes = $this->actingAs($this->admin)->get('/admin/join-us');
        $joinRes->assertStatus(200);
        $joinRes->assertDontSee('Lihat Halaman Publik');
        $joinRes->assertSee('Lihat website publik');

        $joinEventRes = $this->actingAs($this->admin)->get('/admin/join-us?edit=event');
        $joinEventRes->assertStatus(200);
        $joinEventRes->assertDontSee('Lihat Halaman Event');

        // 6. Media Partners
        $partnerRes = $this->actingAs($this->admin)->get('/admin/media-partners');
        $partnerRes->assertStatus(200);
        $partnerRes->assertDontSee('Lihat di Beranda');
        $partnerRes->assertSee('Lihat website publik');

        // 7. Settings
        $settingRes = $this->actingAs($this->admin)->get('/admin/settings');
        $settingRes->assertStatus(200);
        $settingRes->assertDontSee('Lihat Beranda Publik');
        $settingRes->assertSee('Lihat website publik');
    }
}
