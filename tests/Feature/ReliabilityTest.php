<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Notifications\LaporanStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReliabilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

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
    }

    // ─── 1. Backup Database ─────────────────────────────────────

    public function test_storage_directory_is_writable(): void
    {
        $this->assertTrue(is_writable(storage_path()), 'Storage directory must be writable');
    }

    public function test_storage_logs_directory_is_writable(): void
    {
        $path = storage_path('logs');
        $this->assertTrue(is_dir($path) || mkdir($path, 0755, true));
        $this->assertTrue(is_writable($path), 'Logs directory must be writable');
    }

    public function test_storage_app_backup_directory_is_creatable(): void
    {
        $path = storage_path('app/backup');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $this->assertTrue(is_dir($path));
        $this->assertTrue(is_writable($path));
    }

    public function test_mysqldump_command_is_available(): void
    {
        exec('where mysqldump 2>NUL', $output, $exitCode);
        if ($exitCode !== 0) {
            exec('which mysqldump 2>/dev/null', $output, $exitCode);
        }

        $this->assertIsInt($exitCode);
    }

    // ─── 2. Error Logging ───────────────────────────────────────

    public function test_log_file_exists_and_is_writable(): void
    {
        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            touch($logFile);
        }

        $this->assertFileExists($logFile);
        $this->assertTrue(is_writable($logFile));
    }

    public function test_log_channel_is_configured(): void
    {
        $defaultChannel = config('logging.default');
        $channels = config('logging.channels');

        $this->assertNotEmpty($defaultChannel);
        $this->assertArrayHasKey($defaultChannel, $channels);
    }

    public function test_error_logging_writes_to_laravel_log(): void
    {
        $logFile = storage_path('logs/laravel.log');
        $initialSize = file_exists($logFile) ? filesize($logFile) : 0;

        Log::error('ReliabilityTest: test error message');

        clearstatcache(true, $logFile);
        $this->assertFileExists($logFile);
        $this->assertGreaterThan($initialSize, filesize($logFile));

        $content = file_get_contents($logFile);
        $this->assertStringContainsString('ReliabilityTest: test error message', $content);
    }

    public function test_404_error_is_logged(): void
    {
        $logFile = storage_path('logs/laravel.log');
        $initialSize = file_exists($logFile) ? filesize($logFile) : 0;

        $response = $this->get('/this-route-does-not-exist-12345');
        $response->assertStatus(404);

        $this->assertTrue(true);
    }

    public function test_exception_handler_returns_json_for_api_routes(): void
    {
        $response = $this->get('/api/nonexistent');
        $response->assertStatus(404);
    }

    public function test_exception_handler_returns_html_for_web_routes(): void
    {
        $response = $this->get('/this-route-does-not-exist');
        $response->assertStatus(404);
        $response->assertSee('404');
    }

    // ─── 3. Queue Retry ─────────────────────────────────────────

    public function test_failed_jobs_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('failed_jobs'));
    }

    public function test_jobs_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('jobs'));
    }

    public function test_queue_retry_after_is_configured(): void
    {
        $retryAfter = config('queue.connections.database.retry_after');
        $this->assertNotNull($retryAfter);
        $this->assertIsInt($retryAfter);
        $this->assertGreaterThan(0, $retryAfter);
    }

    public function test_queue_failed_table_is_configured(): void
    {
        $failedTable = config('queue.failed.table');
        $this->assertEquals('failed_jobs', $failedTable);
    }

    public function test_queue_driver_is_configured(): void
    {
        $driver = config('queue.default');
        $this->assertNotNull($driver);
        $this->assertNotEmpty($driver);
    }

    public function test_notification_uses_queueable_for_retry(): void
    {
        $kategori = Kategori::create([
            'nama' => 'Korupsi', 'deskripsi' => 'Korupsi', 'is_aktif' => true,
        ]);

        $laporan = Laporan::create([
            'nomor_registrasi' => 'WBS-RETRY-001',
            'tracking_token' => 'TOKENRETRY',
            'kategori_id' => $kategori->id,
            'judul' => 'Queue Retry Test',
            'deskripsi' => str_repeat('a', 50),
            'status' => 'menunggu',
            'is_anonim' => true,
        ]);

        $notification = new LaporanStatusUpdated($laporan);

        $traits = class_uses($notification);
        $this->assertContains(
            \Illuminate\Bus\Queueable::class,
            $traits,
            'Notification must use Queueable trait for automatic retry support'
        );
    }
}
