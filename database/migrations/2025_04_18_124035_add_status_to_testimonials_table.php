<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// You need a class declaration here
return new class extends Migration
{
    // Public functions should be inside the class
    public function up()
    {
        // First check if table exists
        if (!Schema::hasTable('testimonials')) {
            Schema::create('testimonials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        } else {
            // Just add the missing columns
            Schema::table('testimonials', function (Blueprint $table) {
                if (!Schema::hasColumn('testimonials', 'message')) {
                    $table->text('message');
                }
                if (!Schema::hasColumn('testimonials', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('testimonials')) {
            if (Schema::hasColumn('testimonials', 'status')) {
                Schema::table('testimonials', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }
            // Uncomment if you want to be able to roll back the message column too
            // if (Schema::hasColumn('testimonials', 'message')) {
            //     Schema::table('testimonials', function (Blueprint $table) {
            //         $table->dropColumn('message');
            //     });
            // }
        }
    }
};
