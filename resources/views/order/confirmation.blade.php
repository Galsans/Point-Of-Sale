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

                        {{-- QRIS PAYMENT SECTION --}}
                        <div class="mt-4 text-center">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="mb-3">
                                        <i class="mdi mdi-qrcode-scan"></i> Pembayaran via QRIS
                                    </h4>
                                    <p class="text-muted mb-3">Scan QR Code di bawah ini untuk melakukan pembayaran</p>

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

                                    <div class="qris-container mb-3">
                                        <img id="qrisImage" src="{{ asset('image/qris.jpg') }}" alt="QRIS Payment"
                                            class="img-fluid"
                                            style="border: 2px solid #ddd; border-radius: 10px; padding: 15px; background: white;">
                                    </div>

                                    <div class="mb-3">
                                        <button id="downloadQris" class="btn btn-success btn-sm">
                                            <i class="mdi mdi-download"></i> Download QR Code
                                        </button>
                                    </div>

                                    <div class="alert alert-warning mb-0">
                                        <small>
                                            <i class="mdi mdi-information"></i>
                                            Silakan lakukan pembayaran sesuai dengan total di atas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- UPLOAD BUKTI PEMBAYARAN --}}
                        <div class="mt-4 text-start">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="mdi mdi-upload"></i>
                                        {{ $order->buktiPembayaran ? 'Status Pembayaran' : 'Upload Bukti Pembayaran' }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="uploadSuccess" class="alert alert-success mb-0 d-none">
                                        <i class="mdi mdi-check-circle"></i>
                                        <strong>Bukti pembayaran berhasil dikirim!</strong>
                                        <p class="mb-0 mt-1 small">
                                            Pesanan Anda sedang diproses oleh kasir. Silakan tunggu konfirmasi selanjutnya.
                                        </p>
                                    </div>

                                    @if (is_null($order->buktiPembayaran))
                                        <div id="uploadFormWrap">
                                            <p class="text-muted mb-3">
                                                Setelah melakukan pembayaran, upload bukti transfer/screenshot pembayaran
                                                Anda di sini.
                                            </p>

                                            <form id="uploadBuktiForm"
                                                action="{{ route('order.upload-bukti', $orderCode) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div id="dropZone" class="border border-2 rounded p-4 text-center mb-3"
                                                    style="border-color: #0d6efd; border-style: dashed !important; cursor: pointer; transition: background 0.2s;"
                                                    onclick="document.getElementById('buktiFile').click()">

                                                    <div id="dropZonePlaceholder">
                                                        <i class="mdi mdi-cloud-upload text-primary"
                                                            style="font-size: 48px;"></i>
                                                        <p class="mb-1 text-muted mt-2">Klik atau seret file ke sini</p>
                                                        <small class="text-muted">Format: JPG, PNG &mdash; Maks. 5
                                                            MB</small>
                                                    </div>

                                                    <div id="dropZonePreview" class="d-none">
                                                        <img id="previewImage" src="#" alt="Preview"
                                                            class="img-fluid rounded mb-2"
                                                            style="max-height: 200px; object-fit: contain;">
                                                        <p id="previewFilename" class="mb-0 text-success fw-semibold small">
                                                        </p>
                                                        <small id="previewFilesize" class="text-muted"></small>
                                                        <br>
                                                        <button type="button" id="btnChangeFile"
                                                            class="btn btn-outline-secondary btn-sm mt-2"
                                                            onclick="event.stopPropagation(); document.getElementById('buktiFile').click();">
                                                            <i class="mdi mdi-pencil"></i> Ganti File
                                                        </button>
                                                    </div>
                                                </div>

                                                <input type="file" id="buktiFile" name="buktiPembayaran"
                                                    accept="image/jpeg,image/png" class="d-none">

                                                <div id="fileError" class="alert alert-danger d-none py-2 mb-3">
                                                    <i class="mdi mdi-alert-circle"></i>
                                                    <span id="fileErrorMsg"></span>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" id="btnUpload" class="btn btn-primary" disabled>
                                                        <i class="mdi mdi-send"></i> Kirim Bukti Pembayaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                document.getElementById('uploadSuccess').classList.remove('d-none');
                                            });
                                        </script>
                                        <div class="mt-3 text-center">
                                            <p class="small text-muted mb-1">Bukti yang Anda kirim:</p>
                                            <img src="{{ asset('storage/' . $order->buktiPembayaran) }}"
                                                class="img-thumbnail" style="max-height: 150px">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ══════════════════════════════════════════ --}}
                        {{-- DETAIL PESANAN                            --}}
                        {{-- ══════════════════════════════════════════ --}}
                        <div class="text-start mt-5">
                            <h5 class="fw-bold mb-3">Detail Pesanan</h5>

                            {{-- Info Pelanggan --}}
                            <div class="mb-3">
                                <strong>Nama:</strong> {{ $order->customer_name }}<br>
                                @if ($order->customer_email)
                                    <strong>Email:</strong> {{ $order->customer_email }}<br>
                                @endif
                                @if ($order->customer_phone)
                                    <strong>Telepon:</strong> {{ $order->customer_phone }}<br>
                                @endif
                                <strong>Meja:</strong> {{ $order->table->kode_table }} <br>
                                <strong>Status Pembayaran:</strong>
                                @php
                                    $badges = [
                                        'pending' => 'warning',
                                        'paid' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                    $color = $badges[$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($order->status) }}</span>
                            </div>

                            <hr>

                            {{-- ── Pisahkan items berdasarkan item_type ── --}}
                            @php
                                $menuItems = $order->items->where('item_type', 'menu');
                                $packageItems = $order->items->where('item_type', 'package');
                            @endphp

                            {{-- ══ SECTION: MENU BIASA ══ --}}
                            @if ($menuItems->isNotEmpty())
                                <div class="mb-3">
                                    {{-- Label section --}}
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span style="font-size:1rem;">🍽️</span>
                                        <span class="fw-semibold text-uppercase"
                                            style="font-size:.72rem;letter-spacing:.06em;color:#aaa;">
                                            Menu
                                        </span>
                                    </div>

                                    @foreach ($menuItems as $item)
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-1 me-3">
                                                {{-- Nama menu
                                                     Gunakan nullable operator (??) dengan fallback ke item_name
                                                     agar tidak error jika relasi menu null/terhapus --}}
                                                <div class="fw-semibold">
                                                    {{ $item->menu?->name ?? $item->item_name }}
                                                    <span class="text-muted fw-normal small">×{{ $item->qty }}</span>
                                                </div>

                                                {{-- Harga satuan --}}
                                                <div class="small text-muted">
                                                    @ Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </div>

                                                {{-- Opsi pilihan (radio/checkbox/text) --}}
                                                @if ($item->options->isNotEmpty())
                                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                                        @foreach ($item->options as $option)
                                                            @if ($option->custom_value)
                                                                {{-- Catatan bebas (tipe text) --}}
                                                                <div class="w-100 small text-muted fst-italic">
                                                                    <i
                                                                        class="mdi mdi-note-outline me-1"></i>{{ $option->custom_value }}
                                                                </div>
                                                            @else
                                                                {{-- Pilihan terstruktur --}}
                                                                <span class="badge rounded-pill"
                                                                    style="background:#f0f0f0;color:#444;font-weight:500;">
                                                                    {{ $option->option_name }}
                                                                    @if ($option->option_price > 0)
                                                                        <span class="text-success ms-1">
                                                                            +Rp
                                                                            {{ number_format($option->option_price, 0, ',', '.') }}
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Subtotal baris --}}
                                            <div class="text-end fw-semibold text-nowrap">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Divider hanya muncul jika kedua tipe ada --}}
                            @if ($menuItems->isNotEmpty() && $packageItems->isNotEmpty())
                                <hr class="my-2">
                            @endif

                            {{-- ══ SECTION: PAKET BUNDLING ══ --}}
                            @if ($packageItems->isNotEmpty())
                                <div class="mb-3">
                                    {{-- Label section --}}
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span style="font-size:1rem;">🎁</span>
                                        <span class="fw-semibold text-uppercase"
                                            style="font-size:.72rem;letter-spacing:.06em;color:#aaa;">
                                            Paket Bundling
                                        </span>
                                    </div>

                                    @foreach ($packageItems as $item)
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-1 me-3">
                                                {{-- Nama paket
                                                     Paket tidak memiliki relasi menu (menu_id = null),
                                                     gunakan item_name yang sudah disimpan saat order --}}
                                                <div class="fw-semibold">
                                                    {{ $item->item_name }}
                                                    <span class="text-muted fw-normal small">×{{ $item->qty }}</span>
                                                </div>

                                                {{-- Harga satuan --}}
                                                <div class="small text-muted">
                                                    @ Rp {{ number_format($item->price, 0, ',', '.') }} / paket
                                                </div>

                                                {{-- Isi paket — muat via eager load 'items' pada priceOffer --}}
                                                {{-- Di controller, pastikan eager load:                       --}}
                                                {{-- Order::with(['items.menu', 'items.options',               --}}
                                                {{--              'items.priceOffer.items', 'table'])           --}}
                                                @if ($item->priceOffer && $item->priceOffer->items->isNotEmpty())
                                                    <div class="mt-1 d-flex flex-wrap gap-1">
                                                        @foreach ($item->priceOffer->items as $pkgContent)
                                                            <span class="badge rounded-pill"
                                                                style="background:#fff3e0;color:#bf360c;font-weight:500;">
                                                                @if ($pkgContent->quantity > 1)
                                                                    {{ $pkgContent->quantity }}×
                                                                @endif
                                                                {{ $pkgContent->item_name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Subtotal baris --}}
                                            <div class="text-end fw-semibold text-nowrap">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <hr>

                            {{-- RINGKASAN BIAYA --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Pajak (10%)</span>
                                <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Service Fee</span>
                                <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Diskon</span>
                                    <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Total</h5>
                                <h5 class="mb-0 text-primary">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </h5>
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
        // ─── QRIS Download ───────────────────────────────────────────
        function downloadQRCode() {
            const qrisImage = document.getElementById('qrisImage');
            fetch(qrisImage.src)
                .then(r => r.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'QRIS-Payment-{{ $order->order_code }}.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                })
                .catch(() => alert('Gagal mendownload QR Code. Silakan coba lagi.'));
        }
        document.getElementById('downloadQris').addEventListener('click', downloadQRCode);

        // ─── Copy Amount ─────────────────────────────────────────────
        document.getElementById('copyAmount').addEventListener('click', function() {
            const amount = document.getElementById('totalAmountRaw').textContent;
            const btn = this;
            const orig = btn.innerHTML;
            navigator.clipboard.writeText(amount).then(function() {
                btn.innerHTML = '<i class="mdi mdi-check"></i> Copied!';
                btn.classList.replace('btn-light', 'btn-success');
                setTimeout(function() {
                    btn.innerHTML = orig;
                    btn.classList.replace('btn-success', 'btn-light');
                }, 2000);
            }).catch(() => alert('Gagal copy nominal.'));
        });

        // ─── Upload Bukti — hanya inisialisasi jika form tersedia ───
        @if (is_null($order->buktiPembayaran))
            (function() {
                const buktiFile = document.getElementById('buktiFile');
                const dropZone = document.getElementById('dropZone');
                const placeholder = document.getElementById('dropZonePlaceholder');
                const previewWrap = document.getElementById('dropZonePreview');
                const previewImg = document.getElementById('previewImage');
                const previewName = document.getElementById('previewFilename');
                const previewSize = document.getElementById('previewFilesize');
                const fileError = document.getElementById('fileError');
                const fileErrorMsg = document.getElementById('fileErrorMsg');
                const btnUpload = document.getElementById('btnUpload');
                const MAX_SIZE = 5 * 1024 * 1024;

                function formatBytes(b) {
                    return b < 1048576 ? (b / 1024).toFixed(1) + ' KB' : (b / 1048576).toFixed(2) + ' MB';
                }

                function showError(msg) {
                    fileErrorMsg.textContent = msg;
                    fileError.classList.remove('d-none');
                    btnUpload.disabled = true;
                    placeholder.classList.remove('d-none');
                    previewWrap.classList.add('d-none');
                }

                function clearError() {
                    fileError.classList.add('d-none');
                }

                function handleFile(file) {
                    clearError();
                    if (!['image/jpeg', 'image/png'].includes(file.type)) {
                        showError('Format tidak didukung. Gunakan JPG atau PNG.');
                        return;
                    }
                    if (file.size > MAX_SIZE) {
                        showError('Ukuran melebihi 5 MB.');
                        return;
                    }

                    previewName.textContent = file.name;
                    previewSize.textContent = formatBytes(file.size);
                    const reader = new FileReader();
                    reader.onload = e => {
                        previewImg.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    placeholder.classList.add('d-none');
                    previewWrap.classList.remove('d-none');
                    btnUpload.disabled = false;
                }

                buktiFile.addEventListener('change', function() {
                    if (this.files[0]) handleFile(this.files[0]);
                });
                dropZone.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropZone.style.background = '#e8f0fe';
                });
                dropZone.addEventListener('dragleave', () => {
                    dropZone.style.background = '';
                });
                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.style.background = '';
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        buktiFile.files = dt.files;
                        handleFile(file);
                    }
                });

                document.getElementById('uploadBuktiForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    btnUpload.disabled = true;
                    btnUpload.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content ?? '{{ csrf_token() }}'
                            },
                            body: new FormData(this)
                        })
                        .then(r => {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        })
                        .then(data => {
                            if (data.success) {
                                document.getElementById('uploadFormWrap').classList.add('d-none');
                                document.getElementById('uploadSuccess').classList.remove('d-none');
                            } else {
                                showError(data.message ?? 'Gagal mengirim bukti.');
                                btnUpload.disabled = false;
                                btnUpload.innerHTML = '<i class="mdi mdi-send"></i> Kirim Bukti Pembayaran';
                            }
                        })
                        .catch(() => {
                            showError('Kesalahan koneksi. Silakan coba lagi.');
                            btnUpload.disabled = false;
                            btnUpload.innerHTML = '<i class="mdi mdi-send"></i> Kirim Bukti Pembayaran';
                        });
                });
            })();
        @endif
    </script>
@endpush
