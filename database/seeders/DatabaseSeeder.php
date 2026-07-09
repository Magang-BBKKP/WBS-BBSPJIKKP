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

        // 2. Buat Super Admin default
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@bbspjikkp.go.id'],
            [
                'name'     => 'Super Admin WBS',
                'password' => Hash::make('Admin@bbspjikkp123'),
                'status'   => 'active',
            ]
        );
        $superAdmin->assignRole('super-admin');
    }
}
