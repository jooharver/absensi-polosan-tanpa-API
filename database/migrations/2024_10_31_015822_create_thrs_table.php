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
        Schema::create('thrs', function (Blueprint $table) {
            $table->id('id_thr');
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawans', 'id_karyawan')->cascadeOnDelete();
            $table->decimal('thr', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thrs');
    }
};
