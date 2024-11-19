<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'nik' => '3507123673849209',
            'nama' => 'Budie Arie Setiadi',
            'tanggal_lahir' => '1987-08-07',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Pahlawan No. 1',
            'agama' => 'Islam',
            'no_telepon' => '081234567890',
            'email' => 'kominfo@email.com',
            'posisi_id' => 1,
        ]);

        Karyawan::create([
            'nik' => '3507126374819228',
            'nama' => 'Ayu Marshanda',
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Sudirman No. 2',
            'agama' => 'Kristen',
            'no_telepon' => '089876543210',
            'email' => 'ayu@email.com',
            'posisi_id' => 2, // Pastikan posisi_id ini valid di tabel posisis
        ]);

        Karyawan::create([
            'nik' => '3507122113356700',
            'nama' => 'Karina Pocca',
            'tanggal_lahir' => '2002-02-02',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Surabaya No. 2',
            'agama' => 'Kristen',
            'no_telepon' => '0877224353210',
            'email' => 'pocca@email.com',
            'posisi_id' => 2, // Pastikan posisi_id ini valid di tabel posisis
        ]);

        Karyawan::create([
            'nik' => '3507123673666543',
            'nama' => 'Bjorka El Hacker',
            'tanggal_lahir' => '1987-07-08',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Bjorka No. 1',
            'agama' => 'Lainnya',
            'no_telepon' => '081234998876',
            'email' => 'bjorka@email.com',
            'posisi_id' => 3, // Pastikan posisi_id ini valid di tabel posisis
        ]);

        Karyawan::create([
            'nik' => '3507125563749287',
            'nama' => 'P. Diddy',
            'tanggal_lahir' => '1969-06-09',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Baby Oil No. 1',
            'agama' => 'Lainnya',
            'no_telepon' => '087789898876',
            'email' => 'pdiddy@email.com',
            'posisi_id' => 3, // Pastikan posisi_id ini valid di tabel posisis
        ]);

    }
}
