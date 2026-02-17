{{-- <script>
    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('.btn-update-status').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('orderStatus').value = this.dataset.status;
                document.getElementById('updateStatusForm').action =
                    `/orders/${this.dataset.id}`;
            });
        });

        document.querySelectorAll('.btn-delete-order').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('deleteOrderCode').innerText = this.dataset.code;
                document.getElementById('deleteOrderForm').action =
                    `/orders/${this.dataset.id}`;
            });
        });
    });
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // RENDER STATUS BADGE
        function renderStatusBadge(status) {
            const badge = document.getElementById('orderStatusBadge');

            const map = {
                pending: 'bg-warning text-dark',
                paid: 'bg-info',
                completed: 'bg-success',
                cancelled: 'bg-danger'
            };

            badge.className = `badge fs-6 px-3 py-2 ${map[status]}`;
            badge.innerText = status.toUpperCase();
        }

        // ===============================
        // DETAIL ORDER (INI YANG KURANG)
        // ===============================
        // ===============================
        // RENDER STATUS BADGE
        // ===============================
        function renderStatusBadge(status) {
            const badge = document.getElementById('orderStatusBadge');
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
        // FORMAT CURRENCY
        // ===============================
        function rupiah(val) {
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        }

        // ===============================
        // RENDER SATU ITEM CARD
        // ===============================
        function renderItemCard(item) {
            // Render options jika ada
            let optionsHTML = '';
            if (item.options && item.options.length > 0) {
                // Group berdasarkan option_group_name
                const groups = {};
                item.options.forEach(opt => {
                    const groupName = opt.option_group_name || 'Pilihan';
                    if (!groups[groupName]) groups[groupName] = [];
                    groups[groupName].push(opt);
                });

                optionsHTML = `<div class="mt-2 pt-2 border-top">`;
                Object.entries(groups).forEach(([groupName, opts]) => {
                    optionsHTML += `
                    <div class="mb-1">
                        <small class="text-muted fw-semibold" style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;">
                            ${groupName}
                        </small>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                `;
                    opts.forEach(opt => {
                        // Tentukan label: pakai option_name, atau custom_value jika null
                        const label = opt.option_name ?? opt.custom_value ??
                            '—';
                        const priceTag = opt.option_price > 0 ?
                            `<span class="text-success ms-1" style="font-size:10px;">+${rupiah(opt.option_price)}</span>` :
                            '';

                        // Warna badge berdasarkan tipe group
                        const badgeStyle = opt.option_group_type === 'radio' ?
                            'background:#dbeafe;color:#1e40af;' :
                            opt.option_group_type === 'checkbox' ?
                            'background:#f0fdf4;color:#166534;' :
                            'background:#fef3c7;color:#92400e;'; // text/custom

                        optionsHTML += `
                        <span class="badge rounded-pill px-2 py-1 d-flex align-items-center gap-1"
                              style="font-size:11px;font-weight:500;${badgeStyle}">
                            ${label}${priceTag}
                        </span>
                    `;
                    });
                    optionsHTML += `</div></div>`;
                });
                optionsHTML += `</div>`;
            }

            return `
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-between gap-3">

                        {{-- Nama + qty + options --}}
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-2 py-1"
                                      style="font-size:11px;min-width:28px;">x${item.qty}</span>
                                <span class="fw-semibold text-dark" style="font-size:14px;">${item.menu.name}</span>
                            </div>

                            ${optionsHTML}
                        </div>

                        {{-- Harga satuan + subtotal --}}
                        <div class="text-end flex-shrink-0">
                            <div class="text-muted" style="font-size:11px;">
                                @${rupiah(item.price)}
                            </div>
                            <div class="fw-bold text-dark" style="font-size:15px;font-family:'Courier New',monospace;">
                                ${rupiah(item.subtotal)}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        `;
        }

        // ===============================
        // EVENT DELEGATION — btn-detail
        // ===============================
        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('.btn-detail');
            if (!btn) return;

            const id = btn.dataset.id;

            // Reset & tampilkan loading
            document.getElementById('orderCode').innerText = '—';
            document.getElementById('customerName').innerText = '—';
            document.getElementById('tableCode').innerText = '—';
            document.getElementById('tableFloor').innerText = '—';
            document.getElementById('orderTime').innerText = '—';
            document.getElementById('totalPrice').innerText = '—';
            document.getElementById('orderItems').innerHTML = '';
            document.getElementById('itemsLoading').classList.remove('d-none');
            document.getElementById('orderItemsContainer').classList.add('d-none');
            document.getElementById('noteWrap').classList.add('d-none');
            document.getElementById('buktiWrap').classList.add('d-none');
            document.getElementById('summaryWrap').style.display = 'none';

            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();

            try {
                const res = await fetch(`/orders/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) throw new Error('Gagal load order');

                const data = await res.json();

                // ─── INFO HEADER ──────────────────────────────────────
                document.getElementById('orderCode').innerText = data.order_code;
                document.getElementById('customerName').innerText = data
                    .customer_name;
                document.getElementById('tableCode').innerText = data.tableKode ??
                    '-';
                document.getElementById('tableFloor').innerText = data.tableFloor ?
                    `Lantai ${data.tableFloor}` : '';
                document.getElementById('totalPrice').innerText = rupiah(data
                    .total_price);

                // Format waktu order
                if (data.created_at) {
                    const d = new Date(data.created_at);
                    document.getElementById('orderTime').innerText =
                        d.toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                }

                renderStatusBadge(data.status);

                // ─── BUTTON VISIBILITY ────────────────────────────────
                document.getElementById('btnPay').classList.toggle('d-none', data
                    .status !== 'pending');
                document.getElementById('btnComplete').classList.toggle('d-none',
                    data.status !== 'paid');

                // ─── NOTE PEMBAYARAN ──────────────────────────────────
                if (data.notePembayaran) {
                    document.getElementById('notePembayaran').innerText = data
                        .notePembayaran;
                    document.getElementById('noteWrap').classList.remove('d-none');
                }

                // ─── BUKTI PEMBAYARAN ─────────────────────────────────
                if (data.buktiPembayaran) {
                    document.getElementById('buktiImg').src =
                        `/storage/${data.buktiPembayaran}`;
                    document.getElementById('buktiWrap').classList.remove('d-none');
                }

                // ─── ITEMS ────────────────────────────────────────────
                document.getElementById('itemsLoading').classList.add('d-none');
                document.getElementById('orderItemsContainer').classList.remove(
                    'd-none');

                const container = document.getElementById('orderItems');

                if (data.items && data.items.length) {
                    document.getElementById('itemCount').innerText =
                        `${data.items.length} item`;
                    container.innerHTML = data.items.map(renderItemCard).join('');
                } else {
                    document.getElementById('itemCount').innerText = '0 item';
                    container.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="mdi mdi-food-off" style="font-size:40px;opacity:0.3;"></i>
                        <p class="mt-2 mb-0">Tidak ada item</p>
                    </div>
                `;
                }

                // ─── SUMMARY ─────────────────────────────────────────
                if (data.subtotal !== undefined) {
                    document.getElementById('summarySubtotal').innerText = rupiah(
                        data.subtotal);
                    document.getElementById('summaryTax').innerText = rupiah(data
                        .tax_amount);
                    document.getElementById('summaryService').innerText = rupiah(
                        data.service_fee);
                    document.getElementById('summaryTotal').innerText = rupiah(data
                        .total_price);
                    document.getElementById('summaryWrap').style.display = '';
                }

            } catch (err) {
                console.error(err);
                document.getElementById('itemsLoading').classList.add('d-none');
                document.getElementById('orderItemsContainer').classList.remove(
                    'd-none');
                document.getElementById('orderItems').innerHTML = `
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert-circle me-2"></i>Gagal memuat detail order.
                </div>
            `;
            }
        });

        // ===============================
        // EVENT DELEGATION — btn-delete-order
        // ===============================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-order');
            if (!btn) return;
            document.getElementById('deleteOrderCode').innerText = btn.dataset.code;
            document.getElementById('deleteOrderForm').action =
                `/orders/${btn.dataset.id}`;
        });

        // ===============================
        // EVENT DELEGATION — btn-update-status
        // ===============================
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-update-status');
            if (!btn) return;
            document.getElementById('orderStatus').value = btn.dataset.status;
            document.getElementById('updateStatusForm').action =
                `/orders/${btn.dataset.id}`;
        });

    });
