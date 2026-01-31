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
        Schema::create('menu_option_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreignId('option_group_id')->references('id')->on('option_groups')->onDelete('cascade');
            $table->boolean('is_required');
            $table->unsignedSmallInteger('min_choice')->nullable();
            $table->unsignedSmallInteger('max_choice')->nullable();
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_option_groups');
    }
};
