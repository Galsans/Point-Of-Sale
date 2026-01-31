@extends('layouts.order')

@section('title', 'Konfirmasi Pesanan')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="mdi mdi-check-circle text-success" style="font-size: 80px;"></i>
                        </div>

                        <h2 class="mb-3">Pesanan Berhasil Dibuat!</h2>
                        <p class="text-muted mb-4">Terima kasih atas pesanan Anda</p>

                        <div class="alert alert-info">
                            <h5 class="mb-0">Kode Pesanan: <strong>{{ $order->order_code }}</strong></h5>
                        </div>

                        {{-- ORDER DETAILS --}}
                        <div class="text-start mt-4">
                            <h5 class="mb-3">Detail Pesanan</h5>

                            <div class="mb-3">
                                <strong>Nama:</strong> {{ $order->customer_name }}<br>
                                @if ($order->customer_email)
                                    <strong>Email:</strong> {{ $order->customer_email }}<br>
                                @endif
                                @if ($order->customer_phone)
                                    <strong>Telepon:</strong> {{ $order->customer_phone }}<br>
                                @endif
                                <strong>Table:</strong> {{ $order->table->kode_table }}
                            </div>

                            <hr>

                            {{-- ITEMS --}}
                            @foreach ($order->items as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $item->menu->name }}</strong>
                                        <small class="text-muted">(x{{ $item->qty }})</small>

                                        {{-- OPTIONS --}}
                                        @if ($item->options->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                @foreach ($item->options as $option)
                                                    • {{ $option->option_name ?? $option->custom_value }}
                                                    @if ($option->option_price > 0)
                                                        (+Rp {{ number_format($option->option_price, 0, ',', '.') }})
                                                    @endif
                                                    <br>
                                                @endforeach
                                            </small>
                                        @endif
                                    </div>
                                    <div>
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <hr>

                            {{-- SUMMARY --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak (10%):</span>
                                <strong>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Fee (5%):</span>
                                <strong>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5>Total:</h5>
                                <h5 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('order.menu') }}" class="btn btn-primary">
                                <i class="mdi mdi-home"></i> Kembali ke Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