</script>
<script>
    let soundEnabled = false;

    // ===========================
    // ENABLE SOUND NOTIFICATION
    // ===========================
    function enableSound() {
        const sound = document.getElementById('orderSound');
        const paymentSound = document.getElementById('paymentSound');
        const btn = document.getElementById('enableSoundBtn');

        // Unlock orderSound
        sound.play().then(() => {
            sound.pause();
            sound.currentTime = 0;

            // ✅ Unlock paymentSound juga setelah orderSound berhasil
            return paymentSound.play();
        }).then(() => {
            paymentSound.pause();
            paymentSound.currentTime = 0;

            // Baru set soundEnabled = true setelah KEDUANYA berhasil di-unlock
            soundEnabled = true;
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            btn.innerHTML = '🔔 Notifikasi Aktif';
            console.log('✅ Both sounds enabled');
            showToast('Notifikasi suara telah diaktifkan!', 'success');

        }).catch((err) => {
            console.error('❌ Error enabling sound:', err);
            showToast('Gagal mengaktifkan notifikasi suara', 'danger');
        });

    }

    // ===========================
    // SETUP BUTTON LISTENER
    // ===========================
    document.addEventListener('DOMContentLoaded', () => {
        console.log('🔍 DOM loaded');

        const sound = document.getElementById('orderSound');
        const paymentSound = document.getElementById('paymentSound');
        const reverbStatus = document.getElementById('reverbStatus');
        const enableSoundBtn = document.getElementById('enableSoundBtn');

        // ✅ Attach event listener
        if (enableSoundBtn) {
            enableSoundBtn.addEventListener('click', enableSound);
        }

        // Cek apakah Echo tersedia
        if (!window.Echo) {
            console.error('❌ Echo belum siap! Pastikan npm run dev sudah running.');
            showToast('Echo tidak tersedia! Refresh halaman.', 'danger');
            return;
        }

        console.log('✅ Echo tersedia, subscribing ke channel orders...');

        // Monitor connection state
        if (window.Echo.connector && window.Echo.connector.pusher) {
            window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                console.log('📡 Reverb state:', states.previous, '→', states.current);

                if (states.current === 'connected') {
                    console.log('✅ Connected to Reverb!');
                    reverbStatus.classList.remove('d-none');
                    showToast('Terhubung ke real-time server!', 'success', 3000);
                } else if (states.current === 'disconnected') {
                    console.log('❌ Disconnected from Reverb');
                    reverbStatus.classList.add('d-none');
                }
            });
        }

        // Subscribe ke channel 'orders'
        window.Echo.channel('orders')
            .listen('.order.created', (data) => {
                console.log('🔔 ORDER BARU MASUK:', data);

                // 🔊 PLAY SOUND
                if (soundEnabled && sound) {
                    console.log('🔊 Playing sound...');
                    sound.currentTime = 0;
                    sound.play()
                        .then(() => console.log('✅ Sound played'))
                        .catch(err => console.error('❌ Error playing sound:', err));
                } else {
                    console.log('🔇 Sound disabled or not activated yet');
                }

                // 🔔 TOAST NOTIFICATION
                const tableName = data.tableKode || 'N/A';
                const message = `
                        <strong>Order Baru!</strong><br>
                        <small>
                            ${data.order_code}<br>
                            Customer: ${data.customer_name}<br>
                            Meja: ${tableKode}<br>
                            Total: Rp ${data.total_price}
                        </small>
                    `;

                showToast(message, 'success', 8000);

                // 🔄 REFRESH DATA
                console.log('🔄 Reloading data...');
                if (typeof loadData === 'function') {
                    loadData(true);
                } else {
                    console.log('⚠️ loadData function not found, reloading page in 2s...');
                    setTimeout(() => location.reload(), 2000);
                }
            })
            // ✅ TAMBAHKAN LISTENER BARU INI — payment.uploaded
            .listen('.payment.uploaded', (data) => {
                console.log('💳 BUKTI PEMBAYARAN MASUK:', data);

                // 🔊 PLAY SOUND (sama seperti order baru)
                if (soundEnabled && paymentSound) {
                    paymentSound.currentTime = 0;
                    paymentSound.play()
                        .then(() => console.log('✅ Sound played'))
                        .catch(err => console.error('❌ Error playing sound:', err));
                }

                // 🔔 TOAST — warna berbeda (info) biar beda dari order baru
                const message = `
            <strong>💳 Bukti Pembayaran Masuk!</strong><br>
            <small>
                ${data.order_code}<br>
                Customer: ${data.customer_name}<br>
                Meja: ${data.table_name}<br>
                Total: Rp ${data.total_price}<br>
                Status: <strong>PAID</strong>
            </small>
        `;

                showToast(message, 'info', 8000);

                // 🔄 REFRESH TABLE — agar status berubah ke PAID di list
                if (typeof loadData === 'function') {
                    loadData(true);
                } else {
                    setTimeout(() => location.reload(), 2000);
                }
            })

            .subscribed(() => {
                console.log('✅✅✅ BERHASIL SUBSCRIBE KE CHANNEL ORDERS');
            })
            .error((error) => {
                console.error('❌ Error subscribing:', error);
                showToast('Gagal subscribe ke channel orders', 'danger');
            });
    });

    // ===========================
    // TOAST HELPER FUNCTION
    // ===========================
    function showToast(message, type = 'success', duration = 5000) {
        const bgClass = type === 'success' ? 'bg-success' :
            type === 'danger' ? 'bg-danger' :
            type === 'warning' ? 'bg-warning' : 'bg-info';

        const toast = document.createElement('div');
        toast.className = `toast show align-items-center text-white ${bgClass} position-fixed bottom-0 end-0 m-4`;
        toast.style.zIndex = '9999';
        toast.style.minWidth = '300px';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
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

    // ===============================
    // LOAD DATA FUNCTION
    // ===============================
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
        if (nextCursor) {
            url += `&cursor=${nextCursor}`;
        }

        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const html = await response.text();
        wrapper.insertAdjacentHTML('beforeend', html);

        // ambil cursor baru
        const cursorHeader = response.headers.get('X-Cursor');
        nextCursor = cursorHeader ? cursorHeader : null;

        loading = false;
        loadingEl.classList.add('d-none');

        if (nextCursor) {
            observer.observe(trigger);
        }
    }

    // ===============================
    // INTERSECTION OBSERVER
    // ===============================
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && nextCursor) {
            loadData();
        }
    }, {
        rootMargin: '200px'
    });

    observer.observe(trigger);

    // ===============================
    // FILTER BUTTON EVENT
    // ===============================
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            status = btn.dataset.value; // ✅ FIXED
            loadData(true); // reset data + cursor
        });
    });
</script>
