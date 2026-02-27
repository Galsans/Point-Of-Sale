<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriceOffer;

class OrderItemService
{
    /**
     * Tambahkan PAKET BUNDLING ke order.
     * Menghasilkan 1 baris di order_items dengan item_type = 'package'.
     */
    public function addPackage(Order $order, PriceOffer $package, int $qty = 1): OrderItem
    {
        if (! $package->isAvailableNow()) {
            throw new \Exception("Paket \"{$package->name}\" tidak tersedia saat ini.");
        }

        $item = OrderItem::create([
            'order_id'       => $order->id,
            'price_offer_id' => $package->id,
            'menu_id'        => null,
            'item_name'      => $package->name,
            'item_type'      => 'package',
            'qty'            => $qty,
            'price'          => $package->package_price,
            'subtotal'       => $package->package_price * $qty,
        ]);

        $this->recalculateOrder($order);

        return $item;
    }

    /**
     * Tambahkan MENU SATUAN ke order.
     * Jika menu sudah ada di order, qty-nya ditambah (merge).
     */
    public function addMenu(Order $order, Menu $menu, int $qty = 1): OrderItem
    {
        $existing = OrderItem::where('order_id', $order->id)
            ->where('menu_id', $menu->id)
            ->where('item_type', 'menu')
            ->first();

        if ($existing) {
            $newQty = $existing->qty + $qty;
            $existing->update([
                'qty'      => $newQty,
                'subtotal' => $newQty * $existing->price,
            ]);
            $this->recalculateOrder($order);
            return $existing->fresh();
        }

        $item = OrderItem::create([
            'order_id'       => $order->id,
            'price_offer_id' => null,
            'menu_id'        => $menu->id,
            'item_name'      => $menu->name,
            'item_type'      => 'menu',
            'qty'            => $qty,
            'price'          => $menu->price,
            'subtotal'       => $menu->price * $qty,
        ]);

        $this->recalculateOrder($order);

        return $item;
    }

    /**
     * Hitung ulang total order dari semua item yang ada.
     */
    public function recalculateOrder(Order $order): void
    {
        $subtotal       = OrderItem::where('order_id', $order->id)->sum('subtotal');
        $discountAmount = $order->discount_amount ?? 0;
        $taxAmount      = ($subtotal - $discountAmount) * (config('app.tax_percent', 0) / 100);
        $totalPrice     = $subtotal - $discountAmount + $taxAmount + ($order->service_fee ?? 0);

        $order->update([
            'subtotal'    => $subtotal,
            'tax_amount'  => $taxAmount,
            'total_price' => $totalPrice,
        ]);
    }
}
