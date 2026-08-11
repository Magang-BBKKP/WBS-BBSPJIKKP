<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Faq;
use App\Models\Investigation;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RevisionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test Modul Pelaporan (Revision 1 & 2)
     */
    public function test_reported_party_fields_are_strictly_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('laporan.store'), [
            'sumber_informasi' => 'Lainnya',
            'judul' => 'Contoh Laporan Pelanggaran',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2026-08-01',
            'lokasi' => 'BBSPJIKKP',
        ]);

        $response->assertSessionHasErrors(['nama_terlapor', 'jabatan_terlapor', 'unit_terlapor']);
    }

    /**
     * Test Modul Tracking (Revision 3)
     */
    public function test_tracking_uses_nomor_registrasi_and_hides_tracking_token()
    {
        $laporan = Laporan::create([
            'kategori_id' => 1,
            'nomor_registrasi' => 'WBS-2026-0001',
            'tracking_token' => 'secret-internal-token-xyz',
            'sumber_informasi' => 'Website WBS',
            'judul' => 'Judul Laporan Kasus Korupsi',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2026-08-01',
            'lokasi' => 'BBSPJIKKP',
            'nama_terlapor' => 'John Doe',
            'jabatan_terlapor' => 'Staf',
            'unit_terlapor' => 'Unit IT',
            'status' => 'menunggu',
            'is_anonim' => true,
        ]);

        // Success tracking page access
        $response = $this->get(route('track.show', $laporan->nomor_registrasi));
        $response->assertStatus(200);
        $response->assertSee('WBS-2026-0001');
        $response->assertDontSee('secret-internal-token-xyz');
    }

    /**
     * Test Modul FAQ (Revision 4)
     */
    public function test_dynamic_faqs_on_landing_page()
    {
        Faq::create([
            'question' => 'Pertanyaan Kustom?',
            'answer' => 'Jawaban Kustom.',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Pertanyaan Kustom?');
        $response->assertSee('Jawaban Kustom.');
    }

    /**
     * Test Modul Monitoring (Revision 5)
     */
    public function test_monitoring_year_filtering_and_export_route()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        Laporan::create([
            'kategori_id' => 1,
            'nomor_registrasi' => 'WBS-2025-0001',
            'tracking_token' => 'token-2025',
            'sumber_informasi' => 'Website WBS',
            'judul' => 'Laporan 2025',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2025-05-10',
            'lokasi' => 'BBSPJIKKP',
            'nama_terlapor' => 'John 2025',
            'jabatan_terlapor' => 'Staf',
            'unit_terlapor' => 'Unit IT',
            'status' => 'menunggu',
            'created_at' => '2025-05-10 10:00:00',
        ]);

        Laporan::create([
            'kategori_id' => 1,
            'nomor_registrasi' => 'WBS-2026-0002',
            'tracking_token' => 'token-2026',
            'sumber_informasi' => 'Website WBS',
            'judul' => 'Laporan 2026',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2026-08-11',
            'lokasi' => 'BBSPJIKKP',
            'nama_terlapor' => 'John 2026',
            'jabatan_terlapor' => 'Staf',
            'unit_terlapor' => 'Unit IT',
            'status' => 'menunggu',
            'created_at' => '2026-08-11 12:00:00',
        ]);

        // Access monitoring with year filter
        $response = $this->get(route('monitoring.index', ['year' => '2026']));
        $response->assertStatus(200);

        // Access export route
        $exportResponse = $this->get(route('monitoring.export', ['year' => '2026']));
        $exportResponse->assertStatus(200);
        $exportResponse->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * Test Modul Investigasi (Revision 6)
     */
    public function test_investigation_optional_file_upload_and_download()
    {
        Storage::fake('local');

        $investigator = User::factory()->create();
        $investigator->assignRole('investigator');
        $this->actingAs($investigator);

        $laporan = Laporan::create([
            'kategori_id' => 1,
            'nomor_registrasi' => 'WBS-2026-0003',
            'tracking_token' => 'token-investigation',
            'sumber_informasi' => 'Website WBS',
            'judul' => 'Laporan Investigasi',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2026-08-11',
            'lokasi' => 'BBSPJIKKP',
            'nama_terlapor' => 'John Doe',
            'jabatan_terlapor' => 'Staf',
            'unit_terlapor' => 'Unit IT',
            'status' => 'investigasi',
        ]);

        $investigation = Investigation::create([
            'laporan_id' => $laporan->id,
            'investigator_id' => $investigator->id,
            'assigned_by' => 1,
            'assigned_at' => now(),
            'status' => 'in_progress',
        ]);

        $fileHasil = UploadedFile::fake()->create('hasil.pdf', 200, 'application/pdf');
        $fileRekom = UploadedFile::fake()->create('rekomendasi.docx', 300, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->post(route('investigations.update-result', $investigation->id), [
            'final_result' => 'Ditemukan bukti yang cukup kuat atas pelanggaran.',
            'recommendation' => 'Pemberian sanksi hukuman disiplin berat.',
            'dokumen_hasil_akhir' => $fileHasil,
            'dokumen_rekomendasi' => $fileRekom,
        ]);

        $response->assertRedirect(route('investigations.show', $investigation->id));

        $investigation->refresh();
        $this->assertNotNull($investigation->dokumen_hasil_akhir);
        $this->assertNotNull($investigation->dokumen_rekomendasi);

        Storage::disk('local')->assertExists($investigation->dokumen_hasil_akhir);
        Storage::disk('local')->assertExists($investigation->dokumen_rekomendasi);

        // Test download route
        $downloadResponse = $this->get(route('investigations.download-document-field', [
            'id' => $investigation->id,
            'field' => 'dokumen_hasil_akhir'
        ]));
        $downloadResponse->assertStatus(200);
    }

    /**
     * Test Modul Audit Log Command (Revision 7)
     */
    public function test_clear_old_audit_logs_command()
    {
        // Insert directly using DB facade to set exact created_at timestamps without Eloquent interference
        DB::table('audit_logs')->insert([
            [
                'user_id' => 1,
                'action' => 'Kategori kustom',
                'description' => 'Test log old',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla',
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subYears(3),
            ],
            [
                'user_id' => 1,
                'action' => 'Kategori kustom 2',
                'description' => 'Test log new',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla',
                'created_at' => now()->subYear(),
                'updated_at' => now()->subYear(),
            ]
        ]);

        $this->assertEquals(2, AuditLog::count());

        // Run clear command
        Artisan::call('audit-logs:clear');

        $this->assertEquals(1, AuditLog::count());
        $this->assertEquals('Test log new', AuditLog::first()->description);
    }

    /**
     * Test Custom Sumber Informasi (Revision 1 - Additional)
     */
    public function test_custom_sumber_informasi_is_saved_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('laporan.store'), [
            'kategori_id' => 1,
            'judul' => 'Contoh Laporan Pelanggaran Kustom',
            'deskripsi' => 'Deskripsi pelanggaran terperinci yang cukup panjang untuk memenuhi validasi minimal lima puluh karakter.',
            'tanggal_kejadian' => '2026-08-01',
            'lokasi' => 'BBSPJIKKP',
            'nama_terlapor' => 'Target Person',
            'jabatan_terlapor' => 'Staff',
            'unit_terlapor' => 'Kepegawaian',
            'is_anonim' => '0',
            'nama_pelapor' => 'Reporter Person',
            'email_pelapor' => 'reporter@example.com',
            'telepon_pelapor' => '0812345678',
            'custom_fields' => [
                'sumber_informasi' => 'Lainnya',
            ],
            'sumber_informasi_kustom' => 'Mendapat Surat Kaleng',
        ]);

        $response->assertRedirect(route('laporan.sukses'));

        $laporan = Laporan::latest('id')->first();
        $this->assertNotNull($laporan);
        $this->assertArrayHasKey('sumber_informasi', $laporan->custom_fields);
        $this->assertEquals('Mendapat Surat Kaleng', $laporan->custom_fields['sumber_informasi']['value']);
    }

    /**
     * Test Superadmin FAQ CRUD (Revision 4 - Additional)
     */
    public function test_superadmin_can_manage_faq_master_data()
    {
        $superadmin = User::role('super-admin')->first();
        if (!$superadmin) {
            $superadmin = User::factory()->create();
            $superadmin->assignRole('super-admin');
        }

        $this->actingAs($superadmin);

        // 1. Create FAQ
        $response = $this->post(route('master-data.faq.store'), [
            'question' => 'Pertanyaan Baru?',
            'answer' => 'Jawaban Baru.',
            'order' => 5,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('master-data.faq.index'));
        $this->assertDatabaseHas('faqs', [
            'question' => 'Pertanyaan Baru?',
            'answer' => 'Jawaban Baru.',
            'order' => 5,
            'is_active' => true,
        ]);

        $faq = Faq::where('question', 'Pertanyaan Baru?')->first();

        // 2. Update FAQ
        $response = $this->put(route('master-data.faq.update', $faq->id), [
            'question' => 'Pertanyaan Diedit?',
            'answer' => 'Jawaban Diedit.',
            'order' => 2,
            'is_active' => 0,
        ]);

        $response->assertRedirect(route('master-data.faq.index'));
        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Pertanyaan Diedit?',
            'answer' => 'Jawaban Diedit.',
            'order' => 2,
            'is_active' => false,
        ]);

        // 3. Delete FAQ
        $response = $this->delete(route('master-data.faq.destroy', $faq->id));
        $response->assertRedirect(route('master-data.faq.index'));
        $this->assertDatabaseMissing('faqs', [
            'id' => $faq->id,
        ]);
    }
}
