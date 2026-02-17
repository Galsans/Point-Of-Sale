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

                        {{-- QRIS PAYMENT SECTION --}}
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

                        {{-- ═══════════════════════════════════════════════════════════════ --}}
                        {{-- UPLOAD BUKTI PEMBAYARAN                                         --}}
                        {{-- ═══════════════════════════════════════════════════════════════ --}}
                        <div class="mt-4 text-start">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="mdi mdi-upload"></i>
                                        {{ $order->buktiPembayaran ? 'Status Pembayaran' : 'Upload Bukti Pembayaran' }}
                                    </h5>
                                </div>
                                <div class="card-body">

                                    {{-- ✅ SUCCESS STATE — selalu ada di DOM, awalnya disembunyikan --}}
                                    <div id="uploadSuccess" class="alert alert-success mb-0 d-none">
                                        <i class="mdi mdi-check-circle"></i>
                                        <strong>Bukti pembayaran berhasil dikirim!</strong>
                                        <p class="mb-0 mt-1 small">
                                            Pesanan Anda sedang diproses oleh kasir. Silakan tunggu konfirmasi selanjutnya.
                                        </p>
                                    </div>

                                    @if (is_null($order->buktiPembayaran))
                                        {{-- ✅ Bungkus semua konten form dalam satu div agar mudah disembunyikan --}}
                                        <div id="uploadFormWrap">
                                            <p class="text-muted mb-3">
                                                Setelah melakukan pembayaran, upload bukti transfer/screenshot pembayaran
                                                Anda di sini.
                                            </p>

                                            <form id="uploadBuktiForm"
                                                action="{{ route('order.upload-bukti', $orderCode) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                {{-- Drop Zone --}}
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

                                                <div class="mb-3">
                                                    <label for="catatanPembayaran" class="form-label text-muted small">
                                                        <i class="mdi mdi-note-text"></i> Catatan (opsional)
                                                    </label>
                                                    <textarea id="catatanPembayaran" name="notePembayaran" rows="2" class="form-control"
                                                        placeholder="Contoh: Sudah transfer via BCA jam 13.00 atas nama Fulan"></textarea>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" id="btnUpload" class="btn btn-primary"
                                                        disabled>
                                                        <i class="mdi mdi-send"></i> Kirim Bukti Pembayaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        {{-- Jika sudah pernah upload (page refresh), langsung tampilkan success --}}
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
                        {{-- ═══════════════════════════════════════════════════════════════ --}}

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
                                <span>Service Fee:</span>
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
        // ─── QRIS Download ────────────────────────────────────────────────────────
        function downloadQRCode() {
            const qrisImage = document.getElementById('qrisImage');
            const link = document.createElement('a');
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

        // window.addEventListener('load', function() {
        //     setTimeout(downloadQRCode, 1000);
        // });

        document.getElementById('downloadQris').addEventListener('click', downloadQRCode);

        // ─── Copy Amount ──────────────────────────────────────────────────────────
        document.getElementById('copyAmount').addEventListener('click', function() {
            const totalAmount = document.getElementById('totalAmountRaw').textContent;
            const button = this;
            const originalHTML = button.innerHTML;
            navigator.clipboard.writeText(totalAmount).then(function() {
                button.innerHTML = '<i class="mdi mdi-check"></i> Copied!';
                button.classList.remove('btn-light');
                button.classList.add('btn-success');
                setTimeout(function() {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-light');
                }, 2000);
            }).catch(function() {
                alert('Gagal copy nominal. Silakan coba lagi.');
            });
        });

        // ─── Upload Bukti Pembayaran ──────────────────────────────────────────────
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
        const MAX_SIZE = 5 * 1024 * 1024; // 5 MB

        function formatBytes(bytes) {
            return bytes < 1024 * 1024 ?
                (bytes / 1024).toFixed(1) + ' KB' :
                (bytes / (1024 * 1024)).toFixed(2) + ' MB';
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

            const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!allowed.includes(file.type)) {
                showError('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                return;
            }
            if (file.size > MAX_SIZE) {
                showError('Ukuran file terlalu besar. Maksimal 5 MB.');
                return;
            }

            previewName.textContent = file.name;
            previewSize.textContent = formatBytes(file.size);

            if (file.type === 'application/pdf') {
                // Show a generic PDF icon for PDF files
                previewImg.src = 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg';
                previewImg.alt = 'PDF File';
            } else {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            placeholder.classList.add('d-none');
            previewWrap.classList.remove('d-none');
            btnUpload.disabled = false;
        }

        // Click to browse
        buktiFile.addEventListener('change', function() {
            if (this.files && this.files[0]) handleFile(this.files[0]);
        });

        // Drag-and-drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = '#e8f0fe';
        });
        dropZone.addEventListener('dragleave', function() {
            this.style.background = '';
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '';
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                buktiFile.files = dt.files;
                handleFile(file);
            }
        });

        // ─── AJAX Form Submit ─────────────────────────────────────────────────────
        document.getElementById('uploadBuktiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            btnUpload.disabled = true;
            btnUpload.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...';

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ??
                            '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // ✅ Sembunyikan seluruh wrap form (termasuk teks di atasnya)
                        document.getElementById('uploadFormWrap').classList.add('d-none');

                        // ✅ Tampilkan success (sekarang selalu ada di DOM)
                        document.getElementById('uploadSuccess').classList.remove('d-none');
                    } else {
                        showError(data.message ?? 'Gagal mengirim bukti. Silakan coba lagi.');
                        btnUpload.disabled = false;
                        btnUpload.innerHTML = '<i class="mdi mdi-send"></i> Kirim Bukti Pembayaran';
                    }
                })
                .catch(() => {
                    showError('Terjadi kesalahan koneksi. Silakan coba lagi.');
                    btnUpload.disabled = false;
                    btnUpload.innerHTML = '<i class="mdi mdi-send"></i> Kirim Bukti Pembayaran';
                });
        });
    </script>
@endpush
