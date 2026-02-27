<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'qty',
        'price',
        'subtotal',
        'price_offer_id',
        'item_name',
        'item_type',
    ];

    // protected $casts = [
    //     'price' => 'decimal:2',
    //     'subtotal' => 'decimal:2',
    //     'qty' => 'integer',
    // ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'qty'      => 'integer',
    ];


    /**
     * Get the menu that owns the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function options()
    {
        return $this->hasMany(OrderItemOption::class);
    }

    /**
     * Jika item_type = 'package', ini adalah paket yang dipilih
     */
    public function priceOffer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    // =====================
    // HELPERS
    // =====================

    public function isPackage(): bool
    {
        return $this->item_type === 'package';
    }

    public function isMenu(): bool
    {
        return $this->item_type === 'menu';
    }
}
