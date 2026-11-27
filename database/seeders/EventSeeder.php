<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::first()?->id;

        Event::updateOrCreate(
            ['slug' => 'gerobak-angkasa'],
            [
                'name'                  => 'Membaca Bersama Gerobak Angkasa',
                'location'              => 'Tambun Selatan, Kabupaten Bekasi',
                'registration_status'   => 'Buka',
                'schedule'              => '2026-11-30',
                'registration_deadline' => '2026-11-25',
                'volunteer_quota'       => 20,
                'form_url'              => 'https://docs.google.com/forms/d/e/1FAIpQLSc-event-sample/viewform',
                'description'           => 'Dampingi anak-anak memilih buku dan membaca bersama dalam kegiatan literasi komunitas di Tambun Selatan.',
                'created_by'            => $adminId,
            ]
        );
    }
}
