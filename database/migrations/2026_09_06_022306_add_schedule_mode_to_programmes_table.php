<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->string('schedule_mode')->default('none')->after('max_participants');
        });

        // Programmes already carrying a weekly session keep behaving as before.
        DB::table('programmes')->whereNotNull('session_weekday')->update(['schedule_mode' => 'weekly']);
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('schedule_mode');
        });
    }
};
