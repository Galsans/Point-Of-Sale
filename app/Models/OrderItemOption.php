<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $fillable = [
        'order_item_id',
        'option_group_id',
        'option_group_name',
        'option_group_type',
        'option_id',
        'option_name',
        'option_price',
        'custom_value',
    ];

    protected $casts = [
        'option_price' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }

    //     public function option()
    // {
    //     return $this->belongsTo(MenuOption::class, 'option_id');
    // }
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
