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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreignId('menu_id')->nullable()->references('id')->on('menus')->nullOnDelete();
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 10, 2);

            // TAMBAHAN
            $table->foreignId('price_offer_id')->nullable()->references('id')->on('price_offers')->nullOnDelete();
            // $table->foreignId('price_offer_id')
            //     ->nullable()
            //     ->constrained('price_offers')
            //     ->nullOnDelete();
            // ->comment('Jika item ini bagian dari paket bundling');

            // Snapshot nama item untuk keperluan struk/display
            $table->string('item_name')->nullable();
            // ->comment('Snapshot nama: nama paket atau nama menu');

            // Tipe baris: 'menu' = item satuan, 'package' = paket bundling
            $table->enum('item_type', ['menu', 'package'])->default('menu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
