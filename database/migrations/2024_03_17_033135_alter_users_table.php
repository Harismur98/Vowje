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
        Schema::table('users', function (Blueprint $table) {

            $table->string('phone_num')->nullable();
            $table->string('is_phone_num_verify')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_num');
            $table->dropColumn('is_phone_num_verify');
            $table->dropColumn('birthdate');
            $table->dropColumn('gender');
        });
    }
};
