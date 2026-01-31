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
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', async function() {
                try {
                    const id = this.dataset.id;

                    const res = await fetch(`/orders/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) throw new Error('Gagal load order');

                    const data = await res.json();

                    // INFO
                    document.getElementById('orderCode').innerText = data.order_code;
                    document.getElementById('customerName').innerText = data.customer_name;
                    document.getElementById('tableCode').innerText =
                        data.tableKode ?? '-';


                    document.getElementById('totalPrice').innerText =
                        Number(data.total_price).toLocaleString('id-ID');

                    renderStatusBadge(data.status);

                    // BUTTON VISIBILITY
                    document.getElementById('btnPay').classList.toggle(
                        'd-none', data.status !== 'pending'
                    );
                    document.getElementById('btnComplete').classList.toggle(
                        'd-none', data.status !== 'paid'
                    );

                    // ITEMS
                    let rows = '';

                    if (data.items && data.items.length) {
                        data.items.forEach(item => {
                            rows += `
                        <tr>
                            <td>${item.menu.name}</td>
                            <td class="text-center">${item.qty}</td>
                            <td class="text-end">Rp ${Number(item.price).toLocaleString('id-ID')}</td>
                            <td class="text-end fw-semibold">
                                Rp ${Number(item.subtotal).toLocaleString('id-ID')}
                            </td>
                        </tr>
                    `;
                        });
                    } else {
                        rows = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Tidak ada item
                        </td>
                    </tr>
                `;
                    }

                    document.getElementById('orderItems').innerHTML = rows;

                    new bootstrap.Modal(
                        document.getElementById('orderDetailModal')
                    ).show();

                } catch (err) {
                    console.error(err);
                    alert('Gagal memuat detail order');
                }
            });
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
        const btn = document.getElementById('enableSoundBtn');

        sound.play().then(() => {
            sound.pause();
            sound.currentTime = 0;
            soundEnabled = true;
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            btn.innerHTML = '🔔 Notifikasi Aktif';
            console.log('✅ Sound enabled');
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
                const tableName = data.table_name || 'N/A';
                const message = `
                        <strong>Order Baru!</strong><br>
                        <small>
                            ${data.order_code}<br>
                            Customer: ${data.customer_name}<br>
                            Meja: ${tableName}<br>
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
