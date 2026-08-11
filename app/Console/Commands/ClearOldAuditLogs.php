<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class ClearOldAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit-logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete audit logs older than 2 years';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = now()->subYears(2);
        
        $deleted = AuditLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Successfully deleted {$deleted} old audit logs.");
    }
}
