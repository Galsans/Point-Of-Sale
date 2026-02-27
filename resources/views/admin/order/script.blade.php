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

        // Null-safe DOM helpers — tidak crash jika elemen tidak ada
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.innerText = value ?? '—';
        }

        function setHtml(id, value) {
            const el = document.getElementById(id);
            if (el) el.innerHTML = value ?? '';
        }

        function show(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('d-none');
        }

        function hide(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('d-none');
        }

        function toggleHide(id, shouldHide) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('d-none', shouldHide);
        }

        // ===============================
        // RENDER STATUS BADGE
        // ===============================
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
                cls: 'bg-secondary text-white',
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
                            // Catatan bebas (tipe text)
                            optionsHTML += `
                                <span class="d-block small text-muted fst-italic w-100">
                                    <i class="mdi mdi-note-outline me-1"></i>${escHtml(opt.custom_value)}
                                </span>`;
                        } else {
                            // Pilihan terstruktur (radio / checkbox)
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
        // Menggunakan struktur modal BARU:
        //   #orderItemsMenu   → container item menu biasa
        //   #orderItemsPackage → container item paket
        //   #sectionMenu      → wrapper section menu (dengan label)
        //   #sectionPackage   → wrapper section paket (dengan label)
        //   #sectionDivider   → <hr> pemisah
        // ===============================
        function renderAllItems(items) {
            const menuContainer = document.getElementById('orderItemsMenu');
            const packageContainer = document.getElementById('orderItemsPackage');
            const sectionMenu = document.getElementById('sectionMenu');
            const sectionPackage = document.getElementById('sectionPackage');
            const sectionDivider = document.getElementById('sectionDivider');

            // Bersihkan isi lama
            if (menuContainer) menuContainer.innerHTML = '';
            if (packageContainer) packageContainer.innerHTML = '';

            // Sembunyikan semua section dulu
            if (sectionMenu) sectionMenu.classList.add('d-none');
            if (sectionPackage) sectionPackage.classList.add('d-none');
            if (sectionDivider) sectionDivider.classList.add('d-none');

            const menuItems = items.filter(i => i.item_type === 'menu');
            const packageItems = items.filter(i => i.item_type === 'package');

            // ── Section: Menu Biasa
            if (menuItems.length > 0 && menuContainer && sectionMenu) {
                menuContainer.innerHTML = menuItems.map(renderMenuCard).join('');
                sectionMenu.classList.remove('d-none');
            }

            // ── Divider — hanya jika keduanya ada
            if (menuItems.length > 0 && packageItems.length > 0 && sectionDivider) {
                sectionDivider.classList.remove('d-none');
            }

            // ── Section: Paket Bundling
            if (packageItems.length > 0 && packageContainer && sectionPackage) {
                packageContainer.innerHTML = packageItems.map(renderPackageCard).join('');
                sectionPackage.classList.remove('d-none');
            }

            // ── Kosong
            if (items.length === 0 && menuContainer) {
                menuContainer.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="mdi mdi-food-off" style="font-size:40px;opacity:0.3;"></i>
                        <p class="mt-2 mb-0">Tidak ada item</p>
                    </div>`;
                if (sectionMenu) sectionMenu.classList.remove('d-none');
            }

            // Update badge jumlah
            const itemCountEl = document.getElementById('itemCount');
            if (itemCountEl) itemCountEl.innerText = `${items.length} item`;
        }

        // ===============================
        // RESET MODAL ke loading state
        // ===============================
        function resetModal() {
            ['orderCode', 'customerName', 'tableCode', 'tableFloor', 'orderTime', 'totalPrice']
            .forEach(id => setText(id, '—'));

            // Reset sections
            ['sectionMenu', 'sectionPackage', 'sectionDivider'].forEach(hide);

            const menuContainer = document.getElementById('orderItemsMenu');
            const pkgContainer = document.getElementById('orderItemsPackage');
            if (menuContainer) menuContainer.innerHTML = '';
            if (pkgContainer) pkgContainer.innerHTML = '';

            hide('noteWrap');
            hide('buktiWrap');
            hide('btnPay');
            hide('btnComplete');
            show('itemsLoading');
            hide('orderItemsContainer');

            const summaryWrap = document.getElementById('summaryWrap');
            if (summaryWrap) summaryWrap.style.setProperty('display', 'none', 'important');
        }

        // ===============================
        // EVENT DELEGATION — btn-detail
        // ===============================
        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('.btn-detail');
            if (!btn) return;

            const id = btn.dataset.id;

            resetModal();
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

                // ── Items — render ke section yang sesuai
                hide('itemsLoading');
                show('orderItemsContainer');
                renderAllItems(data.items ?? []);

                // ── Summary
                if (data.subtotal !== undefined) {
                    setText('summarySubtotal', rupiah(data.subtotal));
                    setText('summaryTax', rupiah(data.tax_amount));
                    setText('summaryService', rupiah(data.service_fee));
                    setText('summaryTotal', rupiah(data.total_price));
                    const summaryWrap = document.getElementById('summaryWrap');
                    if (summaryWrap) summaryWrap.style.removeProperty('display');
                }

            } catch (err) {
                console.error('Error loading order detail:', err);
                hide('itemsLoading');
                show('orderItemsContainer');
                // Tampilkan error di section menu karena itu yang pertama visible
                const menuContainer = document.getElementById('orderItemsMenu');
                if (menuContainer) {
                    menuContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="mdi mdi-alert-circle me-2"></i>Gagal memuat detail order.
                        </div>`;
                }
                const sectionMenu = document.getElementById('sectionMenu');
                if (sectionMenu) sectionMenu.classList.remove('d-none');
            }
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

            const id = btn.dataset.id;
            const status = btn.dataset.status;

            // Set form action & value
            const form = document.getElementById('updateStatusForm');
            const statusInput = document.getElementById('orderStatus');
            const message = document.getElementById('confirmMessage');

            form.action = `/orders/${id}`;
            statusInput.value = status;

            // Ubah pesan sesuai status
            if (status === 'paid') {
                message.textContent = 'Apakah kamu yakin ingin menandai order ini sebagai Dibayar?';
            } else if (status === 'completed') {
                message.textContent = 'Apakah kamu yakin ingin menyelesaikan order ini?';
            }
        });

        // Tombol "Ya" → submit form
        // Tombol "Ya" → Ajax instead of form submit
        document.getElementById('btnConfirmYes').addEventListener('click', function() {
            const form = document.getElementById('updateStatusForm');
            const formData = new FormData(form);
            const orderId = form.action.split('/').pop();

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'))
                            .hide();

                        // Update UI langsung tanpa tunggu Echo
                        updateOrderRowUI(data.order);
                    }
                })
                .catch(err => console.error(err));
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
                const tableName = data.tableKode || 'N/A';
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
            // ✅ TAMBAH INI
            .listen('.status.updated', (data) => {
                updateOrderRowUI(data.order);
                showToast(
                    `Status order <strong>${data.order.order_code}</strong> diubah ke <strong>${data.order.status.toUpperCase()}</strong>`,
                    'info', 5000);
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

    // ✅ Letakkan di luar DOMContentLoaded, setelah semua script
    function updateOrderRowUI(order) {
        const badgeMap = {
            pending: {
                cls: 'bg-warning text-dark',
                label: 'PENDING'
            },
            paid: {
                cls: 'bg-info',
                label: 'PAID'
            },
            completed: {
                cls: 'bg-success',
                label: 'COMPLETED'
            },
            cancelled: {
                cls: 'bg-danger',
                label: 'CANCELLED'
            },
        };
        const b = badgeMap[order.status] ?? {
            cls: 'bg-secondary',
            label: order.status.toUpperCase()
        };

        // Update card border
        const card = document.querySelector(`[data-order-id="${order.id}"] .card`);
        if (card) {
            card.classList.remove('border-warning', 'border-info', 'border-success', 'border-danger');
            const borderMap = {
                pending: 'border-warning',
                paid: 'border-info',
                completed: 'border-success',
                cancelled: 'border-danger',
            };
            if (borderMap[order.status]) card.classList.add(borderMap[order.status]);
        }

        // ✅ Samakan class badge dengan blade (fs-6 px-3 py-2)
        const badgeEl = document.querySelector(`[data-order-id="${order.id}"] .status-badge`);
        if (badgeEl) {
            badgeEl.className = `badge fs-6 px-3 py-2 status-badge ${b.cls}`;
            badgeEl.textContent = b.label;
        }

        // ✅ Samakan button dengan blade (btn-warning/btn-success, w-100)
        const actionEl = document.querySelector(`[data-order-id="${order.id}"] .action-buttons`);
        if (actionEl) {
            if (order.status === 'paid') {
                actionEl.innerHTML = `
                <button class="btn btn-success w-100 btn-update-status"
                    data-id="${order.id}" data-status="completed"
                    data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i class="mdi mdi-check"></i> Selesaikan Order
                </button>`;
            } else if (order.status === 'pending') {
                actionEl.innerHTML = `
                <button class="btn btn-warning w-100 btn-update-status"
                    data-id="${order.id}" data-status="paid"
                    data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                    <i class="mdi mdi-cash"></i> Tandai Dibayar
                </button>`;
            } else {
                actionEl.innerHTML = '';
            }
        }

        // Update modal jika sedang terbuka untuk order ini
        const modalCode = document.getElementById('orderCode');
        if (modalCode && modalCode.innerText === order.order_code) {
            if (typeof renderStatusBadge === 'function') renderStatusBadge(order.status);
            if (typeof toggleHide === 'function') {
                toggleHide('btnPay', order.status !== 'pending');
                toggleHide('btnComplete', order.status !== 'paid');
            }
        }
    }
</script>

<script>
    let nextCursor = @json($data->nextCursor()?->encode());
    let loading = false;
    let status = '';

    const trigger = document.getElementById('load-more-trigger');
    const wrapper = document.getElementById('table-wrapper');
    const loadingEl = document.getElementById('loading');
    const filterButtons = document.querySelectorAll('#availableFilterGroup button');

    async function loadData(reset = false) {
        if (loading) return;
        loading = true;
        loadingEl.classList.remove('d-none');

        if (reset) {
            wrapper.innerHTML = '';
            nextCursor = null;
            observer.disconnect();
        }

        let url = `?status=${status}`;
        if (nextCursor) url += `&cursor=${nextCursor}`;

        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        wrapper.insertAdjacentHTML('beforeend', await response.text());
        nextCursor = response.headers.get('X-Cursor') || null;

        loading = false;
        loadingEl.classList.add('d-none');

        if (nextCursor) observer.observe(trigger);
    }

    const observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && nextCursor) loadData();
    }, {
        rootMargin: '200px'
    });

    observer.observe(trigger);

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            status = btn.dataset.value;
            loadData(true);
        });
    });
</script>
