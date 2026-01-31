@extends('layouts.order')

@section('title', 'Pilih Menu - ' . $table->kode_table)
@section('table-info', $table->kode_table)

@section('content')
    <div class="container">
        {{-- SEARCH BAR --}}
        <div class="search-bar">
            <i class="mdi mdi-magnify search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari menu..."
                value="{{ request('search') }}">
        </div>

        {{-- CATEGORY FILTER --}}
        <div class="category-filter">
            <button class="category-tab category-btn active" data-category="">
                <i class="mdi mdi-menu"></i> Semua
            </button>
            @foreach ($categories as $category)
                <button class="category-tab category-btn" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle-outline me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MENU GRID --}}
        <div class="row g-3" id="table-wrapper">
            @include('order._items', ['data' => $data])
        </div>

        {{-- LOADING SPINNER --}}
        <div id="loading" class="text-center py-4 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        {{-- TRIGGER INFINITE SCROLL --}}
        <div id="load-more-trigger" style="height: 1px;"></div>

        {{-- MODAL OPTIONS --}}
        <div class="modal fade" id="menuOptionsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMenuName">Menu Options</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="selectedMenuId">
                        <input type="hidden" id="selectedMenuPrice">

                        {{-- ✅ OPTION GROUPS CONTAINER (termasuk textarea jika ada type='text') --}}
                        <div id="optionGroupsContainer"></div>

                        {{-- ❌ HAPUS: Textarea Catatan Hardcoded --}}
                        {{-- Textarea akan muncul otomatis dari renderOptions() jika ada config type='text' --}}

                        <div class="mt-3">
                            <label class="form-label">Jumlah</label>
                            <div class="qty-selector-modal">
                                <button type="button" class="qty-btn-modal qty-minus-modal" disabled>
                                    <i class="mdi mdi-minus"></i>
                                </button>
                                <span class="qty-display-modal" id="modalQty">1</span>
                                <button type="button" class="qty-btn-modal qty-plus-modal">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Total Harga:</strong>
                                <strong class="text-primary" id="modalTotalPrice">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="addToCartBtn">
                            <i class="mdi mdi-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL CUSTOMER INFO --}}
        <div class="modal fade" id="customerInfoModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-account-edit"></i> Informasi Pelanggan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="customerInfoForm">
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="mdi mdi-information"></i>
                                Mohon isi data Anda untuk melanjutkan pesanan
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="customerName"
                                    placeholder="Masukkan nama Anda" required minlength="3" maxlength="100">
                                <div class="invalid-feedback">Nama minimal 3 karakter</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Email <span class="text-muted">(Opsional)</span>
                                </label>
                                <input type="email" class="form-control" id="customerEmail"
                                    placeholder="contoh@email.com" maxlength="100">
                                <div class="invalid-feedback">Format email tidak valid</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    No. Telepon <span class="text-muted">(Opsional)</span>
                                </label>
                                <input type="tel" class="form-control" id="customerPhone" placeholder="08123456789"
                                    pattern="[0-9]{10,15}" maxlength="15">
                                <div class="invalid-feedback">Nomor telepon 10-15 digit</div>
                                <small class="text-muted">Format: 08xxxxxxxxxx (10-15 digit)</small>
                            </div>

                            {{-- SUMMARY --}}
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="mb-3">Ringkasan Pesanan</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Jumlah Item:</span>
                                    <strong id="summaryItemCount">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong id="summarySubtotal">Rp 0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Pajak (10%):</span>
                                    <strong id="summaryTax">Rp 0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Service Fee (50%):</span>
                                    <strong id="summaryServiceFee">Rp 0</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong class="text-primary fs-5" id="summaryTotal">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Kembali
                            </button>
                            <button type="submit" class="btn btn-primary" id="proceedToOrderBtn">
                                <i class="mdi mdi-check-circle"></i> Lanjutkan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- FIXED CART BUTTON --}}
    <div class="cart-fixed-bottom">
        <button type="button" class="cart-btn" id="submitOrderBtn" disabled>
            <span>
                <i class="mdi mdi-cart"></i>
                <span id="cartItemCount">Pilih Menu</span>
            </span>
            <span class="cart-total" id="cartTotal">Rp 0</span>
        </button>
    </div>

    {{-- HIDDEN FORM --}}
    <form action="{{ route('order.store') }}" method="POST" id="orderForm" style="display: none;">
        @csrf
        <input type="hidden" name="table_id" value="{{ $table->id }}">
        <input type="hidden" name="customer_name" id="hiddenCustomerName">
        <input type="hidden" name="customer_email" id="hiddenCustomerEmail">
        <input type="hidden" name="customer_phone" id="hiddenCustomerPhone">
        <div id="orderItems"></div>
    </form>
