<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles & Permissions terlebih dahulu
        $this->call(RolePermissionSeeder::class);

        // 2. Seed Kategori Pelanggaran
        $this->call(KategoriSeeder::class);

        // 2. Buat Super Admin default
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bbspjikkp.go.id'],
            [
                'name'     => 'Super Admin WBS',
                'password' => Hash::make('Admin@bbspjikkp123'),
                'is_active'=> true,
                'status'   => 'active',
            ]
        );
        $superAdmin->assignRole('super-admin');

        // 3. Buat Tim WBS default
        $timWbs = User::firstOrCreate(
            ['email' => 'timwbs@bbspjikkp.go.id'],
            [
                'name'     => 'Tim WBS',
                'password' => Hash::make('Timwbs@bbspjikkp123'),
                'is_active'=> true,
                'status'   => 'active',
            ]
        );
        $timWbs->assignRole('tim-wbs');

        // 4. Buat Investigator default
        $investigator = User::firstOrCreate(
            ['email' => 'investigator@bbspjikkp.go.id'],
            [
                'name'     => 'Tim Investigator',
                'password' => Hash::make('Investigator@bbspjikkp123'),
                'is_active'=> true,
                'status'   => 'active',
            ]
        );
        $investigator->assignRole('investigator');

        // 5. Buat Kepala BBSPJIKKP default
        $kepala = User::firstOrCreate(
            ['email' => 'kepala@bbspjikkp.go.id'],
            [
                'name'     => 'Kepala BBSPJIKKP',
                'password' => Hash::make('Kepala@bbspjikkp123'),
                'is_active'=> true,
                'status'   => 'active',
            ]
        );
        $kepala->assignRole('kepala-bbspjikkp');
    }
}
