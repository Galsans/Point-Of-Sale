<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image',
        'is_available',
        'description'
    ];

    /**
     * Get the category that owns the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function menuOptionGroups()
    {
        return $this->hasMany(MenuOptionGroup::class);
    }

    public function optionGroups()
    {
        return $this->belongsToMany(
            OptionGroup::class,
            'menu_option_groups'
        )->withPivot('is_required', 'min_choice', 'max_choice', 'sort_order')
            ->orderBy('menu_option_groups.sort_order');
    }

    /**
     * Order items yang berasal dari menu ini.
     * menu_id bisa NULL di order_items jika item berasal dari price_offer,
     * sehingga relasi ini secara natural hanya mengembalikan baris dengan menu_id = id menu ini.
     */
    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'menu_id');
    }
}
