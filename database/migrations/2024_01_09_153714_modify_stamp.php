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
        Schema::table('stamp', function (Blueprint $table) {

            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shop');
            $table->integer('total_stamp');
            $table->string('reward');
            $table->date('expired_date');
            $table->string('t&c', 200);
            $table->dropColumn('count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
