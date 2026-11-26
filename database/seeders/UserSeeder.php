<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@literasitamsel.id'],
            [
                'name'       => 'Super Admin Tamsel',
                'username'   => 'superadmin',
                'password'   => Hash::make('password123'),
                'role'       => 'super-admin',
                'status'     => 'aktif',
                'privileges' => [
                    'publikasi'        => true,
                    'digital_library'  => true,
                    'join_us'          => true,
                    'media_partners'   => true,
                    'identitas_konten' => true,
                ],
                'deleted_at' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@literasitamsel.id'],
            [
                'name'       => 'Admin Konten & Pustaka',
                'username'   => 'adminkonten',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'status'     => 'aktif',
                'privileges' => [
                    'publikasi'        => true,
                    'digital_library'  => true,
                    'join_us'          => false,
                    'media_partners'   => false,
                    'identitas_konten' => false,
                ],
                'deleted_at' => null,
            ]
        );
    }
}
