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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('total_voucher');
            $table->dropColumn('total_need_to_collect');
            $table->integer('min_spend');
            $table->integer('max_voucher_used');
            $table->integer('total_used');
            $table->date('expired_date');
            $table->string('t&c', 200);
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
