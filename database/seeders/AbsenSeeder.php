<?php

namespace Database\Seeders;

use App\Models\Absen;
use Illuminate\Database\Seeder;

class AbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = new \DateTime('2024-01-01');
        $endDate = new \DateTime('2024-12-31');

        while ($startDate <= $endDate) {
            Absen::create([
                'karyawan_id' => 3,
                'tanggal' => $startDate->format('Y-m-d'),
                'jam_masuk' => $this->generateRandomTime('06:00:00', '08:00:00'),
                'jam_keluar' => $this->generateRandomTime('14:00:00', '16:00:00'),
            ]);

            $startDate->modify('+1 day');
        }
    }

    public function rollback(): void
    {
        Absen::where('karyawan_id', 3)
            ->whereBetween('tanggal', ['2024-01-01', '2024-12-31'])
            ->delete();
    }

    /**
     * Generate a random time between two times.
     */
    private function generateRandomTime($startTime, $endTime)
    {
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);

        return date('H:i:s', rand($startTimestamp, $endTimestamp));
    }
}
