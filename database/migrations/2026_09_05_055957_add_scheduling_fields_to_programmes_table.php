<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Recurring weekly schedule for group/corporate programmes — used by
     * AvailabilityService to block the coach's calendar on matching weekdays
     * within the programme's date range. Null = doesn't affect availability.
     */
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->unsignedTinyInteger('session_weekday')->nullable()->after('duration_label');
            $table->time('session_start_time')->nullable()->after('session_weekday');
            $table->unsignedInteger('session_duration_minutes')->nullable()->after('session_start_time');
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn(['session_weekday', 'session_start_time', 'session_duration_minutes']);
        });
    }
};
