<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izin', function (Blueprint $table) {
            $table->id('id_izin');
            $table->foreignId('karyawan_id')
                ->nullable()
                ->constrained('karyawans', 'id_karyawan')
                ->onDelete('set null'); // pastikan id_karyawan benar di tabel karyawans

            $table->string('jenis_izin')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
