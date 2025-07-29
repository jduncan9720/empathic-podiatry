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
        Schema::table('patients', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['facility_id']);
            
            // Add the new foreign key constraint that doesn't cascade on delete
            // This allows soft deletes to work properly
            $table->foreign('facility_id')
                ->references('id')
                ->on('facilities')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['facility_id']);
            
            // Restore the original cascade foreign key constraint
            $table->foreign('facility_id')
                ->references('id')
                ->on('facilities')
                ->onDelete('cascade');
        });
    }
};
