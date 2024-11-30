<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosisisTable extends Migration
{
    public function up()
    {
        Schema::create('posisis', function (Blueprint $table) {
            $table->id('id_posisi', 10);
            $table->string('posisi', 50);
            $table->integer('jam_kerja_per_hari');
            $table->integer('hari_kerja_per_minggu');
            $table->time('jam_masuk')->default(null);
            $table->time('jam_keluar')->default(null);
            $table->time('grace_period')->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posisis');
    }
}
