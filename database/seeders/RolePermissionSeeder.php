<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed roles dan permissions sesuai PRD WBS BBSPJIKKP
     * Modul 3: Role & Permission
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // =========================================================
        // PERMISSIONS
        // =========================================================

        $permissions = [
            // --- Laporan ---
            'view-laporan',
            'create-laporan',
            'update-laporan',
            'delete-laporan',
            'approve-laporan',   // Tim WBS: validasi/tolak laporan

            // --- Verifikasi ---
            'view-verifikasi',
            'create-verifikasi',
            'update-verifikasi',

            // --- Investigasi ---
            'view-investigasi',
            'create-investigasi',
            'update-investigasi',
            'approve-investigasi',   // Kepala: telaah & tindak lanjut

            // --- Tindak Lanjut ---
            'view-tindak-lanjut',
            'create-tindak-lanjut',
            'update-tindak-lanjut',

            // --- Monitoring ---
            'view-monitoring',
            'update-monitoring',

            // --- User Management ---
            'view-user',
            'create-user',
            'update-user',
            'delete-user',

            // --- Master Data ---
            'view-master-data',
            'create-master-data',
            'update-master-data',
            'delete-master-data',

            // --- Audit Log ---
            'view-audit-log',

            // --- Notification ---
            'view-notification',

            // --- Settings / Pengaturan Sistem ---
            'view-settings',
            'update-settings',

            // --- Dashboard ---
            'view-dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // =========================================================
        // ROLES & PERMISSION ASSIGNMENT
        // =========================================================

        // --- Super Admin ---
        // Akses penuh ke seluruh sistem
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // --- Tim WBS ---
        // Verifikasi laporan, monitoring, audit log
        $timWbs = Role::firstOrCreate(['name' => 'tim-wbs']);
        $timWbs->syncPermissions([
            'view-laporan',
            'create-laporan',
            'update-laporan',
            'approve-laporan',
            'view-verifikasi',
            'create-verifikasi',
            'update-verifikasi',
            'view-investigasi',
            'view-monitoring',
            'view-master-data',
            'view-audit-log',
            'view-notification',
            'view-dashboard',
        ]);

        // --- Investigator ---
        // Menjalankan investigasi, upload dokumen, rekomendasi
        $investigator = Role::firstOrCreate(['name' => 'investigator']);
        $investigator->syncPermissions([
            'view-laporan',
            'update-laporan',
            'view-investigasi',
            'create-investigasi',
            'update-investigasi',
            'view-tindak-lanjut',
            'view-monitoring',
            'view-master-data',
            'view-notification',
            'view-dashboard',
        ]);

        // --- Kepala BBSPJIKKP ---
        // Menerima laporan valid, bentuk tim investigasi, telaah, tindak lanjut
        $kepala = Role::firstOrCreate(['name' => 'kepala-bbspjikkp']);
        $kepala->syncPermissions([
            'view-laporan',
            'approve-laporan',
            'view-verifikasi',
            'view-investigasi',
            'approve-investigasi',
            'view-tindak-lanjut',
            'create-tindak-lanjut',
            'update-tindak-lanjut',
            'view-monitoring',
            'update-monitoring',
            'view-audit-log',
            'view-master-data',
            'view-notification',
            'view-dashboard',
        ]);
    }
}
