<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Kategori;
use App\Models\Investigation;
use App\Models\InvestigationTimeline;
use App\Models\InvestigationDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InvestigationTest extends TestCase
{
    use RefreshDatabase;

    protected User $investigator1;
    protected User $investigator2;
    protected User $superAdmin;
    protected Laporan $laporan;
    protected Investigation $investigation;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create Users
        $this->investigator1 = User::factory()->create([
            'name' => 'Ahmad Investigator',
            'email' => 'investigator1@bbspjikkp.go.id',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->investigator1->assignRole('investigator');

        $this->investigator2 = User::factory()->create([
            'name' => 'Budi Investigator',
            'email' => 'investigator2@bbspjikkp.go.id',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->investigator2->assignRole('investigator');

        $this->superAdmin = User::factory()->create([
            'name' => 'Super Admin WBS',
            'email' => 'admin@bbspjikkp.go.id',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        // Create category
        $kategori = Kategori::create([
            'nama' => 'Korupsi',
            'deskripsi' => 'Korupsi',
            'is_aktif' => true,
        ]);

        // Create Report
        $this->laporan = Laporan::create([
            'nomor_registrasi' => 'WBS-2026-00001',
            'tracking_token' => 'TOKEN123',
            'kategori_id' => $kategori->id,
            'judul' => 'Dugaan Gratifikasi Alat Lab',
            'deskripsi' => 'Ini deskripsi aduan dugaan gratifikasi.',
            'status' => 'investigasi',
            'is_anonim' => true,
        ]);

        // Create Investigation assigned to Investigator 1
        $this->investigation = Investigation::create([
            'laporan_id' => $this->laporan->id,
            'investigator_id' => $this->investigator1->id,
            'status' => 'active',
        ]);
    }

    /**
     * Test assigned investigator can view their investigation page.
     */
    public function test_assigned_investigator_can_view_investigation()
    {
        $response = $this->actingAs($this->investigator1)
            ->get(route('investigations.show', $this->investigation->id));

        $response->assertStatus(200);
        $response->assertViewHas('investigation');
        $response->assertSee('Dugaan Gratifikasi Alat Lab');
    }

    /**
     * Test other investigator cannot view the investigation page.
     */
    public function test_unassigned_investigator_cannot_view_investigation()
    {
        $response = $this->actingAs($this->investigator2)
            ->get(route('investigations.show', $this->investigation->id));

        $response->assertStatus(403);
    }

    /**
     * Test investigator can add timeline progress.
     */
    public function test_investigator_can_add_timeline()
    {
        $response = $this->actingAs($this->investigator1)
            ->post(route('investigations.store-timeline', $this->investigation->id), [
                'description' => 'Melakukan pemeriksaan dokumen.',
                'date' => now()->format('Y-m-d H:i:s'),
            ]);

        $response->assertRedirect(route('investigations.show', $this->investigation->id));
        $this->assertDatabaseHas('investigation_timelines', [
            'investigation_id' => $this->investigation->id,
            'description' => 'Melakukan pemeriksaan dokumen.',
        ]);
    }

    /**
     * Test investigator can securely upload supporting document.
     */
    public function test_investigator_can_upload_document()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('laporan_keuangan.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->investigator1)
            ->post(route('investigations.store-document', $this->investigation->id), [
                'document' => $file,
            ]);

        $response->assertRedirect(route('investigations.show', $this->investigation->id));
        
        // Assert stored in local disk (outside public)
        $this->assertDatabaseHas('investigation_documents', [
            'investigation_id' => $this->investigation->id,
            'file_name' => 'laporan_keuangan.pdf',
        ]);

        $doc = InvestigationDocument::first();
        Storage::disk('local')->assertExists($doc->file_path);
    }

    /**
     * Test investigator can submit final result.
     */
    public function test_investigator_can_submit_final_result()
    {
        $response = $this->actingAs($this->investigator1)
            ->post(route('investigations.update-result', $this->investigation->id), [
                'final_result' => 'Terbukti menerima suap sebesar Rp 50.000.000.',
                'recommendation' => 'Pemberhentian tidak dengan hormat.',
            ]);

        $response->assertRedirect(route('investigations.show', $this->investigation->id));
        $this->assertDatabaseHas('investigations', [
            'id' => $this->investigation->id,
            'status' => 'completed',
            'final_result' => 'Terbukti menerima suap sebesar Rp 50.000.000.',
            'recommendation' => 'Pemberhentian tidak dengan hormat.',
        ]);
    }
}
