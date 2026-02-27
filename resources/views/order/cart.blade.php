@extends('layouts.order')

@section('title', 'Keranjang - ' . $table->kode_table)
@section('table-info', $table->kode_table)

@section('content')
    <div class="container pb-5">

        {{-- HEADER --}}
        <div class="cart-page-header d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('order.menu', ['table' => request('table')]) }}" class="btn btn-icon-back">
                <i class="mdi mdi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-bold">Keranjang Pesanan</h5>
                <small class="text-muted" id="cartHeaderSubtitle">Memuat...</small>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- EMPTY STATE --}}
        <div id="emptyCartState" class="text-center py-5 d-none">
            <div class="empty-cart-illustration mb-4">
                <i class="mdi mdi-cart-off" style="font-size: 80px; color: #dee2e6;"></i>
            </div>
            <h5 class="text-muted">Keranjang Kosong</h5>
            <p class="text-muted small">Belum ada menu yang dipilih</p>
            <a href="{{ route('order.menu', ['table' => request('table')]) }}" class="btn btn-primary mt-2">
                <i class="mdi mdi-arrow-left me-1"></i> Pilih Menu
            </a>
        </div>

        {{-- CART ITEMS --}}
        <div id="cartItemsContainer"></div>

        {{-- ORDER SUMMARY --}}
        <div id="orderSummaryCard" class="card border-0 shadow-sm mt-4 d-none">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span id="summarySubtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Pajak (10%)</span>
                    <span id="summaryTax">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Service Fee (5%)</span>
                    <span id="summaryServiceFee">Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong class="text-primary fs-5" id="summaryTotal">Rp 0</strong>
                </div>
            </div>
        </div>

    </div>

    {{-- FIXED CHECKOUT BUTTON --}}
    <div class="cart-fixed-bottom" id="checkoutBar" style="display:none;">
        <button type="button" class="cart-btn" id="checkoutBtn">
            <span>
                <i class="mdi mdi-check-circle"></i>
                <span id="checkoutItemLabel">0 Item</span>
            </span>
            <span class="cart-total" id="checkoutTotal">Rp 0</span>
        </button>
    </div>

    {{-- MODAL CUSTOMER INFO --}}
    <div class="modal fade" id="customerInfoModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-account-edit"></i> Informasi Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="customerInfoForm">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> Mohon isi data Anda untuk melanjutkan pesanan
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerName" placeholder="Masukkan nama Anda"
                                required minlength="3" maxlength="100" autocomplete="off">
                            <div class="invalid-feedback">Nama minimal 3 karakter</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-muted">(Opsional)</span></label>
                            <input type="email" class="form-control" id="customerEmail" placeholder="contoh@email.com"
                                maxlength="100" autocomplete="off">
                            <div class="invalid-feedback">Format email tidak valid</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Telepon <span class="text-muted">(Opsional)</span></label>
                            <input type="tel" class="form-control" id="customerPhone" placeholder="08123456789"
                                pattern="[0-9]{10,15}" maxlength="15" autocomplete="off">
                            <div class="invalid-feedback">Nomor telepon 10-15 digit</div>
                            <small class="text-muted">Format: 08xxxxxxxxxx (10-15 digit)</small>
                        </div>
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="mb-3">Ringkasan Pesanan</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah Item:</span><strong id="modalSummaryItemCount">0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span><strong id="modalSummarySubtotal">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pajak (10%):</span><strong id="modalSummaryTax">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Fee (5%):</span><strong id="modalSummaryServiceFee">Rp 0</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong class="text-primary fs-5" id="modalSummaryTotal">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary" id="proceedToOrderBtn">
                            <i class="mdi mdi-check-circle"></i> Lanjutkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT OPTIONS --}}
    <div class="modal fade" id="editOptionsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalMenuName">Edit Opsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editMenuId">
                    <input type="hidden" id="editItemIndex">
                    <input type="hidden" id="editMenuPrice">
                    <div id="editOptionGroupsContainer"></div>
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between">
                            <strong>Harga:</strong>
                            <strong class="text-primary" id="editModalPrice">Rp 0</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="saveEditBtn">
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- HIDDEN FORM — dikirim ke server saat checkout --}}
    {{-- Form ini menangani SEMUA item: menu biasa + paket, sekaligus --}}
    <form action="{{ route('order.store') }}" method="POST" id="orderForm" style="display:none;">
        @csrf
        <input type="hidden" name="table_id" value="{{ $table->id }}">
        <input type="hidden" name="customer_name" id="hiddenCustomerName">
        <input type="hidden" name="customer_email" id="hiddenCustomerEmail">
        <input type="hidden" name="customer_phone" id="hiddenCustomerPhone">
        {{-- items[] dan packages[] diisi JS sebelum submit --}}
        <div id="orderItems"></div>
    </form>

    <style>
        .btn-icon-back {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #333;
            flex-shrink: 0;
        }

        .btn-icon-back:hover {
            background: #e9ecef;
            color: #000;
        }

        .cart-page-header {
            padding-top: 1rem;
        }

        .cart-item-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            padding: 1rem;
            margin-bottom: .75rem;
            border: 1px solid #f0f0f0;
            transition: box-shadow .2s;
        }

        .cart-item-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .1);
        }

        .cart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .cart-item-name {
            font-weight: 600;
            font-size: .95rem;
            color: #1a1a1a;
            flex: 1;
            margin-right: .5rem;
        }

        .cart-item-price {
            font-weight: 700;
            color: var(--bs-primary, #0d6efd);
            white-space: nowrap;
        }

        .cart-item-options {
            margin-top: .5rem;
            padding: .5rem .75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #dee2e6;
        }

        .cart-item-option-tag {
            display: inline-block;
            background: #e9ecef;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: .78rem;
            color: #555;
            margin: 2px 2px 2px 0;
        }

        .cart-item-option-tag.is-extra {
            background: #d1e7dd;
            color: #0f5132;
        }

        .cart-item-note {
            font-size: .8rem;
            color: #666;
            font-style: italic;
        }

        .cart-item-actions {
            display: flex;
            gap: .5rem;
            margin-top: .75rem;
            padding-top: .75rem;
            border-top: 1px solid #f0f0f0;
            align-items: center;
        }

        .btn-cart-action {
            flex: 1;
            font-size: .82rem;
            padding: .35rem .5rem;
            border-radius: 8px;
            border: 1px solid;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            transition: all .15s;
        }

        .btn-cart-edit {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-cart-edit:hover {
            background: #0d6efd;
            color: #fff;
        }

        .btn-cart-delete {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-cart-delete:hover {
            background: #dc3545;
            color: #fff;
        }

        .item-index-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: var(--bs-primary, #0d6efd);
            color: #fff;
            border-radius: 50%;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-right: 6px;
        }

        .menu-group-label {
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #999;
            padding: .75rem 0 .25rem;
        }

        #orderSummaryCard {
            border-radius: 16px !important;
        }

        .pkg-qty-btn-sm {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid #e0e0e0;
            background: #fff;
            color: #555;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: all .15s;
        }

        .pkg-qty-btn-sm:hover {
            background: #ff6b35;
            border-color: #ff6b35;
            color: #fff;
        }

        .pkg-qty-btn-sm:active {
            transform: scale(.9);
        }
    </style>
@endsection

@push('scripts')
    <script>
        // ============================================================
        // CONFIG — harus konsisten dengan _script.blade.php
        // ============================================================
        const CART_KEY = 'restaurant_cart';
        const MENU_KEY = 'restaurant_menu_data';
        const TAX_RATE = 0.10;
        const SERVICE_FEE_RATE = 0.05; // 5% dari subtotal
        const tableParam = '{{ request()->query('table') }}';

        // ============================================================
        // STATE
        // ============================================================
        let cart = {};
        let menuNameMap = {};

        function loadCart() {
            try {
                cart = JSON.parse(localStorage.getItem(CART_KEY) || '{}');
                menuNameMap = JSON.parse(localStorage.getItem(MENU_KEY) || '{}');
            } catch (e) {
                cart = {};
                menuNameMap = {};
            }
        }

        function saveCart() {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function escapeHtml(text) {
            return String(text).replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [m]));
        }

        function isValidEmail(e) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);
        }

        function isValidPhone(p) {
            return /^[0-9]{10,15}$/.test(p);
        }

        function calcTotals() {
            let totalItems = 0,
                subtotal = 0;
            Object.values(cart).forEach(d => {
                (d.items || []).forEach(i => {
                    totalItems += i.qty || 1;
                    subtotal += (i.price || 0) * (i.qty || 1);
                });
            });
            const tax = subtotal * TAX_RATE;
            const serviceFee = subtotal * SERVICE_FEE_RATE; // 5% dari subtotal
            const grandTotal = subtotal + tax + serviceFee;
            return {
                totalItems,
                subtotal,
                tax,
                serviceFee,
                grandTotal
            };
        }

        // ============================================================
        // RENDER CART
        // ============================================================
        function renderCart() {
            const container = document.getElementById('cartItemsContainer');
            const emptyState = document.getElementById('emptyCartState');
            const summaryCard = document.getElementById('orderSummaryCard');
            const checkoutBar = document.getElementById('checkoutBar');
            const subtitle = document.getElementById('cartHeaderSubtitle');

            container.innerHTML = '';

            const allKeys = Object.keys(cart);
            const pkgKeys = allKeys.filter(k => k.startsWith('pkg_'));
            const menuKeys = allKeys.filter(k => !k.startsWith('pkg_'));

            if (allKeys.length === 0) {
                emptyState.classList.remove('d-none');
                summaryCard.classList.add('d-none');
                checkoutBar.style.display = 'none';
                subtitle.textContent = 'Keranjang kosong';
                return;
            }

            emptyState.classList.add('d-none');
            summaryCard.classList.remove('d-none');
            checkoutBar.style.display = '';

            // ── PAKET ──
            if (pkgKeys.length > 0) {
                const lbl = document.createElement('div');
                lbl.className = 'menu-group-label';
                lbl.innerHTML = '<i class="mdi mdi-gift-outline me-1"></i>Paket Bundling';
                container.appendChild(lbl);

                pkgKeys.forEach(key => {
                    const data = cart[key];
                    if (!data.items || !data.items.length) return;
                    const entry = data.items.find(i => i.is_package);
                    if (!entry) return;

                    const pkgId = parseInt(key.replace('pkg_', ''));
                    const pkgMeta = (window.__pkgData || {})[pkgId];
                    // Fallback ke menuNameMap jika window.__pkgData tidak tersedia (halaman cart)
                    const pkgName = pkgMeta ?
                        pkgMeta.name :
                        (menuNameMap[key]?.name || ('Paket #' + pkgId));
                    const qty = entry.qty || 1;
                    const price = entry.price || 0;
                    const total = price * qty;

                    let itemsHtml = '';
                    if (pkgMeta && pkgMeta.items && pkgMeta.items.length) {
                        itemsHtml = '<div class="cart-item-options mt-2">' +
                            pkgMeta.items.map(i =>
                                '<span class="cart-item-option-tag">' +
                                (i.quantity > 1 ? '<strong>' + i.quantity + 'x</strong> ' : '') +
                                escapeHtml(i.name) + '</span>'
                            ).join('') + '</div>';
                    }

                    const card = document.createElement('div');
                    card.className = 'cart-item-card';
                    card.innerHTML = `
                        <div class="cart-item-header">
                            <div class="d-flex align-items-center flex-1">
                                <span class="item-index-badge" style="background:#ff6b35;font-size:14px;">🎁</span>
                                <span class="cart-item-name">${escapeHtml(pkgName)}</span>
                            </div>
                            <span class="cart-item-price">Rp ${Math.round(price).toLocaleString('id-ID')}/pkt</span>
                        </div>
                        ${itemsHtml}
                        <div class="cart-item-actions">
                            <button class="pkg-qty-btn-sm" data-action="pkg-minus" data-pkg-key="${key}">
                                <i class="mdi mdi-minus"></i>
                            </button>
                            <span class="fw-bold px-2">${qty}x</span>
                            <button class="pkg-qty-btn-sm" data-action="pkg-plus" data-pkg-key="${key}">
                                <i class="mdi mdi-plus"></i>
                            </button>
                            <span class="ms-2 text-primary fw-bold">= Rp ${Math.round(total).toLocaleString('id-ID')}</span>
                            <button class="btn-cart-action btn-cart-delete ms-auto" style="flex:0;padding:.35rem .75rem;"
                                data-action="pkg-delete" data-pkg-key="${key}">
                                <i class="mdi mdi-delete-outline"></i>
                            </button>
                        </div>`;
                    container.appendChild(card);
                });
            }

            // ── MENU BIASA ──
            menuKeys.forEach(menuId => {
                const menuData = cart[menuId];
                if (!menuData.items || !menuData.items.length) return;

                const menuInfo = menuNameMap[menuId] || {};
                const menuName = menuInfo.name || ('Menu #' + menuId);

                const lbl = document.createElement('div');
                lbl.className = 'menu-group-label';
                lbl.innerHTML = '<i class="mdi mdi-food-outline me-1"></i>' + escapeHtml(menuName);
                container.appendChild(lbl);

                menuData.items.forEach((item, itemIdx) => {
                    const optionsHtml = renderOptionsDisplay(item.options, menuId);
                    const card = document.createElement('div');
                    card.className = 'cart-item-card';
                    card.innerHTML = `
                        <div class="cart-item-header">
                            <div class="d-flex align-items-center flex-1">
                                <span class="item-index-badge">${itemIdx + 1}</span>
                                <span class="cart-item-name">${escapeHtml(menuName)}</span>
                            </div>
                            <span class="cart-item-price">Rp ${Math.round(item.price || 0).toLocaleString('id-ID')}</span>
                        </div>
                        ${optionsHtml ? '<div class="cart-item-options mt-2">' + optionsHtml + '</div>' : ''}
                        <div class="cart-item-actions">
                            <button class="btn-cart-action btn-cart-edit"
                                data-action="edit" data-menu-id="${menuId}" data-item-idx="${itemIdx}">
                                <i class="mdi mdi-pencil"></i> Ubah Opsi
                            </button>
                            <button class="btn-cart-action btn-cart-delete"
                                data-action="delete" data-menu-id="${menuId}" data-item-idx="${itemIdx}">
                                <i class="mdi mdi-delete-outline"></i> Hapus
                            </button>
                        </div>`;
                    container.appendChild(card);
                });
            });

            // ── SUMMARY ──
            const {
                totalItems,
                subtotal,
                tax,
                serviceFee,
                grandTotal
            } = calcTotals();
            document.getElementById('summarySubtotal').textContent = 'Rp ' + Math.round(subtotal).toLocaleString('id-ID');
            document.getElementById('summaryTax').textContent = 'Rp ' + Math.round(tax).toLocaleString('id-ID');
            document.getElementById('summaryServiceFee').textContent = 'Rp ' + Math.round(serviceFee).toLocaleString(
                'id-ID');
            document.getElementById('summaryTotal').textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
            document.getElementById('checkoutItemLabel').textContent = totalItems + ' Item';
            document.getElementById('checkoutTotal').textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
            subtitle.textContent = totalItems + ' item dipilih';
        }

        function renderOptionsDisplay(options, menuId) {
            if (!options || !Object.keys(options).length) return '';
            const optionMeta = (menuNameMap[menuId] || {}).option_meta || {};
            let html = '';
            for (const [groupId, optArr] of Object.entries(options)) {
                const groupMeta = optionMeta[groupId] || {};
                (optArr || []).forEach(opt => {
                    if (opt.custom_value) {
                        html += '<div class="cart-item-note"><i class="mdi mdi-note-text-outline me-1"></i>' +
                            escapeHtml(opt.custom_value) + '</div>';
                    } else if (opt.option_id) {
                        const optMeta = (groupMeta.options || {})[opt.option_id] || {};
                        const name = optMeta.name || ('Opsi #' + opt.option_id);
                        const extra = optMeta.extra_price || 0;
                        html += '<span class="cart-item-option-tag ' + (extra > 0 ? 'is-extra' : '') + '">' +
                            escapeHtml(name) + (extra > 0 ? ' +Rp' + extra.toLocaleString('id-ID') : '') +
                            '</span>';
                    }
                });
            }
            return html;
        }

        // ============================================================
        // CART ITEM ACTIONS — event delegation
        // ============================================================
        document.getElementById('cartItemsContainer').addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const action = btn.dataset.action;

            // Paket
            if (action === 'pkg-delete') {
                if (confirm('Hapus paket ini dari keranjang?')) {
                    delete cart[btn.dataset.pkgKey];
                    saveCart();
                    renderCart();
                }
                return;
            }
            if (action === 'pkg-minus') {
                const entry = cart[btn.dataset.pkgKey]?.items?.find(i => i.is_package);
                if (entry) {
                    entry.qty--;
                    if (entry.qty <= 0) delete cart[btn.dataset.pkgKey];
                    saveCart();
                    renderCart();
                }
                return;
            }
            if (action === 'pkg-plus') {
                const entry = cart[btn.dataset.pkgKey]?.items?.find(i => i.is_package);
                if (entry && entry.qty < 10) {
                    entry.qty++;
                    saveCart();
                    renderCart();
                }
                return;
            }

            // Menu biasa
            const menuId = btn.dataset.menuId;
            const itemIdx = parseInt(btn.dataset.itemIdx);
            if (action === 'delete') {
                if (confirm('Hapus item ini dari keranjang?')) {
                    cart[menuId].items.splice(itemIdx, 1);
                    if (!cart[menuId].items.length) delete cart[menuId];
                    saveCart();
                    renderCart();
                }
            } else if (action === 'edit') {
                openEditModal(menuId, itemIdx);
            }
        });

        // ============================================================
        // CHECKOUT BUTTON → buka modal customer info
        // ============================================================
        const customerModal = new bootstrap.Modal(document.getElementById('customerInfoModal'));

        document.getElementById('checkoutBtn').addEventListener('click', function() {
            // Reset form supaya tidak ada nilai sisa dari sesi sebelumnya
            document.getElementById('customerName').value = '';
            document.getElementById('customerEmail').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerName').classList.remove('is-invalid');
            document.getElementById('customerEmail').classList.remove('is-invalid');
            document.getElementById('customerPhone').classList.remove('is-invalid');

            const {
                totalItems,
                subtotal,
                tax,
                serviceFee,
                grandTotal
            } = calcTotals();
            document.getElementById('modalSummaryItemCount').textContent = totalItems;
            document.getElementById('modalSummarySubtotal').textContent = 'Rp ' + Math.round(subtotal)
                .toLocaleString('id-ID');
            document.getElementById('modalSummaryTax').textContent = 'Rp ' + Math.round(tax).toLocaleString(
                'id-ID');
            document.getElementById('modalSummaryServiceFee').textContent = 'Rp ' + Math.round(serviceFee)
                .toLocaleString('id-ID');
            document.getElementById('modalSummaryTotal').textContent = 'Rp ' + Math.round(grandTotal)
                .toLocaleString('id-ID');
            customerModal.show();
        });

        // ============================================================
        // CUSTOMER INFO FORM SUBMIT
        // ============================================================
        document.getElementById('customerInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const nameEl = document.getElementById('customerName');
            const emailEl = document.getElementById('customerEmail');
            const phoneEl = document.getElementById('customerPhone');

            const name = nameEl.value.trim();
            const email = emailEl.value.trim();
            const phone = phoneEl.value.trim();

            // Validasi
            let valid = true;
            nameEl.classList.remove('is-invalid');
            emailEl.classList.remove('is-invalid');
            phoneEl.classList.remove('is-invalid');

            if (name.length < 3) {
                nameEl.classList.add('is-invalid');
                valid = false;
            }
            if (email && !isValidEmail(email)) {
                emailEl.classList.add('is-invalid');
                valid = false;
            }
            if (phone && !isValidPhone(phone)) {
                phoneEl.classList.add('is-invalid');
                valid = false;
            }
            if (!valid) return;

            // ── Isi hidden fields customer ──
            // PENTING: isi field ini SEBELUM build items, agar tidak tertimpa
            document.getElementById('hiddenCustomerName').value = name;
            document.getElementById('hiddenCustomerEmail').value = email;
            document.getElementById('hiddenCustomerPhone').value = phone;

            // ── Build items & packages ke dalam #orderItems ──
            const orderItemsDiv = document.getElementById('orderItems');
            orderItemsDiv.innerHTML = ''; // bersihkan dulu

            let menuIdx = 0;
            let pkgIdx = 0;

            Object.entries(cart).forEach(function([key, data]) {
                if (key.startsWith('pkg_')) {
                    // ── PAKET ──
                    const pkgId = parseInt(key.replace('pkg_', ''));
                    const entry = (data.items || []).find(function(i) {
                        return i.is_package;
                    });
                    if (!entry || entry.qty < 1) return;

                    appendHidden(orderItemsDiv, 'packages[' + pkgIdx + '][price_offer_id]', pkgId);
                    appendHidden(orderItemsDiv, 'packages[' + pkgIdx + '][qty]', entry.qty);
                    pkgIdx++;

                } else {
                    // ── MENU BIASA ──
                    (data.items || []).forEach(function(item) {
                        const sanitized = sanitizeOptions(item.options || {});
                        appendHidden(orderItemsDiv, 'items[' + menuIdx + '][menu_id]', key);
                        appendHidden(orderItemsDiv, 'items[' + menuIdx + '][qty]', Math.max(1,
                            parseInt(item.qty || 1)));
                        appendHidden(orderItemsDiv, 'items[' + menuIdx + '][options]', JSON
                            .stringify(sanitized));
                        menuIdx++;
                    });
                }
            });

            // Pastikan ada item
            if (menuIdx === 0 && pkgIdx === 0) {
                alert('Keranjang kosong!');
                return;
            }

            // Disable tombol agar tidak double submit
            const btn = document.getElementById('proceedToOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            // Clear localStorage
            localStorage.removeItem(CART_KEY);
            localStorage.removeItem(MENU_KEY);

            // Submit form ke server
            document.getElementById('orderForm').submit();
        });

        // Helper: buat input hidden dan append ke parent
        function appendHidden(parent, name, value) {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = name;
            inp.value = value;
            parent.appendChild(inp);
        }

        ['customerName', 'customerEmail', 'customerPhone'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // ============================================================
        // EDIT MODAL (menu biasa)
        // ============================================================
        const editModal = new bootstrap.Modal(document.getElementById('editOptionsModal'));
        let editCurrentMenuData = null;

        async function openEditModal(menuId, itemIdx) {
            const item = cart[menuId]?.items[itemIdx];
            const menuInfo = menuNameMap[menuId] || {};
            if (!item) return;

            document.getElementById('editMenuId').value = menuId;
            document.getElementById('editItemIndex').value = itemIdx;
            document.getElementById('editMenuPrice').value = item.base_price || item.price;
            document.getElementById('editModalMenuName').textContent = menuInfo.name || 'Edit Opsi';

            editCurrentMenuData = null;

            try {
                const res = await fetch('/api/menus/' + menuId + '/options', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                editCurrentMenuData = data;
                renderEditOptionGroups(data.option_groups || [], JSON.parse(JSON.stringify(item.options || {})));
                updateEditModalPrice();
                editModal.show();
            } catch (err) {
                alert('Gagal memuat opsi menu. Coba lagi.');
            }
        }

        function renderEditOptionGroups(groups, prefilled) {
            const container = document.getElementById('editOptionGroupsContainer');
            container.innerHTML = '';
            groups.forEach(function(group) {
                let optionsHtml = '';
                if (group.type === 'text') {
                    const val = prefilled[group.id]?.[0]?.custom_value || '';
                    optionsHtml = '<textarea class="form-control edit-option-text" data-group-id="' + group.id +
                        '" placeholder="Masukkan catatan..." rows="2">' + escapeHtml(val) + '</textarea>';
                } else {
                    const inputType = group.type === 'single' ? 'radio' : 'checkbox';
                    const preIds = (prefilled[group.id] || []).map(function(o) {
                        return String(o.option_id);
                    });
                    optionsHtml = group.options.map(function(option) {
                        const checked = preIds.includes(String(option.id)) ? 'checked' : '';
                        const extraLabel = option.extra_price > 0 ?
                            ' <span class="text-success">+Rp ' + option.extra_price.toLocaleString(
                                'id-ID') + '</span>' :
                            '';
                        return '<div class="form-check"><input class="form-check-input edit-option-choice" type="' +
                            inputType + '" name="edit_group_' + group.id + '" data-group-id="' + group.id +
                            '" data-option-id="' + option.id + '" data-extra-price="' + option.extra_price +
                            '" id="edit_opt_' + option.id + '" ' + checked + '>' +
                            '<label class="form-check-label" for="edit_opt_' + option.id + '">' +
                            option.name + extraLabel + '</label></div>';
                    }).join('');
                }
                const req = group.pivot.is_required ? '<span class="text-danger">*</span>' : '';
                container.insertAdjacentHTML('beforeend',
                    '<div class="option-group mb-4"><label class="form-label fw-bold">' +
                    group.name + req + '</label><small class="text-muted d-block mb-2">' +
                    (group.description || '') + '</small>' + optionsHtml + '</div>');
            });
        }

        function updateEditModalPrice() {
            const base = parseFloat(document.getElementById('editMenuPrice').value) || 0;
            let extra = 0;
            document.querySelectorAll('.edit-option-choice:checked').forEach(function(inp) {
                extra += parseFloat(inp.dataset.extraPrice) || 0;
            });
            document.getElementById('editModalPrice').textContent = 'Rp ' + (base + extra).toLocaleString('id-ID');
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('edit-option-choice')) updateEditModalPrice();
        });
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('edit-option-text')) updateEditModalPrice();
        });

        document.getElementById('saveEditBtn').addEventListener('click', function() {
            const menuId = document.getElementById('editMenuId').value;
            const itemIdx = parseInt(document.getElementById('editItemIndex').value);
            const base = parseFloat(document.getElementById('editMenuPrice').value) || 0;

            if (editCurrentMenuData) {
                const required = (editCurrentMenuData.option_groups || []).filter(function(g) {
                    return g.pivot.is_required;
                });
                const newOpts = collectEditOptions();
                const missing = required.filter(function(g) {
                    return !newOpts[g.id] || !newOpts[g.id].length;
                });
                if (missing.length) {
                    alert('Harap pilih: ' + missing.map(function(g) {
                        return g.name;
                    }).join(', '));
                    return;
                }
            }

            const newOpts = collectEditOptions();
            let extra = 0;
            document.querySelectorAll('.edit-option-choice:checked').forEach(function(inp) {
                extra += parseFloat(inp.dataset.extraPrice) || 0;
            });

            cart[menuId].items[itemIdx].options = newOpts;
            cart[menuId].items[itemIdx].price = base + extra;
            if (editCurrentMenuData) syncMenuMeta(menuId, editCurrentMenuData);

            saveCart();
            editModal.hide();
            renderCart();
        });

        function collectEditOptions() {
            const opts = {};
            document.querySelectorAll('.edit-option-choice:checked').forEach(function(inp) {
                const g = inp.dataset.groupId;
                if (!opts[g]) opts[g] = [];
                opts[g].push({
                    option_id: inp.dataset.optionId
                });
            });
            document.querySelectorAll('.edit-option-text').forEach(function(ta) {
                const v = ta.value.trim();
                if (v) opts[ta.dataset.groupId] = [{
                    custom_value: v
                }];
            });
            return opts;
        }

        function syncMenuMeta(menuId, apiData) {
            if (!menuNameMap[menuId]) menuNameMap[menuId] = {};
            menuNameMap[menuId].option_meta = {};
            (apiData.option_groups || []).forEach(function(group) {
                menuNameMap[menuId].option_meta[group.id] = {
                    groupName: group.name,
                    options: {}
                };
                (group.options || []).forEach(function(opt) {
                    menuNameMap[menuId].option_meta[group.id].options[opt.id] = {
                        name: opt.name,
                        extra_price: opt.extra_price
                    };
                });
            });
            localStorage.setItem(MENU_KEY, JSON.stringify(menuNameMap));
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function sanitizeOptions(options) {
            const sanitized = {};
            for (const [groupId, arr] of Object.entries(options)) {
                sanitized[groupId] = (arr || []).map(function(opt) {
                    if (opt.option_id) return {
                        option_id: opt.option_id
                    };
                    if (opt.custom_value) return {
                        custom_value: opt.custom_value
                    };
                    return null;
                }).filter(Boolean);
            }
            return sanitized;
        }

        // ============================================================
        // INIT
        // ============================================================
        loadCart();
        renderCart();
    </script>
@endpush
