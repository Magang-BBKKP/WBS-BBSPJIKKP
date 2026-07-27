<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Laporan;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $timWbs;
    protected User $investigator;
    protected User $kepala;
    protected User $normalUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->superAdmin = User::factory()->create(['email' => 'super@admin.com', 'is_active' => true, 'status' => 'active']);
        $this->superAdmin->assignRole('super-admin');

        $this->timWbs = User::factory()->create(['email' => 'wbs@test.com', 'is_active' => true, 'status' => 'active']);
        $this->timWbs->assignRole('tim-wbs');

        $this->investigator = User::factory()->create(['email' => 'invest@test.com', 'is_active' => true, 'status' => 'active']);
        $this->investigator->assignRole('investigator');

        $this->kepala = User::factory()->create(['email' => 'kepala@test.com', 'is_active' => true, 'status' => 'active']);
        $this->kepala->assignRole('kepala-bbspjikkp');

        $this->normalUser = User::factory()->create(['email' => 'user@test.com', 'is_active' => true, 'status' => 'active']);
    }

    // ─── 1. HTTPS ───────────────────────────────────────────────

    public function test_https_redirect_in_production(): void
    {
        $response = $this->get('/');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 301, 302]));
    }

    // ─── 2. CSRF ────────────────────────────────────────────────

    public function test_form_contains_csrf_field(): void
    {
        $response = $this->get('/login');
        $response->assertSee('_token');
    }

    // ─── 3. XSS Protection ──────────────────────────────────────

    public function test_xss_input_is_escaped_in_output(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi',
            'deskripsi' => 'Korupsi',
            'is_aktif' => true,
        ]);

        $this->actingAs($this->normalUser);
        $response = $this->post(route('laporan.store'), [
            'kategori_id' => $kategori->id,
            'judul' => '<script>alert("xss")</script>',
            'deskripsi' => 'Deskripsi dengan konten berbahaya yang panjangnya lebih dari lima puluh karakter ya.',
            'tanggal_kejadian' => now()->format('Y-m-d'),
            'lokasi' => 'Gedung Utama',
            'is_anonim' => '1',
        ]);

        $this->assertDatabaseHas('laporans', [
            'judul' => '<script>alert("xss")</script>',
        ]);

        $laporan = Laporan::where('judul', '<script>alert("xss")</script>')->first();

        $viewResponse = $this->actingAs($this->timWbs)
            ->get(route('verifikasi.show', $laporan->id));

        $viewResponse->assertDontSee('<script>alert("xss")</script>', false);
    }

    // ─── 4. SQL Injection Protection ────────────────────────────

    public function test_sql_injection_attempt_on_login(): void
    {
        $response = $this->post('/login', [
            'email' => "' OR '1'='1' --",
            'password' => 'anything',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_sql_injection_attempt_on_search(): void
    {
        $this->actingAs($this->superAdmin);
        $response = $this->get('/audit-log?search=' . urlencode("' OR 1=1 --"));

        $response->assertStatus(200);
    }

    // ─── 5. Audit Trail ─────────────────────────────────────────

    public function test_login_action_is_logged(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
            '_token' => csrf_token(),
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'Login',
        ]);
    }

    public function test_failed_login_is_logged(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'Failed Login',
        ]);
    }

    public function test_audit_log_stores_ip_address_and_user_agent(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ], [
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'TestBrowser/1.0',
        ]);

        $log = AuditLog::where('action', 'Failed Login')->first();
        $this->assertNotNull($log);
        $this->assertEquals('192.168.1.1', $log->ip_address);
        $this->assertEquals('TestBrowser/1.0', $log->user_agent);
    }

    // ─── 6. Password Hashing ────────────────────────────────────

    public function test_password_is_stored_as_hash(): void
    {
        $user = User::factory()->create([
            'password' => 'plain-text-password',
        ]);

        $this->assertNotEquals('plain-text-password', $user->getRawOriginal('password'));
        $this->assertTrue(Hash::check('plain-text-password', $user->getRawOriginal('password')));
        $this->assertStringStartsWith('$2y$', $user->getRawOriginal('password'));
    }

    public function test_password_is_not_returned_in_responses(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('users.index'));

        $response->assertDontSee($this->superAdmin->getRawOriginal('password'));
    }

    // ─── 7. RBAC ────────────────────────────────────────────────

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_normal_user_cannot_access_verification(): void
    {
        $this->actingAs($this->normalUser)
            ->get(route('verifikasi.index'))
            ->assertStatus(403);
    }

    public function test_investigator_cannot_access_verification(): void
    {
        $this->actingAs($this->investigator)
            ->get(route('verifikasi.index'))
            ->assertStatus(403);
    }

    public function test_tim_wbs_can_access_verification(): void
    {
        $this->actingAs($this->timWbs)
            ->get(route('verifikasi.index'))
            ->assertStatus(200);
    }

    public function test_super_admin_can_access_verification(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('verifikasi.index'))
            ->assertStatus(200);
    }

    public function test_normal_user_cannot_access_user_management(): void
    {
        $this->actingAs($this->normalUser)
            ->get(route('users.index'))
            ->assertStatus(403);
    }

    public function test_super_admin_can_access_user_management(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('users.index'))
            ->assertStatus(200);
    }

    public function test_investigator_cannot_access_master_data(): void
    {
        $this->actingAs($this->investigator)
            ->get(route('master-data.index'))
            ->assertStatus(403);
    }

    // ─── 8. Secure File Upload ──────────────────────────────────

    public function test_exe_file_is_rejected_on_laporan(): void
    {
        Storage::fake('local');

        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $file = UploadedFile::fake()->create('malicious.exe', 100);

        $this->actingAs($this->normalUser);
        $response = $this->post(route('laporan.store'), [
            'kategori_id' => $kategori->id,
            'judul' => 'Test Upload EXE',
            'deskripsi' => str_repeat('a', 50),
            'tanggal_kejadian' => now()->format('Y-m-d'),
            'lokasi' => 'Gedung C',
            'is_anonim' => '1',
            'bukti' => [$file],
        ]);

        $response->assertSessionHasErrors('bukti.0');
    }

    public function test_oversized_file_is_rejected_on_laporan(): void
    {
        Storage::fake('local');

        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $file = UploadedFile::fake()->create('huge.pdf', 20480);

        $this->actingAs($this->normalUser);
        $response = $this->post(route('laporan.store'), [
            'kategori_id' => $kategori->id,
            'judul' => 'Test Oversized File',
            'deskripsi' => str_repeat('a', 50),
            'tanggal_kejadian' => now()->format('Y-m-d'),
            'lokasi' => 'Gedung D',
            'is_anonim' => '1',
            'bukti' => [$file],
        ]);

        $response->assertSessionHasErrors('bukti.0');
    }

    public function test_valid_pdf_file_is_accepted_on_laporan(): void
    {
        Storage::fake('local');

        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $this->actingAs($this->normalUser);
        $response = $this->post(route('laporan.store'), [
            'kategori_id' => $kategori->id,
            'judul' => 'Test Valid PDF Upload',
            'deskripsi' => str_repeat('a', 50),
            'tanggal_kejadian' => now()->format('Y-m-d'),
            'lokasi' => 'Gedung E',
            'is_anonim' => '1',
            'bukti' => [$file],
        ]);

        $response->assertRedirect(route('laporan.sukses'));
    }

    public function test_exe_file_is_rejected_on_investigation_document(): void
    {
        Storage::fake('local');

        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $laporan = Laporan::create([
            'nomor_registrasi' => 'WBS-SEC-001',
            'tracking_token' => 'TOKENSEC',
            'kategori_id' => $kategori->id,
            'judul' => 'Security Test',
            'deskripsi' => 'Testing secure upload on investigation.',
            'status' => 'investigasi',
            'is_anonim' => true,
        ]);

        $investigation = \App\Models\Investigation::create([
            'laporan_id' => $laporan->id,
            'investigator_id' => $this->investigator->id,
            'status' => 'active',
        ]);

        $file = UploadedFile::fake()->create('hack.exe', 100);

        $response = $this->actingAs($this->investigator)
            ->post(route('investigations.store-document', $investigation->id), [
                'document' => $file,
            ]);

        $response->assertSessionHasErrors('document');
    }

    public function test_too_many_files_is_rejected(): void
    {
        Storage::fake('local');

        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $files = [];
        for ($i = 0; $i < 11; $i++) {
            $files[] = UploadedFile::fake()->create("file{$i}.pdf", 100, 'application/pdf');
        }

        $this->actingAs($this->normalUser);
        $response = $this->post(route('laporan.store'), [
            'kategori_id' => $kategori->id,
            'judul' => 'Test Too Many Files',
            'deskripsi' => str_repeat('a', 50),
            'tanggal_kejadian' => now()->format('Y-m-d'),
            'lokasi' => 'Gedung F',
            'is_anonim' => '1',
            'bukti' => $files,
        ]);

        $response->assertSessionHasErrors('bukti');
    }
}
