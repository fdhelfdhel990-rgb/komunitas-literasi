<?php

namespace Tests\Feature;

use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MediaPartnerController;
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
        // 1. Regular sub-admin is blocked from /admin/users (403 forbidden)
        $subAdminRes = $this->actingAs($this->admin)->get('/admin/users');
        $subAdminRes->assertStatus(403);

        // 2. Super admin can access /admin/users
        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'approved',
        ]);
        $response = $this->actingAs($superAdmin)->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Admin');
        $response->assertSee('Aktif');
        $response->assertDontSee('bg-purple-100');
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
        $response->assertSee('Media Partner');
        $response->assertDontSee('PROFIL MITRA · DATA CONTOH');
        $response->assertDontSee('DOKUMENTASI KERJA SAMA · DATA CONTOH');
        $response->assertDontSee('Ruang Baca Nusantara');
        $response->assertSee('Belum Ada Publikasi Kemitraan');
        $response->assertSee('Ajukan Kerja Sama Kolaborasi');
    }

    public function test_partnership_page_displays_partnership_publications_and_pagination(): void
    {
        Publication::create([
            'title' => 'Penyaluran Buku Bacaan Bermutu',
            'slug' => 'penyaluran-buku-bacaan-bermutu',
            'category' => 'Partnership',
            'published_at' => now(),
            'excerpt' => 'Pemberian paket buku bergambar.',
            'content' => '<p>Konten berita kerja sama.</p>',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get('/partnership?partner_page=1&article_page=1');
        $response->assertStatus(200);
        $response->assertSee('Media Partner');
        $response->assertSee('Penyaluran Buku Bacaan Bermutu');
        $response->assertDontSee('Belum Ada Publikasi Kemitraan');
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
        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'approved',
        ]);

        // 1. Regular sub-admin cannot create users (403 forbidden)
        $forbiddenRes = $this->actingAs($this->admin)->post('/admin/users', [
            'name' => 'Relawan Staff',
            'email' => 'relawan@literasitamsel.org',
            'password' => 'secret123',
            'role' => 'admin',
        ]);
        $forbiddenRes->assertStatus(403);

        // 2. Super admin creates a new user
        $storeRes = $this->actingAs($superAdmin)->post('/admin/users', [
            'name' => 'Relawan Staff',
            'username' => 'relawan_staff',
            'email' => 'relawan@literasitamsel.org',
            'password' => 'secret123',
            'role' => 'admin',
            'privileges' => ['dashboard', 'publications'],
        ]);
        $storeRes->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'relawan@literasitamsel.org',
            'status' => 'approved',
        ]);

        $user = User::where('email', 'relawan@literasitamsel.org')->first();

        // 3. Toggle status to pending (nonaktif)
        $toggleRes = $this->actingAs($superAdmin)->patch('/admin/users/'.$user->id.'/toggle-status');
        $toggleRes->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'pending',
        ]);

        // 4. Update user details (status and privileges are editable here)
        $updateRes = $this->actingAs($superAdmin)->put('/admin/users/'.$user->id, [
            'name' => 'Relawan Senior',
            'username' => 'relawan_senior',
            'email' => 'relawan_senior@literasitamsel.org',
            'status' => 'approved',
            'privileges' => ['dashboard', 'publications', 'library'],
        ]);
        $updateRes->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Relawan Senior',
            'status' => 'approved',
        ]);

        // 5. Delete user
        $deleteRes = $this->actingAs($superAdmin)->delete('/admin/users/'.$user->id);
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
            'instagram_show' => '1',
            'youtube' => 'https://youtube.com/@literasitamsel_channel',
            'youtube_show' => '1',
            'facebook' => 'https://facebook.com/literasitamsel_page',
            'facebook_show' => '1',
            'tiktok' => 'https://tiktok.com/@literasitamsel_official',
            // tiktok_show is omitted / unchecked
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
        $home->assertSee('https://facebook.com/literasitamsel_page');
        // TikTok was unchecked so it should not be displayed in footer
        $home->assertDontSee('https://tiktok.com/@literasitamsel_official');
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

    public function test_book_validation_requires_description_and_author_publisher_must_contain_letters(): void
    {
        // 1. Missing description should fail
        $resNoDesc = $this->actingAs($this->admin)->post('/admin/library', [
            'title' => 'Buku Uji Coba',
            'author_name' => 'Penulis Valid',
            'publisher_name' => 'Penerbit Valid',
            'genre_name' => 'Fiksi',
            'description' => '',
        ]);
        $resNoDesc->assertSessionHasErrors('description');

        // 2. Purely numeric author name (e.g. "544") should fail
        $resNumericAuthor = $this->actingAs($this->admin)->post('/admin/library', [
            'title' => 'Buku Angka',
            'author_name' => '544',
            'publisher_name' => 'Penerbit Valid',
            'genre_name' => 'Fiksi',
            'description' => 'Sinopsis buku yang lengkap.',
        ]);
        $resNumericAuthor->assertSessionHasErrors('author_name');

        // 3. Purely numeric publisher name (e.g. "12345") should fail
        $resNumericPub = $this->actingAs($this->admin)->post('/admin/library', [
            'title' => 'Buku Angka 2',
            'author_name' => 'Penulis Valid',
            'publisher_name' => '12345',
            'genre_name' => 'Fiksi',
            'description' => 'Sinopsis buku yang lengkap.',
        ]);
        $resNumericPub->assertSessionHasErrors('publisher_name');

        // 4. Valid string with numbers (e.g. "Studio 47" or "Penerbit 21") should pass
        $resValid = $this->actingAs($this->admin)->post('/admin/library', [
            'title' => 'Buku Kreatif',
            'author_name' => 'Penulis Studio 47',
            'publisher_name' => 'Gramedia 21',
            'genre_name' => 'Fiksi',
            'description' => 'Sinopsis buku yang lengkap dan valid.',
        ]);
        $resValid->assertRedirect('/admin/library');
        $this->assertDatabaseHas('books', ['title' => 'Buku Kreatif']);
    }

    public function test_dedicated_create_and_edit_pages_render_for_events_partners_and_users(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'approved',
        ]);

        // Join Us Event Create & Edit
        $eventCreateRes = $this->actingAs($this->admin)->get('/admin/join-us/events/create');
        $eventCreateRes->assertStatus(200);
        $eventCreateRes->assertSee('Tambah Event Sukarelawan');

        // Store a new event to guarantee it exists for edit
        $this->actingAs($this->admin)->post('/admin/join-us/events', [
            'title' => 'Event Uji Coba Detail Page',
            'category' => 'Volunteer Event',
            'area' => 'Tambun Selatan',
            'schedule' => '30 November 2026',
            'deadline' => '25 November 2026',
            'quota' => '20 Relawan',
            'status' => 'Buka',
            'description' => 'Deskripsi kegiatan relawan.',
        ]);

        $settings = (new JoinUsController)->getSettings();
        $targetEventId = end($settings['events'])['id'];

        $eventEditRes = $this->actingAs($this->admin)->get('/admin/join-us/events/'.$targetEventId.'/edit');
        $eventEditRes->assertStatus(200);
        $eventEditRes->assertSee('Edit Event Sukarelawan');

        // Media Partner Create & Edit
        $partnerCreateRes = $this->actingAs($this->admin)->get('/admin/media-partners/create');
        $partnerCreateRes->assertStatus(200);
        $partnerCreateRes->assertSee('Tambah Media Partner');

        $partners = MediaPartnerController::getPartners();
        $targetPartnerId = $partners[0]['id'] ?? 1;
        $partnerEditRes = $this->actingAs($this->admin)->get('/admin/media-partners/'.$targetPartnerId.'/edit');
        $partnerEditRes->assertStatus(200);
        $partnerEditRes->assertSee('Edit Media Partner');

        // Users Create & Edit (Super Admin only)
        $userCreateRes = $this->actingAs($superAdmin)->get('/admin/users/create');
        $userCreateRes->assertStatus(200);
        $userCreateRes->assertSee('Tambah Admin Baru');
        $userCreateRes->assertDontSee('Status Akun *');

        $userEditRes = $this->actingAs($superAdmin)->get('/admin/users/'.$this->admin->id.'/edit');
        $userEditRes->assertStatus(200);
        $userEditRes->assertSee('Edit Akun Admin');
        $userEditRes->assertDontSee('Status Akun *');
    }

    public function test_super_admin_and_sub_admin_privilege_access_control(): void
    {
        // 1. Sub-admin with restricted permissions
        $subAdmin = User::factory()->create([
            'role' => 'admin',
            'status' => 'approved',
            'privileges' => ['dashboard', 'publications'], // only 01 & 02
        ]);

        // Can access dashboard & publications
        $this->actingAs($subAdmin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($subAdmin)->get('/admin/publications')->assertStatus(200);

        // Blocked from pages not permitted
        $this->actingAs($subAdmin)->get('/admin/library')->assertStatus(403);
        $this->actingAs($subAdmin)->get('/admin/join-us')->assertStatus(403);
        $this->actingAs($subAdmin)->get('/admin/media-partners')->assertStatus(403);
        $this->actingAs($subAdmin)->get('/admin/settings')->assertStatus(403);
        $this->actingAs($subAdmin)->get('/admin/users')->assertStatus(403);

        // Sidebar for subAdmin does not show Kelola Admin & Izin
        $sidebarRes = $this->actingAs($subAdmin)->get('/admin/dashboard');
        $sidebarRes->assertDontSee('Kelola Admin & Izin');

        // 2. Super admin has full access to all pages
        $superAdmin = User::factory()->create([
            'role' => 'super-admin',
            'status' => 'approved',
        ]);

        $this->actingAs($superAdmin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/publications')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/library')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/join-us')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/media-partners')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/settings')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/users')->assertStatus(200);

        $superSidebarRes = $this->actingAs($superAdmin)->get('/admin/dashboard');
        $superSidebarRes->assertSee('Kelola Admin & Izin');
    }
}
