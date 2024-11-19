<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans', 'id_karyawan')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('absen_masuk')->nullable();
            $table->time('absen_keluar')->nullable();
            $table->time('hadir')->nullable()->default('00:00:00');
            $table->time('sakit')->nullable()->default('00:00:00');
            $table->time('izin')->nullable()->default('00:00:00');
            $table->time('alpha')->nullable()->default('00:00:00');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
