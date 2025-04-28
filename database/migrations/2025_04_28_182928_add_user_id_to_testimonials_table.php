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
        Schema::table('testimonials', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('testimonials', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            }

            // Check for other columns that might be missing
            if (!Schema::hasColumn('testimonials', 'name')) {
                $table->string('name')->nullable();
            }

            if (!Schema::hasColumn('testimonials', 'message')) {
                $table->text('message')->nullable();
            }

            if (!Schema::hasColumn('testimonials', 'image')) {
                $table->string('image')->nullable();
            }

            if (!Schema::hasColumn('testimonials', 'rating')) {
                $table->integer('rating')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Remove columns if needed
            if (Schema::hasColumn('testimonials', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            // Uncomment these if you want to remove other columns too
            // $table->dropColumn(['name', 'message', 'image', 'rating']);
        });
    }
};
