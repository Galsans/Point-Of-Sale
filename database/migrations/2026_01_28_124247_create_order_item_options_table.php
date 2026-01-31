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
        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('option_group_id')->constrained()->cascadeOnDelete();
            $table->string('option_group_name'); // snapshot nama group (misal: "Pilih Bagian Ayam")
            $table->enum('option_group_type', ['single', 'multiple', 'text']);

            // Untuk pilihan single/multiple
            $table->foreignId('option_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('option_name')->nullable(); // snapshot nama option (misal: "Paha")
            $table->decimal('option_price', 12, 2)->default(0); // extra price

            // Untuk pilihan text (custom notes per option group)
            $table->text('custom_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_options');
    }
};
