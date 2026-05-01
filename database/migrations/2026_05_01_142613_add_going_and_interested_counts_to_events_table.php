<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add two counter columns to the events table so we can track
     * how many visitors clicked "Going" or "Interested" for each event.
     *
     * We store the counts directly on the event row for simplicity.
     * Both start at zero and only go up (we never decrement).
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Number of visitors who clicked the "Going" button
            $table->unsignedInteger('going_count')->default(0)->after('is_published');

            // Number of visitors who clicked the "Interested" button
            $table->unsignedInteger('interested_count')->default(0)->after('going_count');
        });
    }

    /**
     * Remove the counter columns if we ever need to roll back this migration.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['going_count', 'interested_count']);
        });
    }
};
