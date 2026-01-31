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
    ];

    // protected $casts = [
    //     'price' => 'decimal:2',
    //     'subtotal' => 'decimal:2',
    //     'qty' => 'integer',
    // ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
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
}
