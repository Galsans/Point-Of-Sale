<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ===============================
        // HELPERS
        // ===============================
        function rupiah(val) {
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        }

        function escHtml(t) {
            if (!t) return '';
            return String(t).replace(/[&<>"']/g, m =>
                ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                } [m]));
        }

        function renderStatusBadge(status) {
            const badge = document.getElementById('orderStatusBadge');
            if (!badge) return;
            const map = {
                pending: {
                    cls: 'bg-warning text-dark',
                    label: '⏳ PENDING'
                },
                paid: {
                    cls: 'bg-info text-white',
                    label: '💳 PAID'
                },
                completed: {
                    cls: 'bg-success text-white',
                    label: '✅ COMPLETED'
                },
                cancelled: {
                    cls: 'bg-danger text-white',
                    label: '❌ CANCELLED'
                },
            };
            const s = map[status] ?? {
                cls: 'bg-secondary',
                label: status.toUpperCase()
            };
            badge.className = `badge px-3 py-2 ${s.cls}`;
            badge.innerText = s.label;
        }

        // ===============================
        // RENDER CARD — MENU BIASA
        // ===============================
        function renderMenuCard(item) {
            let optionsHTML = '';

            if (item.options && item.options.length > 0) {
                const groups = {};
                item.options.forEach(opt => {
                    const gName = opt.option_group_name || 'Pilihan';
                    if (!groups[gName]) groups[gName] = [];
                    groups[gName].push(opt);
                });

                optionsHTML = `<div class="mt-2 pt-2 border-top">`;
                Object.entries(groups).forEach(([groupName, opts]) => {
                    optionsHTML += `
                        <div class="mb-1">
                            <small class="text-muted fw-semibold"
                                style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                                ${escHtml(groupName)}
                            </small>
                            <div class="d-flex flex-wrap gap-1 mt-1">`;

                    opts.forEach(opt => {
                        if (opt.custom_value) {
                            optionsHTML += `
                                <span class="d-block small text-muted fst-italic w-100">
                                    <i class="mdi mdi-note-outline me-1"></i>${escHtml(opt.custom_value)}
                                </span>`;
                        } else {
                            const priceTag = opt.option_price > 0 ?
                                `<span class="text-success ms-1" style="font-size:10px;">+${rupiah(opt.option_price)}</span>` :
                                '';
                            const badgeStyle = opt.option_group_type === 'radio' ?
                                'background:#dbeafe;color:#1e40af;' :
                                opt.option_group_type === 'checkbox' ?
                                'background:#f0fdf4;color:#166534;' :
                                'background:#fef3c7;color:#92400e;';

                            optionsHTML += `
                                <span class="badge rounded-pill px-2 py-1 d-flex align-items-center gap-1"
                                    style="font-size:11px;font-weight:500;${badgeStyle}">
                                    ${escHtml(opt.option_name)}${priceTag}
                                </span>`;
                        }
                    });

                    optionsHTML += `</div></div>`;
                });
                optionsHTML += `</div>`;
            }

            return `
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary rounded-pill px-2 py-1"
                                        style="font-size:11px;min-width:28px;">x${item.qty}</span>
                                    <span class="fw-semibold text-dark" style="font-size:14px;">
                                        ${escHtml(item.menu_name)}
                                    </span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:12px;">
                                    @ ${rupiah(item.price)}
                                </div>
                                ${optionsHTML}
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold text-dark"
                                    style="font-size:15px;font-family:'Courier New',monospace;">
                                    ${rupiah(item.subtotal)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        // ===============================
        // RENDER CARD — PAKET BUNDLING
        // ===============================
        function renderPackageCard(item) {
            let contentsHTML = '';

            if (item.package_contents && item.package_contents.length > 0) {
                const tags = item.package_contents.map(c => {
                    const qty = c.quantity > 1 ? `${c.quantity}× ` : '';
                    return `<span class="badge rounded-pill px-2 py-1"
                                style="font-size:11px;font-weight:500;background:#fff3e0;color:#bf360c;">
                                ${qty}${escHtml(c.name)}
                            </span>`;
                }).join('');
                contentsHTML = `
                    <div class="mt-2 pt-2 border-top">
                        <small class="text-muted fw-semibold"
                            style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                            Isi Paket
                        </small>
                        <div class="d-flex flex-wrap gap-1 mt-1">${tags}</div>
                    </div>`;
            }

            return `
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden"
                    style="border-left:3px solid #ff6b35!important;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill px-2 py-1"
                                        style="font-size:11px;min-width:28px;background:#ff6b35;color:#fff;">
                                        x${item.qty}
                                    </span>
                                    <span class="fw-semibold text-dark" style="font-size:14px;">
                                        ${escHtml(item.item_name)}
                                    </span>
                                </div>
                                <div class="text-muted mt-1" style="font-size:12px;">
                                    @ ${rupiah(item.price)} / paket
                                </div>
                                ${contentsHTML}
                            </div>
                            <div class="text-end flex-shrink-0">
                                <div class="fw-bold text-dark"
                                    style="font-size:15px;font-family:'Courier New',monospace;">
                                    ${rupiah(item.subtotal)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        // ===============================
        // RENDER SEMUA ITEMS
        // Menulis ke #orderItems yang sudah ada di modal lama.
        // Tidak butuh elemen tambahan di HTML.
        // ===============================
        function renderAllItems(items) {
            // #orderItems HARUS ada di modal — ini satu-satunya elemen yang dibutuhkan
            const container = document.getElementById('orderItems');
            if (!container) return;

            container.innerHTML = '';

            const menuItems = items.filter(i => i.item_type === 'menu');
            const packageItems = items.filter(i => i.item_type === 'package');

            // ── Section: Menu Biasa
            if (menuItems.length > 0) {
                container.insertAdjacentHTML('beforeend', `
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="font-size:.95rem;">🍽️</span>
                        <span class="fw-semibold text-uppercase"
                            style="font-size:.7rem;letter-spacing:.07em;color:#aaa;">Menu</span>
                    </div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        ${menuItems.map(renderMenuCard).join('')}
                    </div>`);
            }

            // ── Divider hanya jika keduanya ada
            if (menuItems.length > 0 && packageItems.length > 0) {
                container.insertAdjacentHTML('beforeend', `<hr class="my-2">`);
            }

            // ── Section: Paket Bundling
            if (packageItems.length > 0) {
                container.insertAdjacentHTML('beforeend', `
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span style="font-size:.95rem;">🎁</span>
                        <span class="fw-semibold text-uppercase"
                            style="font-size:.7rem;letter-spacing:.07em;color:#aaa;">Paket Bundling</span>
                    </div>
                    <div class="d-flex flex-column gap-2 mb-3">
                        ${packageItems.map(renderPackageCard).join('')}
                    </div>`);
            }

            // ── Kosong
            if (items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="mdi mdi-food-off" style="font-size:40px;opacity:0.3;"></i>
                        <p class="mt-2 mb-0">Tidak ada item</p>
                    </div>`;
            }

            // Update badge count
            const itemCountEl = document.getElementById('itemCount');
            if (itemCountEl) itemCountEl.innerText = `${items.length} item`;
        }

        // ===============================
        // SAFE HELPER — set innerText
        // Agar tidak crash jika elemen tidak ditemukan
        // ===============================
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.innerText = value;
        }

        function setHtml(id, value) {
            const el = document.getElementById(id);
            if (el) el.innerHTML = value;
        }

        function show(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('d-none');
        }

        function hide(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('d-none');
        }

        function toggleHide(id, condition) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('d-none', condition);
        }

        // ===============================
        // EVENT DELEGATION — btn-detail
        // ===============================
        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('.btn-detail');
            if (!btn) return;

            const id = btn.dataset.id;

            // Reset semua field modal ke default
            ['orderCode', 'customerName', 'tableCode', 'tableFloor', 'orderTime', 'totalPrice']
            .forEach(elId => setText(elId, '—'));

            setHtml('orderItems', '');
            show('itemsLoading');
            hide('orderItemsContainer');
            hide('noteWrap');
            hide('buktiWrap');

            const summaryWrap = document.getElementById('summaryWrap');
            if (summaryWrap) summaryWrap.style.display = 'none';

            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();

            try {
                const res = await fetch(`/orders/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();

                // ── Info header
                setText('orderCode', data.order_code);
                setText('customerName', data.customer_name);
                setText('tableCode', data.tableKode ?? '—');
                setText('tableFloor', data.tableFloor ? `Lantai ${data.tableFloor}` : '');
                setText('totalPrice', rupiah(data.total_price));

                if (data.created_at) {
                    setText('orderTime', new Date(data.created_at).toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }));
                }

                renderStatusBadge(data.status);

                // ── Tombol aksi footer
                toggleHide('btnPay', data.status !== 'pending');
                toggleHide('btnComplete', data.status !== 'paid');

                // ── Catatan pembayaran
                if (data.notePembayaran) {
                    setText('notePembayaran', data.notePembayaran);
                    show('noteWrap');
                }

                // ── Bukti pembayaran
                if (data.buktiPembayaran) {
                    const buktiImg = document.getElementById('buktiImg');
                    if (buktiImg) buktiImg.src = `/storage/${data.buktiPembayaran}`;
                    show('buktiWrap');
                }

                // ── Items
                hide('itemsLoading');
                show('orderItemsContainer');
                renderAllItems(data.items ?? []);

                // ── Summary
                if (data.subtotal !== undefined) {
                    setText('summarySubtotal', rupiah(data.subtotal));
                    setText('summaryTax', rupiah(data.tax_amount));
                    setText('summaryService', rupiah(data.service_fee));
                    setText('summaryTotal', rupiah(data.total_price));
                    if (summaryWrap) summaryWrap.style.display = '';
                }

            } catch (err) {
                console.error('Error loading order detail:', err);
                hide('itemsLoading');
                show('orderItemsContainer');
                setHtml('orderItems', `
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle me-2"></i>Gagal memuat detail order.
                    </div>`);
            }
        });

        // ── Modal Detail Menu
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-detail-menu');
            if (!btn) return;

            e.preventDefault(); // ← penting! karena pakai <a href="#">

            // Isi data ke modal
            const name = btn.dataset.name;
            const category = btn.dataset.category;
            const price = Number(btn.dataset.price).toLocaleString('id-ID');
            const isAvailable = btn.dataset.isAvailable == '1';
            const image = btn.dataset.image;
            const description = btn.dataset.description;

            document.getElementById('detailMenuName').textContent = name;
            document.getElementById('detailMenuCategory').textContent = category;
            document.getElementById('detailMenuPrice').textContent = 'Rp ' + price;
            document.getElementById('detailMenuDescription').textContent = description;
            document.getElementById('detailMenuStatus').textContent = isAvailable ? 'Tersedia' :
            'Habis';
            document.getElementById('detailMenuStatus').className = 'badge ' + (isAvailable ?
                'bg-success' : 'bg-danger');

            const img = document.getElementById('detailMenuImage');
            if (img) img.src = image || '/assets/images/unnamed.jpg';

            new bootstrap.Modal(document.getElementById('detailMenuModal')).show();
        });
        
        // ===============================
        // EVENT DELEGATION — btn-delete-order
        // ===============================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-order');
            if (!btn) return;
            setText('deleteOrderCode', btn.dataset.code);
            const form = document.getElementById('deleteOrderForm');
            if (form) form.action = `/orders/${btn.dataset.id}`;
        });

        // ===============================
        // EVENT DELEGATION — btn-update-status
        // ===============================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-update-status');
            if (!btn) return;
            const statusInput = document.getElementById('orderStatus');
            if (statusInput) statusInput.value = btn.dataset.status;
            const form = document.getElementById('updateStatusForm');
            if (form) form.action = `/orders/${btn.dataset.id}`;
        });

    }); // END DOMContentLoaded
