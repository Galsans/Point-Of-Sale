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
        Schema::create('price_offers', function (Blueprint $table) {
            $table->id();
            // ── Identitas Paket ──
            $table->string('name')->comment('Nama paket, contoh: Paket Hemat A');
            $table->text('description')->nullable();
            $table->string('image')->nullable()->comment('Foto paket');
            $table->string('badge')->nullable()->comment('Label: "Terlaris", "Baru", dll');

            // ── Harga ──
            $table->decimal('package_price', 12, 2)->comment('Harga jual paket (bundling)');
            $table->decimal('original_price', 12, 2)->default(0)->comment('Total harga satuan (auto-hitung)');
            // Selisih original_price - package_price = nilai hemat yang tampil ke customer

            // ── Pengaturan Tampilan di QR Menu ──
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('category')->nullable()->comment('Sarapan, Makan Siang, dll');

            // ── Ketersediaan Waktu (opsional) ──
            $table->time('available_from')->nullable();
            $table->time('available_until')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_offers');
    }
};
