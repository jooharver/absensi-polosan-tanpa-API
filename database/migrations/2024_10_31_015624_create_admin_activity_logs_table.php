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
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('action')->nullable();
            $table->text('from')->nullable();
            $table->text('to')->nullable();
            $table->timestamp('action_time')->useCurrent()->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
