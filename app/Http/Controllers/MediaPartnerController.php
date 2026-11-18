<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaPartnerController extends Controller
{
    /**
     * Storage path for media partners json.
     */
    public static function getStoragePath(): string
    {
        return storage_path('app/media_partners.json');
    }

    /**
     * Get media partners with defaults.
     */
    public static function getPartners(): array
    {
        $path = self::getStoragePath();
        $defaults = [
            [
                'id' => 1,
                'name' => 'Kereta Api Indonesia (KAI)',
                'logo' => 'kai.svg',
            ],
            [
                'id' => 2,
                'name' => 'Garuda Indonesia',
                'logo' => 'garuda.svg',
            ],
            [
                'id' => 3,
                'name' => 'Telkom Indonesia',
                'logo' => 'telkom.svg',
            ],
            [
                'id' => 4,
                'name' => 'Pertamina',
                'logo' => 'pertamina.svg',
            ],
            [
                'id' => 5,
                'name' => 'Kementerian Pendidikan',
                'logo' => 'kementrian.jpg',
            ],
            [
                'id' => 6,
                'name' => 'Universitas Gadjah Mada',
                'logo' => 'ugm.png',
            ],
            [
                'id' => 7,
                'name' => 'BRI Peduli',
                'logo' => 'bri-peduli.png',
            ],
            [
                'id' => 8,
                'name' => 'KMTEDI',
                'logo' => 'kmtedi.jpg',
            ],
        ];

        if (File::exists($path)) {
            $saved = json_decode(File::get($path), true);
            if (is_array($saved) && count($saved) > 0) {
                return $saved;
            }
        }

        return $defaults;
    }

    /**
     * Get partner profiles for public partnership page.
     */
    public static function getProfiles(): array
    {
        return [
            [
                'id' => 1,
                'tag' => 'PROFIL MITRA · DATA CONTOH',
                'name' => 'Ruang Baca Nusantara',
                'description' => 'Mitra literasi yang berkolaborasi dalam publikasi kegiatan, kelas membaca, dan perluasan akses buku untuk anak-anak di Tambun Selatan.',
                'support' => 'Bentuk dukungan: publikasi kegiatan dan keterlibatan relawan.',
            ],
            [
                'id' => 2,
                'tag' => 'PROFIL MITRA · DATA CONTOH',
                'name' => 'Pertamina',
                'description' => 'Mitra literasi yang berkolaborasi dalam publikasi kegiatan, kelas membaca, dan perluasan akses buku untuk anak-anak di Tambun Selatan.',
                'support' => 'Bentuk dukungan: penyediaan fasilitas pojok baca dan pendampingan program.',
            ],
            [
                'id' => 3,
                'tag' => 'PROFIL MITRA · DATA CONTOH',
                'name' => 'BRI Peduli',
                'description' => 'Mitra perbankan yang aktif mendukung pengadaan armada perpustakaan keliling dan koleksi buku bacaan bermutu.',
                'support' => 'Bentuk dukungan: bantuan sarana armada Gerobak Angkasa dan donasi buku.',
            ],
            [
                'id' => 4,
                'tag' => 'PROFIL MITRA · DATA CONTOH',
                'name' => 'Universitas Gadjah Mada',
                'description' => 'Kolaborasi pengabdian masyarakat, riset dampak literasi anak desa, dan penerjunan relawan pengajar mahasiswa.',
                'support' => 'Bentuk dukungan: program KKN tematik dan pelatihan relawan pengajar.',
            ],
        ];
    }

    /**
     * Display the public partnership page.
     */
    public function indexPublic(Request $request)
    {
        $allPartners = self::getPartners();
        $perPage = 8;
        $page = (int) $request->input('partner_page', $request->input('page', 1));
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;
        $currentPartners = array_slice($allPartners, $offset, $perPage);

        $partners = new LengthAwarePaginator(
            $currentPartners,
            count($allPartners),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'pageName' => 'partner_page',
                'query' => $request->query(),
            ]
        );

        $partnershipArticles = Publication::where('status', 'published')
            ->where(function ($q) {
                $q->where('category', 'Partnership')
                    ->orWhere('category', 'partnership')
                    ->orWhere('category', 'Kemitraan')
                    ->orWhere('category', 'kemitraan');
            })
            ->latest('published_at')
            ->paginate(6, ['*'], 'article_page')
            ->withQueryString();

        return view('partnership', compact('partners', 'partnershipArticles'));
    }

    /**
     * Save partners array.
     */
    protected static function savePartners(array $partners): void
    {
        $dir = dirname(self::getStoragePath());
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(self::getStoragePath(), json_encode($partners, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Check if user has permission to manage Media Partners.
     */
    protected function checkPrivilege(): void
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin() && ! auth()->user()->hasPrivilege('media-partners')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengelola Media Partner.');
        }
    }

    /**
     * Display media partners in admin panel.
     */
    public function indexAdmin()
    {
        $this->checkPrivilege();

        $partners = self::getPartners();

        return view('admin.media-partners.index', compact('partners'));
    }

    /**
     * Show dedicated create page for media partner.
     */
    public function createAdmin()
    {
        $this->checkPrivilege();

        return view('admin.media-partners.create');
    }

    /**
     * Show dedicated edit page for media partner.
     */
    public function editAdmin($id)
    {
        $this->checkPrivilege();

        $partners = self::getPartners();
        $partner = null;
        foreach ($partners as $p) {
            if ($p['id'] == $id) {
                $partner = $p;
                break;
            }
        }

        if (! $partner) {
            abort(404, 'Media partner tidak ditemukan.');
        }

        return view('admin.media-partners.edit', compact('partner'));
    }

    /**
     * Store a new media partner.
     */
    public function storeAdmin(Request $request)
    {
        $this->checkPrivilege();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_image' => ['required', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:3072'],
        ]);

        $partners = self::getPartners();
        $nextId = count($partners) > 0 ? max(array_column($partners, 'id')) + 1 : 1;

        $file = $request->file('logo_image');
        $fileName = 'partner_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
        $destination = public_path('assets/images/partners');

        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $fileName);

        $partners[] = [
            'id' => $nextId,
            'name' => $request->input('name'),
            'logo' => $fileName,
        ];

        self::savePartners($partners);

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Logo media partner "'.$request->input('name').'" berhasil ditambahkan!');
    }

    /**
     * Update an existing media partner logo.
     */
    public function updateAdmin(Request $request, $id)
    {
        $this->checkPrivilege();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:3072'],
        ]);

        $partners = self::getPartners();
        $found = false;

        foreach ($partners as &$partner) {
            if ($partner['id'] == $id) {
                $partner['name'] = $request->input('name');

                if ($request->hasFile('logo_image')) {
                    $file = $request->file('logo_image');
                    $fileName = 'partner_'.time().'_'.Str::random(5).'.'.$file->getClientOriginalExtension();
                    $destination = public_path('assets/images/partners');

                    if (! File::isDirectory($destination)) {
                        File::makeDirectory($destination, 0755, true);
                    }

                    $file->move($destination, $fileName);
                    $partner['logo'] = $fileName;
                }

                $found = true;
                break;
            }
        }

        if ($found) {
            self::savePartners($partners);
        }

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Media partner berhasil diperbarui!');
    }

    /**
     * Delete a media partner.
     */
    public function destroyAdmin($id)
    {
        $this->checkPrivilege();

        $partners = self::getPartners();
        $filtered = array_values(array_filter($partners, function ($p) use ($id) {
            return $p['id'] != $id;
        }));

        self::savePartners($filtered);

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Logo media partner berhasil dihapus!');
    }
}
