<?php

namespace Database\Seeders;

use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-1',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '15:00:00',
            'keterangan' => 'Masuk Normal dan Keluar Normal'
        ]); //sudah 1

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-2',
            'jam_masuk' => '06:45:00',
            'jam_keluar' => '15:00:00',
            'keterangan' => 'Masuk Sebelum Batas dan Keluar Normal'
        ]); //sudah 2

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-3',
            'jam_masuk' => '07:14:00',
            'jam_keluar' => '15:00:00',
            'keterangan' => 'Masuk Telat 1 Jam dan Keluar Normal'
        ]); //sudah 3

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-4',
            'jam_masuk' => '08:05:00',
            'jam_keluar' => '15:00:00',
            'keterangan' => 'Masuk Telat 2 Jam dan Keluar Normal'
        ]); //sudah 3

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-5',
            'jam_masuk' => '09:30:00',
            'jam_keluar' => '15:00:00',
            'keterangan' => 'Masuk Telat 3 Jam dan Keluar Normal'
        ]); //sudah 3

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-6',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '15:09:00',
            'keterangan' => 'Masuk Normal dan Keluar Melebihi Batas'
        ]); //sudah 4

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-7',
            'jam_masuk' => '06:30:00',
            'jam_keluar' => '15:11:00',
            'keterangan' => 'Masuk Sebelum Batas dan Keluar Melebihi Batas'
        ]); //sudah 5

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-8',
            'jam_masuk' => '07:30:00',
            'jam_keluar' => '15:20:00',
            'keterangan' => 'Masuk Telat 1 Jam dan Pulang Melebihi Batas'
        ]);//sudah 6

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-9',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '14:12:00',
            'keterangan' => 'Masuk Normal dan Keluar Bolos 1 Jam'
        ]); //sudah 7

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-10',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '13:24:00',
            'keterangan' => 'Masuk Normal dan Keluar Bolos 2 Jam'
        ]); //sudah 7

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-11',
            'jam_masuk' => '07:00:00',
            'jam_keluar' => '13:24:00',
            'keterangan' => 'Masuk Normal dan Keluar Bolos 3 Jam'
        ]); //sudah 7

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-12',
            'jam_masuk' => '06:30:00',
            'jam_keluar' => '15:11:00',
            'keterangan' => 'Masuk Sebelum Batas dan Keluar Bolos 1 Jam'
        ]); //sudah 8

        Absen::create([
            'karyawan_id' => 1,
            'tanggal' => '2024-12-13',
            'jam_masuk' => '07:10:00',
            'jam_keluar' => '14:09:00',
            'keterangan' => 'Masuk Telat 1 Jam dan Keluar Bolos 1 Jam'
        ]); //sudah 9

        $startDate = new \DateTime('2024-01-01');
        $endDate = new \DateTime('2024-12-31');
        $currentWeek = 1; // Counter to ensure only 52 weeks of workdays

        while ($startDate <= $endDate && $currentWeek <= 52) {
            // Check if the day is Monday to Friday
            if (in_array($startDate->format('N'), [1, 2, 3, 4, 5])) {
                Absen::create([
                    'karyawan_id' => 2,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'jam_masuk' => $this->generateRandomTime('06:00:00', '08:00:00'),
                    'jam_keluar' => $this->generateRandomTime('14:00:00', '16:00:00'),
                ]);

                Absen::create([
                    'karyawan_id' => 3,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'jam_masuk' => $this->generateRandomTime('06:00:00', '08:00:00'),
                    'jam_keluar' => $this->generateRandomTime('14:00:00', '16:00:00'),
                ]);

                Absen::create([
                    'karyawan_id' => 4,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'jam_masuk' => $this->generateRandomTime('06:00:00', '08:00:00'),
                    'jam_keluar' => $this->generateRandomTime('14:00:00', '16:00:00'),
                ]);

                Absen::create([
                    'karyawan_id' => 5,
                    'tanggal' => $startDate->format('Y-m-d'),
                    'jam_masuk' => $this->generateRandomTime('06:00:00', '08:00:00'),
                    'jam_keluar' => $this->generateRandomTime('14:00:00', '16:00:00'),
                ]);

                // Increment week counter if it's Friday
                if ($startDate->format('N') == 5) {
                    $currentWeek++;
                }
            }

            // Move to the next day
            $startDate->modify('+1 day');
        }

    }

    public function rollback(): void
    {
        Absen::where('karyawan_id', 2)
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
