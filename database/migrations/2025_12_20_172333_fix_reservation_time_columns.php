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
        Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['start_time', 'end_time']);
        $table->time('reserved_from');
        $table->time('reserved_to');
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