</script>

<script>
    let soundEnabled = false;

    function enableSound() {
        const sound = document.getElementById('orderSound');
        const paymentSound = document.getElementById('paymentSound');
        const btn = document.getElementById('enableSoundBtn');

        sound.play()
            .then(() => {
                sound.pause();
                sound.currentTime = 0;
                return paymentSound.play();
            })
            .then(() => {
                paymentSound.pause();
                paymentSound.currentTime = 0;
                soundEnabled = true;
                btn.classList.replace('btn-outline-secondary', 'btn-success');
                btn.innerHTML = '🔔 Notifikasi Aktif';
                showToast('Notifikasi suara telah diaktifkan!', 'success');
            })
            .catch(err => {
                console.error('❌ Error enabling sound:', err);
                showToast('Gagal mengaktifkan notifikasi suara', 'danger');
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const sound = document.getElementById('orderSound');
        const paymentSound = document.getElementById('paymentSound');
        const reverbStatus = document.getElementById('reverbStatus');
        const enableSoundBtn = document.getElementById('enableSoundBtn');

        if (enableSoundBtn) enableSoundBtn.addEventListener('click', enableSound);

        if (!window.Echo) {
            console.error('❌ Echo belum siap!');
            showToast('Echo tidak tersedia! Refresh halaman.', 'danger');
            return;
        }

        if (window.Echo.connector?.pusher) {
            window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                if (states.current === 'connected') {
                    if (reverbStatus) reverbStatus.classList.remove('d-none');
                    showToast('Terhubung ke real-time server!', 'success', 3000);
                } else if (states.current === 'disconnected') {
                    if (reverbStatus) reverbStatus.classList.add('d-none');
                }
            });
        }

        window.Echo.channel('orders')
            .listen('.order.created', (data) => {
                if (soundEnabled && sound) {
                    sound.currentTime = 0;
                    sound.play().catch(err => console.error('❌ Sound error:', err));
                }
                const tableName = data.tableKode || 'N/A'; // fix: was tableKode (undefined var)
                showToast(`
                    <strong>Order Baru!</strong><br>
                    <small>
                        ${data.order_code}<br>
                        Customer: ${data.customer_name}<br>
                        Meja: ${tableName}<br>
                        Total: Rp ${data.total_price}
                    </small>`, 'success', 8000);

                if (typeof loadData === 'function') loadData(true);
                else setTimeout(() => location.reload(), 2000);
            })
            .listen('.payment.uploaded', (data) => {
                if (soundEnabled && paymentSound) {
                    paymentSound.currentTime = 0;
                    paymentSound.play().catch(err => console.error('❌ Sound error:', err));
                }
                showToast(`
                    <strong>💳 Bukti Pembayaran Masuk!</strong><br>
                    <small>
                        ${data.order_code}<br>
                        Customer: ${data.customer_name}<br>
                        Meja: ${data.table_name}<br>
                        Total: Rp ${data.total_price}<br>
                        Status: <strong>PAID</strong>
                    </small>`, 'info', 8000);

                if (typeof loadData === 'function') loadData(true);
                else setTimeout(() => location.reload(), 2000);
            })
            .subscribed(() => console.log('✅ Subscribe ke channel orders berhasil'))
            .error(err => {
                console.error('❌ Error subscribing:', err);
                showToast('Gagal subscribe ke channel orders', 'danger');
            });
    });

    function showToast(message, type = 'success', duration = 5000) {
        const bgMap = {
            success: 'bg-success',
            danger: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };
        const toast = document.createElement('div');
        toast.className =
            `toast show align-items-center text-white ${bgMap[type] ?? 'bg-secondary'} position-fixed bottom-0 end-0 m-4`;
        toast.style.cssText = 'z-index:9999;min-width:300px;';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    onclick="this.parentElement.parentElement.remove()"></button>
            </div>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
</script>

<script>
    let nextCursor = @json(optional($data->nextCursor())->encode());
    let menuLoading = false;
    let currentCategory = '';
    let currentSearch = '{{ request('search') }}';
    let currentAvailable = '';
    let searchTimeout = null;

    const wrapper = document.getElementById('table-wrapper');
    const loadingEl = document.getElementById('loading');
    const trigger = document.getElementById('load-more-trigger');

    // ── Infinite scroll observer
    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && nextCursor) loadMenus();
    }, {
        rootMargin: '200px'
    });

    observer.observe(trigger);

    // ── Main fetch function
    async function loadMenus(reset = false) {
        if (menuLoading) return;
        menuLoading = true;
        loadingEl.classList.remove('d-none');

        if (reset) {
            wrapper.innerHTML = '';
            nextCursor = null;
            observer.disconnect();
        }

        const params = new URLSearchParams();
        if (currentCategory) params.set('category_id', currentCategory); // ✅ cocok dengan controller
        if (currentSearch) params.set('search', currentSearch);
        if (currentAvailable !== '') params.set('is_available', currentAvailable);
        if (nextCursor) params.set('cursor', nextCursor);

        try {
            const res = await fetch(`?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            wrapper.insertAdjacentHTML('beforeend', await res.text());
            nextCursor = res.headers.get('X-Cursor') || null;
        } catch (err) {
            console.error('Gagal load menus:', err);
        }

        menuLoading = false;
        loadingEl.classList.add('d-none');
        if (nextCursor) observer.observe(trigger);
    }

    // ── Category tabs
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.dataset.category; // ← value dari data-category di blade
            loadMenus(true);
        });
    });

    // ── Search — debounce 400ms
    document.getElementById('searchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = this.value.trim();
            loadMenus(true);
        }, 400);
    });

    // ── Filter Tersedia / Tidak Tersedia
    document.querySelectorAll('#availableFilterGroup button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#availableFilterGroup button')
                .forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentAvailable = this.dataset.value; // '' / '1' / '0'
            loadMenus(true);
        });
    });
</script>
