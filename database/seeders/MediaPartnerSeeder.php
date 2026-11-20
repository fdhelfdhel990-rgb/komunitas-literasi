<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MediaPartner;
use App\Models\User;
use Illuminate\Support\Facades\File;

class MediaPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::first()?->id;
        $destination = public_path('assets/images/partners');
        File::ensureDirectoryExists($destination);

        $partners = [
            ['name' => 'Kereta Api Indonesia (KAI)', 'image_path' => 'kai.svg'],
            ['name' => 'Garuda Indonesia', 'image_path' => 'garuda.svg'],
            ['name' => 'Telkom Indonesia', 'image_path' => 'telkom.svg'],
            ['name' => 'Pertamina', 'image_path' => 'pertamina.svg'],
            ['name' => 'Kementerian Pendidikan', 'image_path' => 'kementrian.jpg'],
            ['name' => 'Universitas Gadjah Mada', 'image_path' => 'ugm.png'],
            ['name' => 'BRI Peduli', 'image_path' => 'bri-peduli.png'],
            ['name' => 'KMTEDI', 'image_path' => 'kmtedi.jpg'],
        ];

        foreach ($partners as $item) {
            $filePath = $destination . '/' . $item['image_path'];

            if (! File::exists($filePath)) {
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="240" height="80" viewBox="0 0 240 80">'
                     . '<rect width="240" height="80" rx="8" fill="#f1f5f9"/>'
                     . '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" '
                     . 'font-family="sans-serif" font-size="13" font-weight="bold" fill="#0A2373">'
                     . htmlspecialchars($item['name'])
                     . '</text></svg>';

                File::put($filePath, $svg);
            }

            MediaPartner::updateOrCreate(
                ['name' => $item['name']],
                [
                    'image_path' => $item['image_path'],
                    'created_by' => $adminId,
                ]
            );
        }
    }
}