@endsection

@push('scripts')
    <script>
        // =============================
        // CONFIGURATION
        // =============================
        const tableParam = '{{ request()->query('table') }}';
        const TAX_RATE = 0.10; // 10%
        const SERVICE_FEE_RATE = 0.50; // 50%

        // =============================
        // CART MANAGEMENT
        // =============================
        const cart = {};

        function updateCart() {
            let totalItems = 0;
            let totalPrice = 0;

            Object.values(cart).forEach(menuData => {
                if (menuData.items && Array.isArray(menuData.items)) {
                    menuData.items.forEach(item => {
                        totalItems += item.qty || 0;
                        totalPrice += (item.price || 0) * (item.qty || 0);
                    });
                }
            });

            const itemCount = Object.keys(cart).length;
            const submitBtn = document.getElementById('submitOrderBtn');
            const cartItemCount = document.getElementById('cartItemCount');
            const cartTotal = document.getElementById('cartTotal');

            if (itemCount > 0 && totalItems > 0) {
                submitBtn.disabled = false;
                cartItemCount.textContent = `${totalItems} Item`;

                const tax = totalPrice * TAX_RATE;
                const serviceFee = tax * SERVICE_FEE_RATE;
                const grandTotal = totalPrice + tax + serviceFee;

                cartTotal.textContent = `Rp ${Math.round(grandTotal).toLocaleString('id-ID')}`;
            } else {
                submitBtn.disabled = true;
                cartItemCount.textContent = 'Pilih Menu';
                cartTotal.textContent = 'Rp 0';
            }
        }

        // =============================
        // MENU OPTIONS MODAL
        // =============================
        const optionsModal = new bootstrap.Modal(document.getElementById('menuOptionsModal'));
        let currentMenuData = null;
        let modalQty = 1;
        let selectedOptions = {};

        document.addEventListener('click', async function(e) {
            const plusBtn = e.target.closest('.qty-plus');
            if (plusBtn) {
                e.preventDefault();
                e.stopPropagation();

                const menuId = plusBtn.dataset.menuId;
                const basePrice = parseFloat(plusBtn.dataset.price);

                try {
                    const response = await fetch(`/api/menus/${menuId}/options`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (!data.option_groups || data.option_groups.length === 0) {
                        handleDirectAddToCart(menuId, basePrice);
                        return;
                    }

                    showOptionsModal(data);
                } catch (error) {
                    console.error('Error:', error);
                    handleDirectAddToCart(menuId, basePrice);
                }
            }

            const minusBtn = e.target.closest('.qty-minus');
            if (minusBtn) {
                e.preventDefault();
                e.stopPropagation();

                const menuId = minusBtn.dataset.menuId;
                if (cart[menuId] && cart[menuId].items && cart[menuId].items.length > 0) {
                    cart[menuId].items.pop();
                    if (cart[menuId].items.length === 0) {
                        delete cart[menuId];
                    }
                    updateMenuDisplay(menuId);
                    updateCart();
                }
            }
        });

        function handleDirectAddToCart(menuId, basePrice) {
            if (!cart[menuId]) {
                cart[menuId] = {
                    items: []
                };
            }

            cart[menuId].items.push({
                qty: 1,
                price: basePrice,
                base_price: basePrice,
                options: {}
            });

            updateMenuDisplay(menuId);
            updateCart();

            const qtyDisplay = document.getElementById(`qty-${menuId}`);
            if (qtyDisplay) {
                qtyDisplay.style.transform = 'scale(1.3)';
                setTimeout(() => qtyDisplay.style.transform = 'scale(1)', 150);
            }
        }

        function updateMenuDisplay(menuId) {
            const qtyDisplay = document.getElementById(`qty-${menuId}`);
            const minusBtn = document.querySelector(`.qty-minus[data-menu-id="${menuId}"]`);

            if (!qtyDisplay) return;

            const totalQty = cart[menuId] ?
                cart[menuId].items.reduce((sum, item) => sum + item.qty, 0) : 0;

            qtyDisplay.textContent = totalQty;
            if (minusBtn) minusBtn.disabled = totalQty === 0;
        }

        function showOptionsModal(menuData) {
            currentMenuData = menuData;
            modalQty = 1;
            selectedOptions = {};

            document.getElementById('modalMenuName').textContent = menuData.name;
            document.getElementById('selectedMenuId').value = menuData.id;
            document.getElementById('selectedMenuPrice').value = menuData.price;
            document.getElementById('modalQty').textContent = '1';
            document.querySelector('.qty-minus-modal').disabled = true;

            renderOptionGroups(menuData.option_groups);

            // ✅ Reset semua textarea option-text setelah render (jika ada)
            setTimeout(() => {
                document.querySelectorAll('.option-text').forEach(textarea => {
                    textarea.value = '';
                });
            }, 100);

            updateModalPrice();
            optionsModal.show();
        }

        function renderOptionGroups(optionGroups) {
            const container = document.getElementById('optionGroupsContainer');
            container.innerHTML = '';

            optionGroups.forEach((group) => {
                const groupHtml = `
                <div class="option-group mb-4" data-group-id="${group.id}">
                    <label class="form-label fw-bold">
                        ${group.name}
                        ${group.pivot.is_required ? '<span class="text-danger">*</span>' : ''}
                    </label>
                    <small class="text-muted d-block mb-2">${group.description || ''}</small>
                    ${renderOptions(group)}
                </div>`;
                container.insertAdjacentHTML('beforeend', groupHtml);
            });
        }

        function renderOptions(group) {
            if (group.type === 'text') {
                return `<textarea class="form-control option-text" data-group-id="${group.id}"
                    placeholder="Masukkan catatan..." rows="2"></textarea>`;
            }

            const inputType = group.type === 'single' ? 'radio' : 'checkbox';
            return group.options.map(option => `
            <div class="form-check">
                <input class="form-check-input option-choice" type="${inputType}"
                       name="option_group_${group.id}" data-group-id="${group.id}"
                       data-option-id="${option.id}" data-option-name="${option.name}"
                       data-extra-price="${option.extra_price}" id="option_${option.id}">
                <label class="form-check-label" for="option_${option.id}">
                    ${option.name}
                    ${option.extra_price > 0 ? `<span class="text-success">+Rp ${option.extra_price.toLocaleString('id-ID')}</span>` : ''}
                </label>
            </div>`).join('');
        }

        // ✅ Split event listener
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('option-choice')) {
                updateSelectedOptions();
                updateModalPrice();
            }
        });

        // ✅ Gunakan 'input' event untuk textarea
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('option-text')) {
                updateSelectedOptions();
                updateModalPrice();
            }
        });

        function updateSelectedOptions() {
            selectedOptions = {};

            document.querySelectorAll('.option-choice:checked').forEach(input => {
                const groupId = input.dataset.groupId;
                if (!selectedOptions[groupId]) selectedOptions[groupId] = [];
                selectedOptions[groupId].push({
                    option_id: input.dataset.optionId
                });
            });

            document.querySelectorAll('.option-text').forEach(textarea => {
                const groupId = textarea.dataset.groupId;
                const value = textarea.value.trim();

                console.log(`📝 Textarea groupId=${groupId}, value="${value}"`);

                if (value) {
                    selectedOptions[groupId] = [{
                        custom_value: value
                    }];
                }
            });

            console.log('✅ selectedOptions updated:', JSON.stringify(selectedOptions, null, 2));
        }

        function updateModalPrice() {
            const basePrice = parseFloat(document.getElementById('selectedMenuPrice').value);
            let extraPrice = 0;

            document.querySelectorAll('.option-choice:checked').forEach(input => {
                extraPrice += parseFloat(input.dataset.extraPrice) || 0;
            });

            const totalPerItem = basePrice + extraPrice;
            const totalPrice = totalPerItem * modalQty;

            document.getElementById('modalTotalPrice').textContent =
                `Rp ${totalPrice.toLocaleString('id-ID')}`;
        }

        document.querySelector('.qty-plus-modal').addEventListener('click', function() {
            modalQty++;
            document.getElementById('modalQty').textContent = modalQty;
            document.querySelector('.qty-minus-modal').disabled = false;
            updateModalPrice();
        });

        document.querySelector('.qty-minus-modal').addEventListener('click', function() {
            if (modalQty > 1) {
                modalQty--;
                document.getElementById('modalQty').textContent = modalQty;
                if (modalQty === 1) this.disabled = true;
                updateModalPrice();
            }
        });

        // ✅ PERBAIKAN UTAMA: Panggil updateSelectedOptions() sebelum validasi
        document.getElementById('addToCartBtn').addEventListener('click', function() {
            const menuId = document.getElementById('selectedMenuId').value;
            const basePrice = parseFloat(document.getElementById('selectedMenuPrice').value);

            // ✅ PENTING: Update selectedOptions dari textarea terakhir kali
            updateSelectedOptions();

            console.log('📋 Selected Options before validation:', JSON.stringify(selectedOptions, null, 2));

            // Validate required options
            const requiredGroups = currentMenuData.option_groups.filter(g => g.pivot.is_required);
            const missingRequired = requiredGroups.filter(g =>
                !selectedOptions[g.id] || selectedOptions[g.id].length === 0
            );

            if (missingRequired.length > 0) {
                alert(`Harap pilih: ${missingRequired.map(g => g.name).join(', ')}`);
                return;
            }

            // Hitung extra price untuk preview
            let extraPrice = 0;
            document.querySelectorAll('.option-choice:checked').forEach(input => {
                extraPrice += parseFloat(input.dataset.extraPrice) || 0;
            });
            const itemPrice = basePrice + extraPrice;

            if (!cart[menuId]) cart[menuId] = {
                items: []
            };

            cart[menuId].items.push({
                qty: modalQty,
                price: itemPrice,
                base_price: basePrice,
                options: JSON.parse(JSON.stringify(selectedOptions))
            });

            console.log('🛒 Cart after add:', JSON.stringify(cart, null, 2));

            updateMenuDisplay(menuId);
            updateCart();
            optionsModal.hide();

            showToast('Menu berhasil ditambahkan!');
        });

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'position-fixed top-0 end-0 m-3 alert alert-success';
            toast.style.zIndex = '9999';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // =============================
        // CUSTOMER INFO MODAL
        // =============================
        const customerModal = new bootstrap.Modal(document.getElementById('customerInfoModal'));

        document.getElementById('submitOrderBtn').addEventListener('click', function() {
            if (Object.keys(cart).length === 0) return;

            let totalItems = 0;
            let subtotal = 0;

            Object.values(cart).forEach(menuData => {
                menuData.items.forEach(item => {
                    totalItems += item.qty;
                    subtotal += item.price * item.qty;
                });
            });

            const tax = subtotal * TAX_RATE;
            const serviceFee = tax * SERVICE_FEE_RATE;
            const total = subtotal + tax + serviceFee;

            document.getElementById('summaryItemCount').textContent = totalItems;
            document.getElementById('summarySubtotal').textContent =
                `Rp ${Math.round(subtotal).toLocaleString('id-ID')}`;
            document.getElementById('summaryTax').textContent =
                `Rp ${Math.round(tax).toLocaleString('id-ID')}`;
            document.getElementById('summaryServiceFee').textContent =
                `Rp ${Math.round(serviceFee).toLocaleString('id-ID')}`;
            document.getElementById('summaryTotal').textContent =
                `Rp ${Math.round(total).toLocaleString('id-ID')}`;

            customerModal.show();
        });

        // ✅ HANDLE SUBMIT - HAPUS FIELD NOTES
        document.getElementById('customerInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();

            console.log('🛒 CART SEBELUM SUBMIT:', JSON.stringify(cart, null, 2));

            const customerName = document.getElementById('customerName').value.trim();
            const customerEmail = document.getElementById('customerEmail').value.trim();
            const customerPhone = document.getElementById('customerPhone').value.trim();

            if (customerName.length < 3) {
                document.getElementById('customerName').classList.add('is-invalid');
                return;
            }

            if (customerEmail && !isValidEmail(customerEmail)) {
                document.getElementById('customerEmail').classList.add('is-invalid');
                return;
            }

            if (customerPhone && !isValidPhone(customerPhone)) {
                document.getElementById('customerPhone').classList.add('is-invalid');
                return;
            }

            document.getElementById('hiddenCustomerName').value = customerName;
            document.getElementById('hiddenCustomerEmail').value = customerEmail;
            document.getElementById('hiddenCustomerPhone').value = customerPhone;

            const orderItemsDiv = document.getElementById('orderItems');
            orderItemsDiv.innerHTML = '';

            let itemIndex = 0;
            Object.entries(cart).forEach(([menuId, menuData]) => {
                menuData.items.forEach(item => {
                    console.log(`📝 Item ${itemIndex}:`, {
                        menuId,
                        qty: item.qty,
                        options: item.options
                    });

                    const sanitizedOptions = sanitizeOptions(item.options);

                    // ✅ HANYA KIRIM: menu_id, qty, options (TANPA notes)
                    orderItemsDiv.innerHTML += `
                        <input type="hidden" name="items[${itemIndex}][menu_id]" value="${escapeHtml(menuId)}">
                        <input type="hidden" name="items[${itemIndex}][qty]" value="${Math.max(1, parseInt(item.qty))}">
                        <input type="hidden" name="items[${itemIndex}][options]" value='${JSON.stringify(sanitizedOptions)}'>
                    `;
                    itemIndex++;
                });
            });

            const formData = new FormData(document.getElementById('orderForm'));
            console.log('📤 FORM DATA:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            document.getElementById('proceedToOrderBtn').disabled = true;
            document.getElementById('proceedToOrderBtn').innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            document.getElementById('orderForm').submit();
        });

        ['customerName', 'customerEmail', 'customerPhone'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });

        // ✅ HELPER FUNCTIONS
        function sanitizeOptions(options) {
            const sanitized = {};
            for (const [groupId, optionsArray] of Object.entries(options)) {
                sanitized[groupId] = optionsArray.map(opt => {
                    if (opt.option_id) {
                        return {
                            option_id: opt.option_id
                        };
                    } else if (opt.custom_value) {
                        return {
                            custom_value: opt.custom_value
                        };
                    }
                    return null;
                }).filter(Boolean);
            }
            return sanitized;
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPhone(phone) {
            return /^[0-9]{10,15}$/.test(phone);
        }

        // =============================
        // SEARCH & PAGINATION
        // =============================
        let nextCursor = @json($data->nextCursor()?->encode());
        let loading = false;
        let searchQuery = '{{ request('search') }}';
        let activeCategory = '{{ request('category_id') }}';

        const trigger = document.getElementById('load-more-trigger');
        const wrapper = document.getElementById('table-wrapper');
        const loadingEl = document.getElementById('loading');
        const searchInput = document.getElementById('searchInput');
        const categoryTabs = document.querySelectorAll('.category-tab');

        function buildUrl(params = {}) {
            const url = new URL(window.location.href);
            url.search = '';
            url.searchParams.set('table', tableParam);
            Object.entries(params).forEach(([key, value]) => {
                if (value) url.searchParams.set(key, value);
            });
            return url.toString();
        }

        async function triggerSearch() {
            if (loading) return;
            loading = true;
            observer.disconnect();
            wrapper.innerHTML = '';
            loadingEl.classList.remove('d-none');

            const response = await fetch(buildUrl({
                search: searchQuery,
                category_id: activeCategory
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            wrapper.innerHTML = await response.text();
            nextCursor = response.headers.get('X-Cursor') ?? null;
            loading = false;
            loadingEl.classList.add('d-none');
            if (nextCursor) observer.observe(trigger);
        }

        let debounceTimer;
        searchInput?.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            searchQuery = this.value;
            debounceTimer = setTimeout(triggerSearch, 400);
        });

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                if (tab.classList.contains('active')) return;
                categoryTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                activeCategory = tab.dataset.category;
                triggerSearch();
            });
        });

        const observer = new IntersectionObserver(async (entries) => {
            if (!entries[0].isIntersecting || loading || !nextCursor) return;
            loading = true;
            loadingEl.classList.remove('d-none');

            const response = await fetch(buildUrl({
                cursor: nextCursor,
                search: searchQuery,
                category_id: activeCategory
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            wrapper.insertAdjacentHTML('beforeend', await response.text());
            nextCursor = response.headers.get('X-Cursor') ?? null;
            loading = false;
            loadingEl.classList.add('d-none');
            if (!nextCursor) observer.disconnect();
        }, {
            rootMargin: '200px'
        });

        if (nextCursor) observer.observe(trigger);
    </script>
@endpush
