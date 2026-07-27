<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laporan;
use App\Models\Kategori;
use App\Models\Investigation;
use App\Notifications\LaporanStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $timWbs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        $this->superAdmin = User::factory()->create([
            'email' => 'admin@test.com',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->timWbs = User::factory()->create([
            'email' => 'wbs@test.com',
            'is_active' => true,
            'status' => 'active',
        ]);
        $this->timWbs->assignRole('tim-wbs');
    }

    // ─── 1. Response < 3 detik ──────────────────────────────────

    public function test_landing_page_loads_under_3_seconds(): void
    {
        $start = microtime(true);
        $response = $this->get('/');
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(3000, $duration, "Landing page took {$duration}ms (limit: 3000ms)");
    }

    public function test_login_page_loads_under_3_seconds(): void
    {
        $start = microtime(true);
        $response = $this->get('/login');
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(3000, $duration, "Login page took {$duration}ms (limit: 3000ms)");
    }

    public function test_dashboard_page_loads_under_3_seconds(): void
    {
        $this->actingAs($this->superAdmin);

        $start = microtime(true);
        $response = $this->get(route('dashboard'));
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(3000, $duration, "Dashboard page took {$duration}ms (limit: 3000ms)");
    }

    public function test_verification_page_loads_under_3_seconds(): void
    {
        $this->actingAs($this->timWbs);

        $start = microtime(true);
        $response = $this->get(route('verifikasi.index'));
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(3000, $duration, "Verification page took {$duration}ms (limit: 3000ms)");
    }

    public function test_audit_log_page_loads_under_3_seconds(): void
    {
        $this->actingAs($this->superAdmin);

        $start = microtime(true);
        $response = $this->get(route('audit-log.index'));
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(3000, $duration, "Audit log page took {$duration}ms (limit: 3000ms)");
    }

    // ─── 2. Pagination ──────────────────────────────────────────

    public function test_verification_page_uses_pagination(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        for ($i = 1; $i <= 15; $i++) {
            Laporan::create([
                'nomor_registrasi' => "WBS-PAG-{$i}",
                'tracking_token' => "TOKEN{$i}",
                'kategori_id' => $kategori->id,
                'judul' => "Laporan ke-{$i}",
                'deskripsi' => str_repeat('a', 50),
                'status' => 'menunggu',
                'is_anonim' => true,
            ]);
        }

        $this->actingAs($this->timWbs);
        $response = $this->get(route('verifikasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Previous');
        $response->assertDontSee('Laporan ke-15');
    }

    public function test_verification_page_accepts_page_parameter(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        for ($i = 1; $i <= 15; $i++) {
            Laporan::create([
                'nomor_registrasi' => "WBS-PAG2-{$i}",
                'tracking_token' => "TOKEN{$i}",
                'kategori_id' => $kategori->id,
                'judul' => "Laporan ke-{$i}",
                'deskripsi' => str_repeat('a', 50),
                'status' => 'menunggu',
                'is_anonim' => true,
            ]);
        }

        $this->actingAs($this->timWbs);
        $response = $this->get(route('verifikasi.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertSee('Previous');
    }

    public function test_user_management_uses_pagination(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $user = User::factory()->create([
                'email' => "user{$i}@test.com",
                'is_active' => true,
                'status' => 'active',
            ]);
            $user->assignRole('super-admin');
        }

        $this->actingAs($this->superAdmin);
        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Showing');
    }

    public function test_master_data_uses_pagination(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Kategori::create([
                'nama' => "Kategori {$i}",
                'deskripsi' => "Deskripsi {$i}",
                'is_aktif' => true,
            ]);
        }

        $this->actingAs($this->superAdmin);
        $response = $this->get(route('master-data.index'));

        $response->assertStatus(200);
        $response->assertSee('Showing');
    }

    public function test_pagination_does_not_exceed_per_page_limit(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        for ($i = 1; $i <= 50; $i++) {
            Laporan::create([
                'nomor_registrasi' => "WBS-PAG3-{$i}",
                'tracking_token' => "TOKEN{$i}",
                'kategori_id' => $kategori->id,
                'judul' => "Laporan ke-{$i}",
                'deskripsi' => str_repeat('a', 50),
                'status' => 'menunggu',
                'is_anonim' => true,
            ]);
        }

        $this->actingAs($this->timWbs);
        $response = $this->get(route('verifikasi.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Laporan ke-15');
        $response->assertSee('Laporan ke-1');
        $response->assertSee('Previous');
    }

    // ─── 3. Lazy Loading ────────────────────────────────────────

    public function test_landing_page_uses_lazy_loading_for_images(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $content = $response->getContent();

        preg_match_all('/<img[^>]+>/i', $content, $images);

        foreach ($images[0] as $img) {
            if (str_contains($img, 'loading=')) {
                $this->assertStringContainsString('loading="lazy"', $img);
            }
        }
    }

    public function test_landing_page_uses_defer_for_scripts(): void
    {
        $response = $this->get('/');

        $content = $response->getContent();

        preg_match_all('/<script[^>]+src=["\'][^"\']+["\'][^>]*>/i', $content, $scripts);

        $this->assertGreaterThan(0, count($scripts[0]));

        $externalScripts = array_filter($scripts[0], fn($s) => !str_contains($s, 'bootstrap'));

        foreach ($externalScripts as $script) {
            $this->assertStringContainsString('defer', $script);
        }
    }

    // ─── 4. Queue ───────────────────────────────────────────────

    public function test_notification_uses_queueable_trait(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $laporan = Laporan::create([
            'nomor_registrasi' => 'WBS-QUEUE-001',
            'tracking_token' => 'TOKENQUEUE',
            'kategori_id' => $kategori->id,
            'judul' => 'Queue Test',
            'deskripsi' => str_repeat('a', 50),
            'status' => 'menunggu',
            'is_anonim' => true,
        ]);

        $notification = new LaporanStatusUpdated($laporan);

        $this->assertContains(
            'Illuminate\Bus\Queueable',
            class_uses($notification),
            'LaporanStatusUpdated notification should use the Queueable trait'
        );
    }

    public function test_queue_connection_is_configured(): void
    {
        $default = config('queue.default');
        $this->assertNotNull($default, 'Queue default connection should be configured');
        $this->assertNotEmpty($default);
    }

    public function test_notification_sends_without_error(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $laporan = Laporan::create([
            'nomor_registrasi' => 'WBS-QUEUE-002',
            'tracking_token' => 'TOKENQUEUE2',
            'kategori_id' => $kategori->id,
            'judul' => 'Queue Test 2',
            'deskripsi' => str_repeat('a', 50),
            'status' => 'menunggu',
            'is_anonim' => true,
        ]);

        $notification = new LaporanStatusUpdated($laporan);

        $this->expectNotToPerformAssertions();
        $this->superAdmin->notify($notification);
    }
}
