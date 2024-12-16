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
        Schema::create('izins', function (Blueprint $table) {
            $table->id('id_izin');
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans', 'id_karyawan')->onDelete('set null');
            $table->enum('jenis', ['sakit', 'izin']);
            $table->date('start');
            $table->date('end');
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->string('keterangan')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izins');
    }
};
