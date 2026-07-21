<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create a normal user
        $this->user = User::factory()->create([
            'name' => 'Ahmad Pelapor',
            'email' => 'ahmad@example.com',
            'phone_number' => '08123456789',
            'is_active' => true,
            'status' => 'active',
        ]);

        // Create a category
        $this->kategori = Kategori::create([
            'nama' => 'Korupsi',
            'deskripsi' => 'Korupsi',
            'is_aktif' => true,
        ]);
    }

    /**
     * Guest cannot view report creation form.
     */
    public function test_guest_cannot_access_report_form(): void
    {
        $response = $this->get(route('laporan.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Authenticated user can view report creation form.
     */
    public function test_authenticated_user_can_access_report_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('laporan.create'));

        $response->assertStatus(200);
        $response->assertSee('Pilih Kategori');
    }

    /**
     * Authenticated user can submit anonymous report.
     * The database must store their real identity, but display accessors must return "Anonim".
     */
    public function test_authenticated_user_can_submit_anonymous_report(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('laporan.store'), [
                'kategori_id' => $this->kategori->id,
                'judul' => 'Dugaan Mark Up Pembelian Genset',
                'deskripsi' => 'Deskripsi kejadian minimal harus lima puluh karakter agar memenuhi aturan validasi sistem.',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'lokasi' => 'Gedung Genset',
                'is_anonim' => '1',
            ]);

        $response->assertRedirect(route('laporan.sukses'));

        // Assert database stores the actual identity
        $this->assertDatabaseHas('laporans', [
            'judul' => 'Dugaan Mark Up Pembelian Genset',
            'is_anonim' => true,
            'nama_pelapor' => 'Ahmad Pelapor',
            'email_pelapor' => 'ahmad@example.com',
            'telepon_pelapor' => '08123456789',
        ]);

        // Fetch report and verify accessors mask the data
        $laporan = Laporan::where('judul', 'Dugaan Mark Up Pembelian Genset')->first();
        $this->assertEquals('Anonim', $laporan->nama_pelapor);
        $this->assertEquals('Anonim', $laporan->email_pelapor);
        $this->assertEquals('Anonim', $laporan->telepon_pelapor);

        // Verify we can still get original raw details
        $this->assertEquals('Ahmad Pelapor', $laporan->real_nama_pelapor);
        $this->assertEquals('ahmad@example.com', $laporan->real_email_pelapor);
        $this->assertEquals('08123456789', $laporan->real_telepon_pelapor);
    }

    /**
     * Authenticated user can submit non-anonymous report.
     */
    public function test_authenticated_user_can_submit_non_anonymous_report(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('laporan.store'), [
                'kategori_id' => $this->kategori->id,
                'judul' => 'Dugaan Korupsi Alat Berat',
                'deskripsi' => 'Deskripsi kejadian minimal harus lima puluh karakter agar memenuhi aturan validasi sistem.',
                'tanggal_kejadian' => now()->format('Y-m-d'),
                'lokasi' => 'Gedung Produksi',
                'is_anonim' => '0',
                'nama_pelapor' => 'Ahmad Pelapor Beda',
                'email_pelapor' => 'beda@example.com',
                'telepon_pelapor' => '08999999999',
            ]);

        $response->assertRedirect(route('laporan.sukses'));

        // Assert database stores the submitted identity
        $this->assertDatabaseHas('laporans', [
            'judul' => 'Dugaan Korupsi Alat Berat',
            'is_anonim' => false,
            'nama_pelapor' => 'Ahmad Pelapor Beda',
            'email_pelapor' => 'beda@example.com',
            'telepon_pelapor' => '08999999999',
        ]);

        // Fetch report and verify accessors return actual data
        $laporan = Laporan::where('judul', 'Dugaan Korupsi Alat Berat')->first();
        $this->assertEquals('Ahmad Pelapor Beda', $laporan->nama_pelapor);
        $this->assertEquals('beda@example.com', $laporan->email_pelapor);
        $this->assertEquals('08999999999', $laporan->telepon_pelapor);
    }
}
