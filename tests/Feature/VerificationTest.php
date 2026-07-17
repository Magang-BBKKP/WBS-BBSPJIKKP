<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use App\Models\LaporanTimeline;
use App\Models\LaporanMessage;
use App\Models\Kategori;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $wbsUser;
    protected User $normalUser;
    protected Laporan $laporan;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Create Users
        $this->wbsUser = User::factory()->create([
            'email' => 'wbs@bbspjikkp.go.id',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->wbsUser->assignRole('tim-wbs');

        $this->normalUser = User::factory()->create([
            'email' => 'normal@example.com',
            'is_active' => true,
            'status' => 'active',
        ]);

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
            'judul' => 'Dugaan Gratifikasi',
            'deskripsi' => 'Ini deskripsi pengaduan dugaan gratifikasi di lingkungan lab uji ban.',
            'status' => 'menunggu',
            'is_anonim' => true,
        ]);
    }

    /**
     * Test middleware restrictions
     */
    public function test_only_tim_wbs_can_access_verification(): void
    {
        // Guest gets redirected to login
        $this->get(route('verifikasi.index'))
            ->assertRedirect(route('login'));

        // Normal user gets 403 Forbidden
        $this->actingAs($this->normalUser)
            ->get(route('verifikasi.index'))
            ->assertStatus(403);

        // Tim WBS gets 200 OK
        $this->actingAs($this->wbsUser)
            ->get(route('verifikasi.index'))
            ->assertStatus(200);
    }

    /**
     * Test automatic status transition from menunggu -> verifikasi on view details
     */
    public function test_automatic_transition_on_view_details(): void
    {
        $this->actingAs($this->wbsUser)
            ->get(route('verifikasi.show', $this->laporan->id))
            ->assertStatus(200);

        $this->laporan->refresh();
        $this->assertEquals('verifikasi', $this->laporan->status);
        $this->assertEquals('under_verification', $this->laporan->verification_status);

        $this->assertDatabaseHas('laporan_timelines', [
            'laporan_id' => $this->laporan->id,
            'status' => 'verifikasi',
            'title' => 'Laporan Ditinjau',
        ]);
    }

    /**
     * Test validating a report (Action A)
     */
    public function test_validate_report(): void
    {
        // First view to transition to verifikasi
        $this->actingAs($this->wbsUser)->get(route('verifikasi.show', $this->laporan->id));

        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.validate', $this->laporan->id), [
                'verification_note' => 'Dokumen bukti valid.',
            ]);

        $response->assertRedirect(route('verifikasi.index'));
        
        $this->laporan->refresh();
        $this->assertEquals('valid', $this->laporan->status);
        $this->assertEquals('verified', $this->laporan->verification_status);
        $this->assertEquals($this->wbsUser->id, $this->laporan->verified_by);
        $this->assertEquals('Dokumen bukti valid.', $this->laporan->verification_note);

        $this->assertDatabaseHas('laporan_timelines', [
            'laporan_id' => $this->laporan->id,
            'status' => 'valid',
            'title' => 'Laporan Terverifikasi',
        ]);
    }

    /**
     * Test asking for clarification (Action B)
     */
    public function test_clarification_report_and_response(): void
    {
        // First view to transition to verifikasi
        $this->actingAs($this->wbsUser)->get(route('verifikasi.show', $this->laporan->id));

        // Request clarification
        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.clarify', $this->laporan->id), [
                'clarification_message' => 'Tolong lampirkan bukti foto tambahan.',
            ]);

        $response->assertRedirect(route('verifikasi.index'));

        $this->laporan->refresh();
        $this->assertEquals('menunggu', $this->laporan->status);
        $this->assertEquals('waiting_clarification', $this->laporan->verification_status);
        $this->assertEquals('Tolong lampirkan bukti foto tambahan.', $this->laporan->clarification_message);

        // Check secure chat message was created
        $this->assertDatabaseHas('laporan_messages', [
            'laporan_id' => $this->laporan->id,
            'sender_type' => 'investigator',
            'content' => '[PERMINTAAN KLARIFIKASI TIM WBS]: Tolong lampirkan bukti foto tambahan.',
        ]);

        // Pelapor replies to restore status
        $responseReply = $this->post(route('track.message.store', $this->laporan->tracking_token), [
            'message' => 'Ini jawaban saya.',
        ]);

        $this->laporan->refresh();
        $this->assertEquals('menunggu', $this->laporan->status);
        $this->assertEquals('submitted', $this->laporan->verification_status);

        $this->assertDatabaseHas('laporan_timelines', [
            'laporan_id' => $this->laporan->id,
            'status' => 'menunggu',
            'title' => 'Jawaban Klarifikasi Dikirim',
        ]);
    }

    /**
     * Test rejecting a report (Action C)
     */
    public function test_reject_report(): void
    {
        // First view to transition to verifikasi
        $this->actingAs($this->wbsUser)->get(route('verifikasi.show', $this->laporan->id));

        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.reject', $this->laporan->id), [
                'rejection_reason' => 'Laporan tidak lengkap/kurang bukti',
                'verification_note' => 'Tidak dilampiri file apapun.',
            ]);

        $response->assertRedirect(route('verifikasi.index'));

        $this->laporan->refresh();
        $this->assertEquals('ditolak', $this->laporan->status);
        $this->assertEquals('rejected', $this->laporan->verification_status);
        $this->assertEquals('Laporan tidak lengkap/kurang bukti', $this->laporan->rejection_reason);

        $this->assertDatabaseHas('laporan_timelines', [
            'laporan_id' => $this->laporan->id,
            'status' => 'ditolak',
            'title' => 'Laporan Ditolak',
        ]);
    }

    /**
     * Test invalid status change constraints
     */
    public function test_cannot_modify_completed_report(): void
    {
        $this->laporan->update(['status' => 'selesai']);

        // Try to validate
        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.validate', $this->laporan->id), [
                'verification_note' => 'Note',
            ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_cannot_validate_rejected_report(): void
    {
        $this->laporan->update([
            'status' => 'ditolak',
            'verification_status' => 'rejected'
        ]);

        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.validate', $this->laporan->id), [
                'verification_note' => 'Note',
            ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_cannot_clarify_verified_report(): void
    {
        $this->laporan->update([
            'status' => 'valid',
            'verification_status' => 'verified'
        ]);

        $response = $this->actingAs($this->wbsUser)
            ->post(route('verifikasi.clarify', $this->laporan->id), [
                'clarification_message' => 'Clarification msg',
            ]);

        $response->assertSessionHasErrors('status');
    }
}
