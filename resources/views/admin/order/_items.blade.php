@forelse ($data as $order)
    {{-- ✅ Tambah data-order-id di sini --}}
    <div class="col-md-4 col-sm-6 grid-margin stretch-card" data-order-id="{{ $order->id }}">
        <div
            class="card shadow-sm h-100 border-start
            {{ $order->status === 'pending' ? 'border-warning' : '' }}
            {{ $order->status === 'paid' ? 'border-info' : '' }}
            {{ $order->status === 'completed' ? 'border-success' : '' }}
            {{ $order->status === 'cancelled' ? 'border-danger' : '' }}">

            <div class="card-body text-center">

                <h5 class="fw-bold mb-1">{{ $order->order_code }}</h5>
                <small class="text-muted d-block mb-2">{{ $order->customer_name }}</small>

                {{-- ✅ Tambah class status-badge --}}
                <span
                    class="badge fs-6 px-3 py-2 status-badge
                    {{ $order->status === 'pending' ? 'bg-warning text-dark' : '' }}
                    {{ $order->status === 'paid' ? 'bg-info' : '' }}
                    {{ $order->status === 'completed' ? 'bg-success' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-danger' : '' }}">
                    {{ strtoupper($order->status) }}
                </span>

                <div class="my-3">
                    <h4 class="fw-bold text-success mb-0">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </h4>
                </div>

                {{-- ✅ Tambah class action-buttons di wrapper --}}
                <div class="d-grid gap-2">
                    <button class="btn btn-sm btn-primary btn-detail" data-id="{{ $order->id }}">
                        <i class="mdi mdi-eye"></i> Detail Order
                    </button>

                    <div class="action-buttons">
                        @if ($order->status === 'pending')
                            <button class="btn btn-warning w-100 btn-update-status" data-id="{{ $order->id }}"
                                data-status="paid" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="mdi mdi-cash"></i> Tandai Dibayar
                            </button>
                        @elseif ($order->status === 'paid')
                            <button class="btn btn-success w-100 btn-update-status" data-id="{{ $order->id }}"
                                data-status="completed" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="mdi mdi-check"></i> Selesaikan Order
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@empty
    {{-- EMPTY --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-receipt-text mdi-48px text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada order masuk</h5>
            </div>
        </div>
    </div>
@endforelse
