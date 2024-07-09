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
        Schema::create('2in1stamps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stamp_id');
            $table->foreign('stamp_id')->references('id')->on('stamps');
            $table->unsignedBigInteger('second_stamp_id');
            $table->foreign('second_stamp_id')->references('id')->on('stamps');
            $table->integer('is_2in1stamp')->default(0)->comment('0 - No, 1 - Yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('2in1stamp');
    }
};
