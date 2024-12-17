<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerHitungHadirAlphaUpdate extends Migration
{
    /**
     * Run the migrations.e
     */
    public function up(): void
    {
        DB::statement("
            CREATE TRIGGER hitung_hadir_alpha_update
            BEFORE UPDATE ON absen
            FOR EACH ROW
            BEGIN
                -- Variabel untuk menyimpan data posisi karyawan
                DECLARE batasJamMasuk TIME;
                DECLARE batasJamKeluar TIME;

                -- Variabel untuk perhitungan
                DECLARE jamMasuk TIME;
                DECLARE jamKeluar TIME;
                DECLARE jamKerjaPerHari INT;
                DECLARE durasiAlpha TIME DEFAULT '00:00:00';
                DECLARE durasiHadir TIME DEFAULT '00:00:00';

                -- Ambil data posisi karyawan melalui tabel posisi
                SELECT p.jam_masuk, p.jam_keluar, p.jam_kerja_per_hari
                INTO batasJamMasuk, batasJamKeluar, jamKerjaPerHari
                FROM posisis p
                JOIN karyawans k ON k.posisi_id = p.id_posisi
                WHERE k.id_karyawan = NEW.karyawan_id;

                -- Inisialisasi variabel
                SET jamMasuk = NEW.jam_masuk;
                SET jamKeluar = NEW.jam_keluar;

                -- Kondisi: Jika jam_keluar lebih dari batasJamKeluar
                IF jamKeluar > batasJamKeluar THEN
                    SET jamKeluar = batasJamKeluar;
                END IF;

                -- Kondisi: Jika jam_masuk kurang dari batasJamMasuk
                IF jamMasuk < batasJamMasuk THEN
                    SET jamMasuk = batasJamMasuk;
                END IF;

                -- 1. Periksa keterlambatan masuk
                IF jamMasuk > batasJamMasuk THEN
                    IF jamMasuk < ADDTIME(batasJamMasuk, '01:00:01') THEN
                        -- Terlambat kurang dari 1 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '01:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '01:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '02:00:01') THEN
                        -- Terlambat 1-2 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '02:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '02:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '03:00:01') THEN
                        -- Terlambat 2-3 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '03:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '03:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '04:00:01') THEN
                        -- Terlambat 3-4 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '04:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '04:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '05:00:01') THEN
                        -- Terlambat 4-5 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '05:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '05:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '06:00:01') THEN
                        -- Terlambat 5-6 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '06:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '06:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '07:00:01') THEN
                        -- Terlambat 6-7 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '07:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '07:00:00');
                    ELSEIF jamMasuk < ADDTIME(batasJamMasuk, '08:00:01') THEN
                        -- Terlambat 7-8 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '08:00:00');
                        SET jamMasuk = ADDTIME(batasJamMasuk, '08:00:00');

                    END IF;
                END IF;

                -- 2. Periksa bolos keluar sebelum batas waktu
                IF jamKeluar < batasJamKeluar THEN
                    IF jamKeluar >= ADDTIME(batasJamKeluar, '-01:00:00') THEN
                        -- Bolos kurang dari 1 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '01:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-01:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-02:00:00') THEN
                        -- Bolos 1-2 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '02:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-02:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-03:00:00') THEN
                        -- Bolos 2-3 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '03:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-03:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-04:00:00') THEN
                        -- Bolos 3-4 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '04:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-04:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-05:00:00') THEN
                        -- Bolos 4-5 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '05:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-05:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-06:00:00') THEN
                        -- Bolos 5-6 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '06:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-06:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-07:00:00') THEN
                        -- Bolos 6-7 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '07:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-07:00:00');
                    ELSEIF jamKeluar >= ADDTIME(batasJamKeluar, '-08:00:00') THEN
                        -- Bolos 7-8 jam
                        SET durasiAlpha = ADDTIME(durasiAlpha, '08:00:00');
                        SET jamKeluar = ADDTIME(batasJamKeluar, '-08:00:00');
                    END IF;
                END IF;

                -- 3. Hitung durasi hadir
                SET durasiHadir = TIMEDIFF(jamKeluar, jamMasuk);
                -- Kondisi: Jika durasi hadir lebih kecil dari 00:00:00, set ke 00:00:00
                IF TIME_TO_SEC(durasiHadir) < 0 THEN
                    SET durasiHadir = '00:00:00';
                END IF;

                -- 4. Batasi durasi hadir sesuai dengan jam kerja per hari
                IF TIME_TO_SEC(durasiHadir) > jamKerjaPerHari * 3600 THEN
                    SET durasiHadir = SEC_TO_TIME(jamKerjaPerHari * 3600);
                END IF;


                -- 5. Simpan hasil ke field NEW
                SET NEW.alpha = durasiAlpha; -- Alpha dalam format HH:MM:SS
                SET NEW.hadir = durasiHadir; -- Hadir dalam format HH:MM:SS
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
        DROP TRIGGER IF EXISTS hitung_hadir_alpha_update;
    ");
    }
};
