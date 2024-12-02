<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->default(now()->toDateString())
            ->nullable();
            $table->foreignId('karyawan_id');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->time('hadir')->nullable()->default('00:00:00');
            $table->time('sakit')->nullable()->default('00:00:00');
            $table->time('izin')->nullable()->default('00:00:00');
            $table->time('alpha')->nullable()->default('00:00:00');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};

