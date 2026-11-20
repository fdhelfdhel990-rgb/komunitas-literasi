<?php

namespace App\Http\Controllers;

use App\Models\MediaPartner;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class MediaPartnerController extends Controller
{
    public static function getPartners(): array
    {
        try {
            if (! Schema::hasTable('media_partners')) {
                return [];
            }

            return MediaPartner::latest()
                ->get()
                ->map(fn ($partner) => [
                    'id'   => $partner->id,
                    'name' => $partner->name,
                    'logo' => $partner->image_path,
                ])
                ->toArray();
        } catch (Throwable) {
            return [];
        }
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
        $page = max(1, (int) $request->input('partner_page', $request->input('page', 1)));
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
            ->paginate(3, ['*'], 'article_page')
            ->withQueryString();

        return view('partnership', compact('partners', 'partnershipArticles'));
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

    public function indexAdmin()
    {
        $this->checkPrivilege();
        $partners = self::getPartners();

        return view('admin.media-partners.index', compact('partners'));
    }

    public function createAdmin()
    {
        $this->checkPrivilege();

        return view('admin.media-partners.create');
    }

    public function editAdmin($id)
    {
        $this->checkPrivilege();
        $record = MediaPartner::findOrFail($id);

        $partner = [
            'id'   => $record->id,
            'name' => $record->name,
            'logo' => $record->image_path,
        ];

        return view('admin.media-partners.edit', compact('partner'));
    }

    public function storeAdmin(Request $request)
    {
        $this->checkPrivilege();

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'logo_image' => ['required', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:3072'],
        ]);

        $file = $request->file('logo_image');
        $fileName = 'partner_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        $destination = public_path('assets/images/partners');

        File::ensureDirectoryExists($destination);
        $file->move($destination, $fileName);

        MediaPartner::create([
            'name'       => $validated['name'],
            'image_path' => $fileName,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Logo media partner "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    public function updateAdmin(Request $request, $id)
    {
        $this->checkPrivilege();

        $partner = MediaPartner::findOrFail($id);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'logo_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:3072'],
        ]);

        $partner->name = $validated['name'];

        if ($request->hasFile('logo_image')) {
            $destination = public_path('assets/images/partners');
            File::ensureDirectoryExists($destination);

            if ($partner->image_path && File::exists($destination . '/' . $partner->image_path)) {
                @unlink($destination . '/' . $partner->image_path);
            }

            $file = $request->file('logo_image');
            $fileName = 'partner_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $fileName);

            $partner->image_path = $fileName;
        }

        $partner->save();

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Media partner berhasil diperbarui!');
    }

    public function destroyAdmin($id)
    {
        $this->checkPrivilege();

        $partner = MediaPartner::findOrFail($id);
        $filePath = public_path('assets/images/partners/' . $partner->image_path);

        if ($partner->image_path && File::exists($filePath)) {
            @unlink($filePath);
        }

        $partner->delete();

        return redirect()->route('admin.media-partners.index')
            ->with('success', 'Logo media partner berhasil dihapus!');
    }
}