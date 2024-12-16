<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewRekapAbsensi extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW view_rekap_absensi AS
            SELECT
                karyawan_id,
                DATE_FORMAT(tanggal, '%Y-%m') AS bulan, -- Mengambil bulan dan tahun dari kolom tanggal
                SEC_TO_TIME(SUM(TIME_TO_SEC(hadir))) AS total_hadir,
                SEC_TO_TIME(SUM(TIME_TO_SEC(sakit))) AS total_sakit,
                SEC_TO_TIME(SUM(TIME_TO_SEC(izin))) AS total_izin,
                SEC_TO_TIME(SUM(TIME_TO_SEC(alpha))) AS total_alpha
            FROM
                absen
            GROUP BY
                karyawan_id, bulan; -- Group berdasarkan karyawan dan bulan
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXIST view_rekap_absensi");
    }
}
