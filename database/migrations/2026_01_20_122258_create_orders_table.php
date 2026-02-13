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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->string('order_number')->unique();

            // $table->string('customer_name')->nullable();

            // $table->foreignId('user_id')
            //     ->constrained()
            //     ->cascadeOnDelete();

            // $table->decimal('total_price', 12, 2);

            // $table->decimal('paid_amount', 12, 2)->nullable();
            // $table->decimal('change_amount', 12, 2)->nullable();

            // $table->enum('payment_method', [
            //     'cash',
            //     'qris',
            //     'debit',
            //     'credit'
            // ])->nullable();


            // $table->foreignId('user_id')
            // ->nullable()
            // ->constrained()
            // ->nullOnDelete();
            // $table->timestamp('paid_at')->nullable();

            // ===============================
            // 🔢 ORDER / INVOICE INFO
            // ===============================
            $table->integer('order_year');
            $table->integer('order_number');
            $table->string('order_code')->unique();

            $table->unique(['order_year', 'order_number']);

            // ===============================
            // 👤 CUSTOMER & USER
            // ===============================

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            $table->enum('status', [
                'pending',
                'paid',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->foreignId('table_id')->references('id')->on('tables')->onDelete('cascade');
            // ===============================
            // 💰 PRICE CALCULATION
            // ===============================
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->string('buktiPembayaran')->nullable();
            $table->string('notePembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
