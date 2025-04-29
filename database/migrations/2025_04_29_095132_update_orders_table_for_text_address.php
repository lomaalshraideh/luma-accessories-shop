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
            // Add a new address text column
            $table->text('address')->nullable()->after('user_id');

            // If you want to remove the old address_id column
            // $table->dropForeign(['address_id']); // Drop the foreign key first
            // $table->dropColumn('address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('address');

            // If you removed the old address_id column
            // $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();
        });
    }
};
