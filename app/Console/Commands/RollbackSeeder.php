<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absen;

class RollbackSeeder extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:rollback-absen-seeder';

    /**
     * The console command description.
     */
    protected $description = 'Rollback AbsenSeeder by deleting seeded data.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Rolling back AbsenSeeder...');

        $deletedRows = Absen::where('karyawan_id', 3)
            ->whereBetween('tanggal', ['2024-01-01', '2024-12-31'])
            ->delete();

        $this->info("Deleted {$deletedRows} rows from absens.");
    }
}
