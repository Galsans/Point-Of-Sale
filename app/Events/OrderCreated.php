<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;

        // ✅ LOG KETIKA EVENT DIBUAT
        Log::info('OrderCreated Event constructed', [
            'order_code' => $order->order_code
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('orders');
    }

    public function broadcastAs()
    {
        return 'order.created';
    }

    public function broadcastWith()
    {
        $data = [
            'order_code' => $this->order->order_code,
            'customer_name' => $this->order->customer_name,
            'table_name' => $this->order->table->kode_table ?? 'N/A',
            'total_price' => number_format($this->order->total_price, 0, ',', '.'),
        ];

        // ✅ LOG DATA YANG AKAN DI-BROADCAST
        Log::info('Broadcasting data:', $data);

        return $data;
    }
}
