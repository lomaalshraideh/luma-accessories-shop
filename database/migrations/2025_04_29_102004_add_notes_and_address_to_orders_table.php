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
        Schema::table('orders', function (Blueprint $table) {
            // Add notes column if it doesn't exist
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }

            // Add order_number column if it doesn't exist
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable()->unique();
            }

            // Make sure address column exists (you already added this in a previous migration)
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['notes', 'order_number']);
            // Don't drop 'address' if it was added in a previous migration
        });
    }
};
