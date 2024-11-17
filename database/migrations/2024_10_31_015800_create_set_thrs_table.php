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
        Schema::create('set_thrs', function (Blueprint $table) {
            $table->id('id_set_thr');
            $table->foreignId('posisi_id')->nullable()->constrained('posisis', 'id_posisi')->cascadeOnDelete();
            $table->decimal('besaran_thr', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_thrs');
    }
};
