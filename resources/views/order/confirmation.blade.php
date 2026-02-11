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

                        {{-- QRIS PAYMENT SECTION - MOVED TO TOP --}}
                        <div class="mt-4 text-center">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="mb-3">
                                        <i class="mdi mdi-qrcode-scan"></i> Pembayaran via QRIS
                                    </h4>
                                    <p class="text-muted mb-3">Scan QR Code di bawah ini untuk melakukan pembayaran</p>

                                    {{-- Total Amount --}}
                                    <div class="alert alert-primary mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-start">
                                                <h5 class="mb-0">
                                                    <strong>Total Pembayaran: Rp <span
                                                            id="totalAmount">{{ number_format($order->total_price, 0, ',', '.') }}</span></strong>
                                                </h5>
                                                <small class="text-muted">Nominal: Rp <span
                                                        id="totalAmountRaw">{{ $order->total_price }}</span></small>
                                            </div>
                                            <button id="copyAmount" class="btn btn-light btn-sm"
                                                title="Copy nominal pembayaran">
                                                <i class="mdi mdi-content-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    {{-- QR Code Image --}}
                                    <div class="qris-container mb-3">
                                        <img id="qrisImage" src="{{ asset('qr/qris.jpg') }}" alt="QRIS Payment"
                                            class="img-fluid"
                                            style="max-width: 300px; border: 2px solid #ddd; border-radius: 10px; padding: 15px; background: white;">
                                    </div>

                                    {{-- Download Button --}}
                                    <div class="mb-3">
                                        <button id="downloadQris" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-download"></i> Download QR Code
                                        </button>
                                    </div>

                                    <div class="alert alert-warning mb-0">
                                        <small>
                                            <i class="mdi mdi-information"></i>
                                            QR Code akan otomatis terdownload. Silakan lakukan pembayaran sesuai dengan
                                            total di atas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ORDER DETAILS --}}
                        <div class="text-start mt-5">
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
                                <span>Service Fee (50%):</span>
                                <strong>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5>Total:</h5>
                                <h5 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-primary">
                                <i class="mdi mdi-home"></i> Kembali ke Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Function to download QR Code
        function downloadQRCode() {
            const qrisImage = document.getElementById('qrisImage');
            const link = document.createElement('a');

            // Convert image to blob and download
            fetch(qrisImage.src)
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = 'QRIS-Payment-{{ $order->order_code }}.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Error downloading QR Code:', error);
                    alert('Gagal mendownload QR Code. Silakan coba lagi.');
                });
        }

        // Auto-download when page loads
        window.addEventListener('load', function() {
            // Delay 1 second to ensure page is fully loaded
            setTimeout(function() {
                downloadQRCode();
            }, 1000);
        });

        // Manual download button
        document.getElementById('downloadQris').addEventListener('click', function() {
            downloadQRCode();
        });

        // Copy total amount to clipboard
        document.getElementById('copyAmount').addEventListener('click', function() {
            const totalAmount = document.getElementById('totalAmountRaw').textContent;
            const button = this;
            const originalHTML = button.innerHTML;

            // Copy to clipboard
            navigator.clipboard.writeText(totalAmount).then(function() {
                // Change button text to indicate success
                button.innerHTML = '<i class="mdi mdi-check"></i> Copied!';
                button.classList.remove('btn-light');
                button.classList.add('btn-success');

                // Reset button after 2 seconds
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-light');
                }, 2000);
            }).catch(function(error) {
                console.error('Error copying to clipboard:', error);
                alert('Gagal copy nominal. Silakan coba lagi.');
            });
        });
    </script>
@endpush
