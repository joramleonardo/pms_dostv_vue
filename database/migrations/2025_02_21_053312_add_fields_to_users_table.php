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
            $table->date('start_date')->nullable()->after('is_active');
            $table->date('end_date')->nullable()->after('start_date');
            $table->unsignedBigInteger('created_by')->nullable()->after('end_date');

            // Adding a foreign key constraint
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['start_date', 'end_date', 'created_by']);
        });
    }
};
