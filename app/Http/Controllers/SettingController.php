<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Storage path for community settings.
     */
    public static function getStoragePath(): string
    {
        return storage_path('app/community_settings.json');
    }

    /**
     * Default settings values.
     */
    public static function getSettings(): array
    {
        $path = self::getStoragePath();
        $defaults = [
            'stats' => [
                'impact_kids' => '≥ 400 Anak',
                'impact_number' => 400,
                'book_collection' => '± 500 Buku',
                'book_number' => 500,
                'active_volunteers' => '20 Remaja',
                'volunteers_number' => 20,
                'village_libraries' => '2 Lokasi Binaan',
                'libraries_number' => 2,
            ],
            'contact' => [
                'address' => 'Tambun Selatan, Kabupaten Bekasi, Jawa Barat',
                'whatsapp' => '+62 812-3456-7890',
                'email' => 'kontak@literasitamsel.org',
                'instagram' => 'https://instagram.com/literasitamsel',
                'youtube' => 'https://youtube.com/@literasitamsel',
            ],
        ];

        if (! File::exists($path)) {
            return $defaults;
        }

        try {
            $json = json_decode(File::get($path), true);

            return is_array($json) ? array_replace_recursive($defaults, $json) : $defaults;
        } catch (\Throwable) {
            return $defaults;
        }
    }

    /**
     * Show admin settings form.
     */
    public function indexAdmin(): View
    {
        $settings = self::getSettings();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'impact_kids' => ['required', 'string', 'max:50'],
            'book_collection' => ['required', 'string', 'max:50'],
            'active_volunteers' => ['required', 'string', 'max:50'],
            'village_libraries' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
        ]);

        preg_match('/\d+/', $validated['impact_kids'], $m1);
        preg_match('/\d+/', $validated['book_collection'], $m2);
        preg_match('/\d+/', $validated['active_volunteers'], $m3);
        preg_match('/\d+/', $validated['village_libraries'], $m4);

        $settings = [
            'stats' => [
                'impact_kids' => $validated['impact_kids'],
                'impact_number' => ! empty($m1[0]) ? (int) $m1[0] : 400,
                'book_collection' => $validated['book_collection'],
                'book_number' => ! empty($m2[0]) ? (int) $m2[0] : 500,
                'active_volunteers' => $validated['active_volunteers'],
                'volunteers_number' => ! empty($m3[0]) ? (int) $m3[0] : 20,
                'village_libraries' => $validated['village_libraries'],
                'libraries_number' => ! empty($m4[0]) ? (int) $m4[0] : 2,
            ],
            'contact' => [
                'address' => $validated['address'],
                'whatsapp' => $validated['whatsapp'] ?? '',
                'email' => $validated['email'] ?? '',
                'instagram' => $validated['instagram'] ?? '',
                'youtube' => $validated['youtube'] ?? '',
            ],
        ];

        $dir = dirname(self::getStoragePath());
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put(self::getStoragePath(), json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan statistik beranda dan informasi kontak berhasil diperbarui!');
    }
}
