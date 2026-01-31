<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-light">
                <div>
                    <h5 class="modal-title mb-0">Detail Pesanan</h5>
                    <small class="text-muted" id="orderCode"></small>
                </div>
                <span id="orderStatusBadge" class="badge fs-6 px-3 py-2"></span>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- CUSTOMER & TOTAL -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted">Customer</small>
                            <h6 class="mb-0" id="customerName"></h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <small class="text-muted">Meja</small>
                            <h6 class="mb-0 fw-bold" id="tableCode"></h6>
                        </div>
                    </div>

                    <div class="col-md-4 text-end">
                        <div class="border rounded p-3 h-100 bg-light">
                            <small class="text-muted">Total Pembayaran</small>
                            <h3 class="fw-bold text-success mb-0">
                                Rp <span id="totalPrice"></span>
                            </h3>
                        </div>
                    </div>
                </div>


                <!-- ITEM LIST -->
                <h6 class="fw-bold mb-2">Daftar Menu</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="orderItems"></tbody>
                    </table>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>

                <button id="btnPay" class="btn btn-warning d-none">
                    <i class="mdi mdi-cash"></i> Tandai Dibayar
                </button>

                <button id="btnComplete" class="btn btn-success d-none">
                    <i class="mdi mdi-check"></i> Selesaikan Order
                </button>
            </div>

        </div>
    </div>
</div>

