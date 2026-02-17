<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg overflow-hidden">

            {{-- ═══════════════════════════════════════ --}}
            {{-- HEADER                                  --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3"
                style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
                <div class="d-flex align-items-start gap-3 w-100">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="mdi mdi-receipt text-white opacity-75" style="font-size: 18px;"></i>
                            <small class="text-white opacity-75 fw-semibold text-uppercase tracking-wide"
                                style="letter-spacing: 1px; font-size: 11px;">Detail Pesanan</small>
                        </div>
                        <h5 class="modal-title text-white mb-0 fw-bold" id="orderCode"
                            style="font-size: 1.1rem; font-family: 'Courier New', monospace; letter-spacing: 1px;">
                            —
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span id="orderStatusBadge" class="badge px-3 py-2" style="font-size: 12px;"></span>
                        <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════ --}}
            {{-- BODY                                    --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="modal-body p-0" style="background: #f8fafc;">

                {{-- ─── INFO STRIP ─────────────────────────────────────────── --}}
                <div class="px-4 py-3 bg-white border-bottom">
                    <div class="row g-3">

                        {{-- Customer --}}
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:#dbeafe;">
                                    <i class="mdi mdi-account text-primary" style="font-size:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size:11px;">Customer</div>
                                    <div class="fw-semibold text-dark" id="customerName" style="font-size:13px;">—</div>
                                </div>
                            </div>
                        </div>

                        {{-- Meja --}}
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:#fef3c7;">
                                    <i class="mdi mdi-table-chair text-warning" style="font-size:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size:11px;">Meja</div>
                                    <div class="fw-bold text-dark" id="tableCode"
                                        style="font-size:14px;line-height:1.2;">—</div>
                                    <div class="text-muted" id="tableFloor" style="font-size:11px;line-height:1.2;">—
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Waktu --}}
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:#f0fdf4;">
                                    <i class="mdi mdi-clock-outline text-success" style="font-size:18px;"></i>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size:11px;">Waktu Order</div>
                                    <div class="fw-semibold text-dark" id="orderTime" style="font-size:13px;">—</div>
                                </div>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:36px;height:36px;background:#ede9fe;">
                                    <i class="mdi mdi-cash-multiple" style="font-size:18px;color:#7c3aed;"></i>
                                </div>
                                <div>
                                    <div class="text-muted" style="font-size:11px;">Total Bayar</div>
                                    <div class="fw-bold" id="totalPrice"
                                        style="font-size:15px;color:#7c3aed;font-family:'Courier New',monospace;">—
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ─── NOTE PEMBAYARAN (jika ada) ─────────────────────────── --}}
                <div id="noteWrap" class="d-none px-4 pt-3">
                    <div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-0" style="font-size:13px;">
                        <i class="mdi mdi-note-text-outline mt-1"></i>
                        <div>
                            <strong>Catatan Pembayaran:</strong>
                            <span id="notePembayaran"></span>
                        </div>
                    </div>
                </div>

                {{-- ─── BUKTI PEMBAYARAN (jika ada) ─────────────────────────── --}}
                <div id="buktiWrap" class="d-none px-4 pt-3">
                    <div class="border rounded p-3 bg-white d-flex align-items-center gap-3">
                        <img id="buktiImg" src="#" alt="Bukti Pembayaran" class="rounded border"
                            style="height:70px; width:70px; object-fit:cover; cursor:pointer;"
                            onclick="window.open(this.src, '_blank')">
                        <div>
                            <div class="fw-semibold text-dark" style="font-size:13px;">Bukti Pembayaran</div>
                            <small class="text-muted">Klik gambar untuk memperbesar</small>
                        </div>
                    </div>
                </div>

                {{-- ─── DAFTAR ITEM ─────────────────────────────────────────── --}}
                <div class="px-4 pt-4 pb-2">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="mdi mdi-food text-primary"></i>
                        <h6 class="fw-bold mb-0 text-dark">Daftar Menu</h6>
                        <span id="itemCount" class="badge bg-primary rounded-pill ms-1"
                            style="font-size:11px;"></span>
                    </div>

                    {{-- Loading skeleton --}}
                    <div id="itemsLoading" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <small class="d-block text-muted mt-2">Memuat detail...</small>
                    </div>

                    {{-- Item cards container --}}
                    <div id="orderItemsContainer" class="d-none">
                        <div id="orderItems" class="d-flex flex-column gap-3"></div>
                    </div>
                </div>

                {{-- ─── RINGKASAN PEMBAYARAN ────────────────────────────────── --}}
                <div class="px-4 pb-4 pt-2">
                    <div class="rounded-3 border bg-white overflow-hidden" id="summaryWrap"
                        style="display:none!important;">
                        <div class="px-4 py-3 border-bottom" style="background:#f8fafc;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="mdi mdi-calculator text-muted"></i>
                                <span class="fw-semibold text-muted" style="font-size:13px;">Ringkasan
                                    Pembayaran</span>
                            </div>
                        </div>
                        <div class="px-4 py-3">
                            <div class="d-flex justify-content-between py-1" style="font-size:14px;">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold" id="summarySubtotal">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-1" style="font-size:14px;">
                                <span class="text-muted">Pajak (10%)</span>
                                <span class="fw-semibold" id="summaryTax">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-1" style="font-size:14px;">
                                <span class="text-muted">Service Fee</span>
                                <span class="fw-semibold" id="summaryService">—</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between py-1">
                                <span class="fw-bold text-dark" style="font-size:15px;">Total</span>
                                <span class="fw-bold" id="summaryTotal"
                                    style="font-size:16px;color:#7c3aed;font-family:'Courier New',monospace;">—</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════════════════════════════════════ --}}
            {{-- FOOTER                                  --}}
            {{-- ═══════════════════════════════════════ --}}
            <div class="modal-footer border-0 bg-white px-4 py-3">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Tutup
                </button>

                <button id="btnPay" class="btn btn-warning px-4 d-none"
                    style="background:linear-gradient(135deg,#f59e0b,#d97706);border:none;color:white;">
                    <i class="mdi mdi-cash me-1"></i> Tandai Dibayar
                </button>

                <button id="btnComplete" class="btn btn-success px-4 d-none"
                    style="background:linear-gradient(135deg,#10b981,#059669);border:none;">
                    <i class="mdi mdi-check-circle me-1"></i> Selesaikan Order
                </button>
            </div>

        </div>
    </div>
</div>
