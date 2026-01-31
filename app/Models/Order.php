<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // protected $guarded = [];
    protected $fillable = [
        'order_year',
        'order_number',
        'order_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'table_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'service_fee',
        'total_price',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get all of the orderItem for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    /**
     * Get all of the menu for the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the table that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($order) {
    //         $year = now()->year;

    //         $lastNumber = Order::where('order_year', $year)
    //             ->max('order_number');

    //         $order->order_year   = $year;
    //         $order->order_number = ($lastNumber ?? 0) + 1;

    //         // PINDAHKAN KE SINI - Generate order_code SEBELUM insert
    //         $order->order_code = sprintf(
    //             'ORD-%d-%06d',
    //             $order->order_year,
    //             $order->order_number
    //         );
    //     });
    // }
    // ✅ GENERATE ORDER CODE OTOMATIS
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $year = now()->year;

            // Get last order number untuk tahun ini
            $lastOrder = static::where('order_year', $year)
                ->orderBy('order_number', 'desc')
                ->first();

            $orderNumber = $lastOrder ? $lastOrder->order_number + 1 : 1;

            $order->order_year = $year;
            $order->order_number = $orderNumber;
            $order->order_code = 'ORD-' . $year . '-' . str_pad($orderNumber, 8, '0', STR_PAD_LEFT);
        });
    }

    // ✅ SCOPE
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // app/Models/Order.php
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($order) {
    //         // Validasi tambahan: pastikan total_price tidak negatif
    //         if ($order->total_price < 0) {
    //             throw new \Exception('Invalid total price');
    //         }

    //         // Validasi: subtotal + tax harus = total
    //         $expectedTotal = $order->subtotal + $order->tax_amount;
    //         if (abs($expectedTotal - $order->total_price) > 0.01) { // toleransi pembulatan
    //             throw new \Exception('Price calculation mismatch');
    //         }
    //     });
    // }
}
