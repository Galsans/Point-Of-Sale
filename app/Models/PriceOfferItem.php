<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceOfferItem extends Model
{
    protected $fillable = [
        'price_offer_id',
        'menu_id',
        'item_name',
        'item_price',
        'category',
        'quantity',
        'sort_order',
    ];

    protected $casts = [
        'item_price' => 'decimal:2',
        'quantity'   => 'integer',
        'sort_order' => 'integer',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getSubtotalAttribute(): float
    {
        return (float) $this->item_price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedItemPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->item_price, 0, ',', '.');
    }


    // =====================
    // RELATIONSHIPS
    // =====================

    public function priceOffer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // =====================
    // AUTO SYNC SNAPSHOT + RECALCULATE
    // =====================

    protected static function booted(): void
    {
        // Saat item disimpan: snapshot nama & harga dari menu terkait
        static::saving(function (self $item) {
            if ($item->menu_id && $item->isDirty('menu_id')) {
                $menu = Menu::find($item->menu_id);
                if ($menu) {
                    $item->item_name  = $menu->name;
                    $item->item_price = $menu->price;
                }
            }
        });

        // Setelah item berubah: hitung ulang original_price di paket
        static::saved(function (self $item) {
            $item->priceOffer->recalculateOriginalPrice();
        });

        static::deleted(function (self $item) {
            $item->priceOffer->recalculateOriginalPrice();
        });
    }
}
