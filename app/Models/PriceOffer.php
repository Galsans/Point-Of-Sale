<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class PriceOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'badge',
        'package_price',
        'original_price',
        'is_active',
        'sort_order',
        'category',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'package_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active'     => 'boolean',
        'sort_order'    => 'integer',
        'available_from' => 'datetime:H:i',
        'available_until' => 'datetime:H:i',
    ];

    // =====================
    // RELATIONSHIPS
    // =====================

    public function items()
    {
        return $this->hasMany(PriceOfferItem::class)->orderBy('sort_order');
    }

    public function itemsUnordered()
    {
        return $this->hasMany(PriceOfferItem::class);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? Storage::disk('public')->url($this->image)
            : null;
    }

    public function getSavingAmountAttribute(): float
    {
        return max(0, (float) $this->original_price - (float) $this->package_price);
    }

    public function getSavingPercentAttribute(): float
    {
        if ((float) $this->original_price <= 0) {
            return 0;
        }
        return round(($this->saving_amount / (float) $this->original_price) * 100, 1);
    }

    public function getFormattedPackagePriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->package_price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->original_price, 0, ',', '.');
    }

    public function getIsAvailableNowAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if (!$this->available_from || !$this->available_until) {
            return true;
        }

        $now = now()->format('H:i:s');
        return $now >= $this->available_from && $now <= $this->available_until;
    }


    // =====================
    // SCOPES
    // =====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailableNow($query)
    {
        $now = now()->format('H:i:s');
        return $query->where(function ($q) use ($now) {
            // Tampilkan jika tidak ada batasan waktu
            $q->whereNull('available_from')
                ->orWhere(function ($q2) use ($now) {
                    $q2->where('available_from', '<=', $now)
                        ->where('available_until', '>=', $now);
                });
        });
    }

    // =====================
    // HELPERS
    // =====================

    /**
     * Hitung ulang original_price dari total harga satuan item-item di dalamnya.
     * Dipanggil setiap kali item paket ditambah/diubah/dihapus.
     */
    public function recalculateOriginalPrice(): void
    {
        $original = $this->itemsUnordered()
            ->selectRaw('SUM(item_price * quantity) as total')
            ->value('total') ?? 0;

        $this->update(['original_price' => $original]);
    }

    /**
     * Berapa rupiah yang dihemat customer jika beli paket ini
     */
    public function getSavingsAttribute(): float
    {
        return max(0, $this->original_price - $this->package_price);
    }

    /**
     * Persentase hemat
     */
    public function getSavingsPercentAttribute(): float
    {
        if ($this->original_price <= 0) return 0;
        return round(($this->savings / $this->original_price) * 100, 1);
    }

    /**
     * Apakah paket tersedia saat ini (aktif + dalam jam operasional)?
     */
    public function isAvailableNow(): bool
    {
        if (! $this->is_active) return false;
        if (! $this->available_from) return true;

        $now  = now()->format('H:i:s');
        $from = $this->available_from->format('H:i:s');
        $until = $this->available_until->format('H:i:s');

        return $now >= $from && $now <= $until;
    }
}
