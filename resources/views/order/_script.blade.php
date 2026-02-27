@push('scripts')
    <script>
        // ── CONFIG ──
        const tableParam = '{{ request()->query('table') }}';
        const CART_ROUTE = "{{ route('order.cart') }}";
        const TAX_RATE = 0.10;
        const SERVICE_FEE_RATE = 0.05; // 5% dari subtotal (konsisten dengan cart.blade.php)
        const CART_KEY = 'restaurant_cart';
        const MENU_KEY = 'restaurant_menu_data';
        const cart = {};

        (function() {
            try {
                Object.assign(cart, JSON.parse(localStorage.getItem(CART_KEY) || '{}'));
            } catch (e) {}
        })();

        function saveCartToStorage() {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
        }

        // ── UPDATE CART BADGE ──
        function updateCart() {
            let totalItems = 0,
                totalPrice = 0;
            Object.values(cart).forEach(d => {
                if (d.items && Array.isArray(d.items)) d.items.forEach(i => {
                    totalItems += i.qty || 0;
                    totalPrice += (i.price || 0) * (i.qty || 0);
                });
            });
            const btn = document.getElementById('submitOrderBtn');
            const countEl = document.getElementById('cartItemCount');
            const totalEl = document.getElementById('cartTotal');
            const clearBtn = document.getElementById('clearCartBtn');
            if (totalItems > 0) {
                btn.disabled = false;
                countEl.textContent = totalItems + ' Item';
                const tax = totalPrice * TAX_RATE;
                const svc = totalPrice * SERVICE_FEE_RATE; // service fee dari subtotal, bukan dari tax
                totalEl.textContent = 'Rp ' + Math.round(totalPrice + tax + svc).toLocaleString('id-ID');
                clearBtn.classList.remove('d-none');
            } else {
                btn.disabled = true;
                countEl.textContent = 'Pilih Menu';
                totalEl.textContent = 'Rp 0';
                clearBtn.classList.add('d-none');
            }
        }

        // ── CAROUSEL ──
        (function() {
            const track = document.getElementById('pkgTrack');
            if (!track) return;
            const cards = track.querySelectorAll('.pkg-card');
            if (!cards.length) return;
            const dots = document.querySelectorAll('.pkg-dot');
            const btnPrev = document.getElementById('pkgPrev');
            const btnNext = document.getElementById('pkgNext');
            let idx = 0;
            const cw = () => cards[0].offsetWidth + 12;

            function go(i) {
                i = Math.max(0, Math.min(i, cards.length - 1));
                idx = i;
                track.scrollTo({
                    left: cw() * i,
                    behavior: 'smooth'
                });
                dots.forEach((d, j) => d.classList.toggle('active', j === i));
                if (btnPrev) btnPrev.disabled = i === 0;
                if (btnNext) btnNext.disabled = i >= cards.length - 1;
            }
            btnPrev?.addEventListener('click', () => go(idx - 1));
            btnNext?.addEventListener('click', () => go(idx + 1));
            dots.forEach((d, i) => d.addEventListener('click', () => go(i)));
            let st;
            track.addEventListener('scroll', () => {
                clearTimeout(st);
                st = setTimeout(() => go(Math.round(track.scrollLeft / cw())), 80);
            });
            let tx = 0;
            track.addEventListener('touchstart', e => {
                tx = e.touches[0].clientX;
            }, {
                passive: true
            });
            track.addEventListener('touchend', e => {
                const d = tx - e.changedTouches[0].clientX;
                if (Math.abs(d) > 40) go(idx + (d > 0 ? 1 : -1));
            }, {
                passive: true
            });
            if (btnPrev) btnPrev.disabled = true;
        })();

        // ── MODAL DETAIL PAKET ──
        const pkgDetailModal = new bootstrap.Modal(document.getElementById('packageDetailModal'));
        const CAT_EMOJI = {
            makanan: '🍽️',
            minuman: '🥤',
            dessert: '🍮'
        };
        let currentPkg = null,
            pkgQty = 1;

        function openPackageModal(packageId) {
            const card = document.querySelector('.pkg-card[data-id="' + packageId + '"]');
            if (!card) return;
            currentPkg = window.__pkgData[packageId];
            if (!currentPkg) {
                console.error('Package data not found for id:', packageId);
                return;
            }
            pkgQty = 1;

            document.getElementById('pkgModalTitle').textContent = currentPkg.name;
            document.getElementById('pkgModalDesc').textContent = currentPkg.description || '';

            const badgeEl = document.getElementById('pkgModalBadge');
            if (currentPkg.badge) {
                badgeEl.textContent = currentPkg.badge;
                badgeEl.classList.remove('d-none');
            } else {
                badgeEl.classList.add('d-none');
            }

            const imgEl = document.getElementById('pkgModalImg');
            const phEl = document.getElementById('pkgModalImgPlaceholder');
            if (currentPkg.image) {
                imgEl.src = currentPkg.image;
                imgEl.classList.remove('d-none');
                phEl.classList.add('d-none');
            } else {
                imgEl.classList.add('d-none');
                phEl.classList.remove('d-none');
            }

            document.getElementById('pkgModalPriceMain').textContent =
                'Rp ' + currentPkg.package_price.toLocaleString('id-ID');
            const origEl = document.getElementById('pkgModalPriceOriginal');
            const savEl = document.getElementById('pkgModalSavingsBadge');
            if (currentPkg.savings > 0) {
                origEl.textContent = 'Rp ' + currentPkg.original_price.toLocaleString('id-ID');
                origEl.classList.remove('d-none');
                savEl.textContent = 'Hemat ' + currentPkg.savings_percent + '%';
                savEl.classList.remove('d-none');
            } else {
                origEl.classList.add('d-none');
                savEl.classList.add('d-none');
            }

            document.getElementById('pkgModalItems').innerHTML = currentPkg.items.map(function(item) {
                const cat = (item.category || '').toLowerCase();
                const emoji = CAT_EMOJI[cat] || '🍴';
                const qtyBadge = item.quantity > 1 ?
                    '<span class="pkg-modal-item-qty">' + item.quantity + 'x</span>' :
                    '';
                return '<li class="pkg-modal-item">' +
                    '<div class="pkg-modal-item-icon ' + (cat || 'other') + '">' + emoji + '</div>' +
                    '<div class="pkg-modal-item-info">' +
                    '<p class="pkg-modal-item-name">' + item.name + '</p>' +
                    '<p class="pkg-modal-item-price">Rp ' + Number(item.price).toLocaleString('id-ID') + '</p>' +
                    '</div>' + qtyBadge + '</li>';
            }).join('');

            document.getElementById('pkgQtyValue').textContent = pkgQty;
            updateModalAddBtn();
            pkgDetailModal.show();
        }

        function updateModalAddBtn() {
            const total = currentPkg.package_price * pkgQty;
            document.getElementById('pkgModalAddBtnText').textContent =
                'Tambah — Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('pkgQtyMinus').addEventListener('click', function() {
            if (pkgQty > 1) {
                pkgQty--;
                document.getElementById('pkgQtyValue').textContent = pkgQty;
                updateModalAddBtn();
            }
        });
        document.getElementById('pkgQtyPlus').addEventListener('click', function() {
            if (pkgQty < 10) {
                pkgQty++;
                document.getElementById('pkgQtyValue').textContent = pkgQty;
                updateModalAddBtn();
            }
        });
        document.getElementById('pkgModalAddBtn').addEventListener('click', function() {
            if (!currentPkg) return;
            addPackageToCart(currentPkg.id, currentPkg.name, currentPkg.package_price, pkgQty);
            pkgDetailModal.hide();
        });

        // ── EVENT DELEGATION ──
        document.addEventListener('click', function(e) {
            // Klik tombol + paket
            const addBtn = e.target.closest('.js-pkg-add');
            if (addBtn) {
                e.stopPropagation();
                const card = addBtn.closest('.pkg-card');
                if (card) openPackageModal(parseInt(card.dataset.id));
                return;
            }
            // Klik card paket (seluruh area)
            const card = e.target.closest('.pkg-card');
            if (card) {
                if (e.target.closest('.qty-plus') || e.target.closest('.qty-minus')) return;
                openPackageModal(parseInt(card.dataset.id));
                return;
            }
            // Klik qty-plus menu biasa
            const plusBtn = e.target.closest('.qty-plus');
            if (plusBtn) {
                e.preventDefault();
                e.stopPropagation();
                const menuId = plusBtn.dataset.menuId;
                const basePrice = parseFloat(plusBtn.dataset.price);
                const menuName = plusBtn.dataset.name || '';
                (async function() {
                    try {
                        const r = await fetch('/api/menus/' + menuId + '/options', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const d = await r.json();
                        if (!d.option_groups || !d.option_groups.length) {
                            saveMenuMeta(menuId, d.name || menuName, basePrice, null);
                            handleDirectAddToCart(menuId, basePrice);
                            return;
                        }
                        showOptionsModal(d);
                    } catch (err) {
                        handleDirectAddToCart(menuId, basePrice);
                    }
                })();
                return;
            }
            // Klik qty-minus menu biasa
            const minusBtn = e.target.closest('.qty-minus');
            if (minusBtn) {
                e.preventDefault();
                e.stopPropagation();
                const menuId = minusBtn.dataset.menuId;
                if (cart[menuId]?.items?.length > 0) {
                    cart[menuId].items.pop();
                    if (!cart[menuId].items.length) delete cart[menuId];
                    saveCartToStorage();
                    updateMenuDisplay(menuId);
                    updateCart();
                }
            }
        });

        // ── ADD PACKAGE TO CART (localStorage only, NO background fetch) ──
        // Paket hanya disimpan di localStorage.
        // Server hanya menerima data saat user submit checkout (via store()).
        // Ini menyatukan flow menu biasa & paket sehingga tidak ada order prematur
        // dengan customer_name kosong / 'Walk-in'.
        function addPackageToCart(packageId, packageName, packagePrice, qty) {
            qty = qty || 1;
            const key = 'pkg_' + packageId;

            if (!cart[key]) cart[key] = {
                items: []
            };

            const existing = cart[key].items.find(function(i) {
                return i.is_package;
            });
            if (existing) {
                existing.qty = Math.min(existing.qty + qty, 10); // max 10
            } else {
                cart[key].items.push({
                    qty: qty,
                    price: packagePrice,
                    base_price: packagePrice,
                    is_package: true,
                    options: {}
                });
            }

            // Simpan metadata paket agar cart.blade.php bisa menampilkan nama
            savePackageMeta(packageId, packageName, packagePrice);

            saveCartToStorage();
            updateCart();

            // Visual feedback tombol +
            const addBtn = document.querySelector('.pkg-card[data-id="' + packageId + '"] .pkg-add-btn');
            if (addBtn) {
                addBtn.classList.add('added');
                addBtn.innerHTML = '<i class="mdi mdi-check"></i>';
                setTimeout(function() {
                    addBtn.classList.remove('added');
                    addBtn.innerHTML = '<i class="mdi mdi-plus"></i>';
                }, 1200);
            }

            showToast('✅ ' + packageName + ' (' + qty + 'x) ditambahkan ke keranjang');
        }

        // Simpan metadata paket ke MENU_KEY agar cart.blade.php punya nama paket
        function savePackageMeta(packageId, packageName, packagePrice) {
            try {
                const key = 'pkg_' + packageId;
                var m = JSON.parse(localStorage.getItem(MENU_KEY) || '{}');
                if (!m[key]) m[key] = {};
                m[key].name = packageName;
                m[key].price = packagePrice;
                localStorage.setItem(MENU_KEY, JSON.stringify(m));
            } catch (e) {}
        }

        // ── CLEAR CART ──
        const clearCartModal = new bootstrap.Modal(document.getElementById('clearCartModal'));
        document.getElementById('clearCartBtn').addEventListener('click', function() {
            clearCartModal.show();
        });
        document.getElementById('confirmClearBtn').addEventListener('click', function() {
            Object.keys(cart).forEach(function(k) {
                delete cart[k];
            });
            localStorage.removeItem(CART_KEY);
            localStorage.removeItem(MENU_KEY);
            document.querySelectorAll('[id^="qty-"]').forEach(function(el) {
                el.textContent = '0';
            });
            document.querySelectorAll('.qty-minus').forEach(function(b) {
                b.disabled = true;
            });
            saveCartToStorage();
            updateCart();
            clearCartModal.hide();
            showToast('Semua menu berhasil dihapus', 'danger');
        });

        // ── MENU OPTIONS MODAL ──
        const optionsModal = new bootstrap.Modal(document.getElementById('menuOptionsModal'));
        let currentMenuData = null,
            modalQty = 1,
            selectedOptions = {};

        function saveMenuMeta(menuId, name, price, optionGroups) {
            try {
                var m = JSON.parse(localStorage.getItem(MENU_KEY) || '{}');
                if (!m[menuId]) m[menuId] = {};
                m[menuId].name = name;
                m[menuId].price = price;
                if (optionGroups) {
                    m[menuId].option_meta = {};
                    optionGroups.forEach(function(g) {
                        m[menuId].option_meta[g.id] = {
                            groupName: g.name,
                            options: {}
                        };
                        (g.options || []).forEach(function(o) {
                            m[menuId].option_meta[g.id].options[o.id] = {
                                name: o.name,
                                extra_price: o.extra_price
                            };
                        });
                    });
                }
                localStorage.setItem(MENU_KEY, JSON.stringify(m));
            } catch (e) {}
        }

        function handleDirectAddToCart(menuId, basePrice) {
            if (!cart[menuId]) cart[menuId] = {
                items: []
            };
            cart[menuId].items.push({
                qty: 1,
                price: basePrice,
                base_price: basePrice,
                options: {}
            });
            saveCartToStorage();
            updateMenuDisplay(menuId);
            updateCart();
            var d = document.getElementById('qty-' + menuId);
            if (d) {
                d.style.transform = 'scale(1.3)';
                setTimeout(function() {
                    d.style.transform = 'scale(1)';
                }, 150);
            }
        }

        function updateMenuDisplay(menuId) {
            var d = document.getElementById('qty-' + menuId);
            var b = document.querySelector('.qty-minus[data-menu-id="' + menuId + '"]');
            if (!d) return;
            var q = cart[menuId] ?
                cart[menuId].items.reduce(function(s, i) {
                    return s + i.qty;
                }, 0) :
                0;
            d.textContent = q;
            if (b) b.disabled = q === 0;
        }

        function showOptionsModal(menuData) {
            currentMenuData = menuData;
            modalQty = 1;
            selectedOptions = {};
            document.getElementById('modalMenuName').textContent = menuData.name;
            document.getElementById('selectedMenuId').value = menuData.id;
            document.getElementById('selectedMenuPrice').value = menuData.price;
            renderOptionGroups(menuData.option_groups);
            setTimeout(function() {
                document.querySelectorAll('.option-text').forEach(function(t) {
                    t.value = '';
                });
            }, 100);
            updateModalPrice();
            optionsModal.show();
        }

        function renderOptionGroups(groups) {
            var c = document.getElementById('optionGroupsContainer');
            c.innerHTML = '';
            groups.forEach(function(g) {
                c.insertAdjacentHTML('beforeend',
                    '<div class="option-group mb-4" data-group-id="' + g.id + '">' +
                    '<label class="form-label fw-bold">' + g.name +
                    (g.pivot.is_required ? '<span class="text-danger">*</span>' : '') +
                    '</label><small class="text-muted d-block mb-2">' + (g.description || '') + '</small>' +
                    renderOptions(g) + '</div>');
            });
        }

        function renderOptions(g) {
            if (g.type === 'text')
                return '<textarea class="form-control option-text" data-group-id="' + g.id +
                    '" placeholder="Catatan..." rows="2"></textarea>';
            var t = g.type === 'single' ? 'radio' : 'checkbox';
            return g.options.map(function(o) {
                return '<div class="form-check"><input class="form-check-input option-choice" type="' + t +
                    '" name="option_group_' + g.id + '" data-group-id="' + g.id +
                    '" data-option-id="' + o.id + '" data-option-name="' + o.name +
                    '" data-extra-price="' + o.extra_price + '" id="option_' + o.id + '">' +
                    '<label class="form-check-label" for="option_' + o.id + '">' + o.name +
                    (o.extra_price > 0 ?
                        ' <span class="text-success">+Rp ' + o.extra_price.toLocaleString('id-ID') + '</span>' :
                        '') +
                    '</label></div>';
            }).join('');
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('option-choice')) {
                updateSelectedOptions();
                updateModalPrice();
            }
        });
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('option-text')) {
                updateSelectedOptions();
                updateModalPrice();
            }
        });

        function updateSelectedOptions() {
            selectedOptions = {};
            document.querySelectorAll('.option-choice:checked').forEach(function(i) {
                var g = i.dataset.groupId;
                if (!selectedOptions[g]) selectedOptions[g] = [];
                selectedOptions[g].push({
                    option_id: i.dataset.optionId
                });
            });
            document.querySelectorAll('.option-text').forEach(function(t) {
                var v = t.value.trim();
                if (v) selectedOptions[t.dataset.groupId] = [{
                    custom_value: v
                }];
            });
        }

        function updateModalPrice() {
            var base = parseFloat(document.getElementById('selectedMenuPrice').value),
                extra = 0;
            document.querySelectorAll('.option-choice:checked').forEach(function(i) {
                extra += parseFloat(i.dataset.extraPrice) || 0;
            });
            document.getElementById('modalTotalPrice').textContent =
                'Rp ' + (base + extra).toLocaleString('id-ID');
        }

        document.getElementById('addToCartBtn').addEventListener('click', function() {
            var menuId = document.getElementById('selectedMenuId').value;
            var base = parseFloat(document.getElementById('selectedMenuPrice').value);
            updateSelectedOptions();
            var missing = currentMenuData.option_groups.filter(function(g) {
                return g.pivot.is_required && !selectedOptions[g.id]?.length;
            });
            if (missing.length) {
                alert('Harap pilih: ' + missing.map(function(g) {
                    return g.name;
                }).join(', '));
                return;
            }
            var extra = 0;
            document.querySelectorAll('.option-choice:checked').forEach(function(i) {
                extra += parseFloat(i.dataset.extraPrice) || 0;
            });
            if (!cart[menuId]) cart[menuId] = {
                items: []
            };
            cart[menuId].items.push({
                qty: modalQty,
                price: base + extra,
                base_price: base,
                options: JSON.parse(JSON.stringify(selectedOptions))
            });
            saveMenuMeta(menuId, currentMenuData.name, currentMenuData.price, currentMenuData.option_groups);
            saveCartToStorage();
            updateMenuDisplay(menuId);
            updateCart();
            optionsModal.hide();
            showToast('Menu berhasil ditambahkan!');
        });

        function showToast(msg, type) {
            type = type || 'success';
            var t = document.createElement('div');
            t.className = 'position-fixed top-0 end-0 m-3 alert alert-' + type + ' d-flex align-items-center gap-2';
            t.style.cssText = 'z-index:9999;min-width:220px;';
            t.innerHTML = '<i class="mdi mdi-' +
                (type === 'success' ? 'check-circle' : 'delete-sweep') +
                '"></i><span>' + msg + '</span>';
            document.body.appendChild(t);
            setTimeout(function() {
                t.style.opacity = '0';
                t.style.transition = 'opacity 0.3s';
                setTimeout(function() {
                    t.remove();
                }, 300);
            }, 2500);
        }

        document.getElementById('submitOrderBtn').addEventListener('click', function() {
            if (!Object.keys(cart).length) return;
            saveCartToStorage();
            window.location.href = CART_ROUTE + '?table=' + tableParam;
        });

        // ── SEARCH & PAGINATION ──
        let nextCursor = @json($data->nextCursor()?->encode()),
            loading = false;
        let searchQuery = '{{ request('search') }}',
            activeCategory = '{{ request('category_id') }}';
        const trigger = document.getElementById('load-more-trigger');
        const wrapper = document.getElementById('table-wrapper');
        const loadingEl = document.getElementById('loading');
        const searchInput = document.getElementById('searchInput');
        const categoryTabs = document.querySelectorAll('.category-tab');

        function buildUrl(p) {
            p = p || {};
            var u = new URL(window.location.href);
            u.search = '';
            u.searchParams.set('table', tableParam);
            Object.entries(p).forEach(function([k, v]) {
                if (v) u.searchParams.set(k, v);
            });
            return u.toString();
        }

        async function triggerSearch() {
            if (loading) return;
            loading = true;
            observer.disconnect();
            wrapper.innerHTML = '';
            loadingEl.classList.remove('d-none');
            var r = await fetch(buildUrl({
                search: searchQuery,
                category_id: activeCategory
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            wrapper.innerHTML = await r.text();
            nextCursor = r.headers.get('X-Cursor') || null;
            loading = false;
            loadingEl.classList.add('d-none');
            Object.keys(cart).forEach(function(id) {
                updateMenuDisplay(id);
            });
            if (nextCursor) observer.observe(trigger);
        }

        var dbt;
        searchInput && searchInput.addEventListener('input', function() {
            clearTimeout(dbt);
            searchQuery = this.value;
            dbt = setTimeout(triggerSearch, 400);
        });

        categoryTabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                if (tab.classList.contains('active')) return;
                categoryTabs.forEach(function(t) {
                    t.classList.remove('active');
                });
                tab.classList.add('active');
                activeCategory = tab.dataset.category;
                triggerSearch();
            });
        });

        const observer = new IntersectionObserver(async function(entries) {
            if (!entries[0].isIntersecting || loading || !nextCursor) return;
            loading = true;
            loadingEl.classList.remove('d-none');
            var r = await fetch(buildUrl({
                cursor: nextCursor,
                search: searchQuery,
                category_id: activeCategory
            }), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            wrapper.insertAdjacentHTML('beforeend', await r.text());
            nextCursor = r.headers.get('X-Cursor') || null;
            loading = false;
            loadingEl.classList.add('d-none');
            if (!nextCursor) observer.disconnect();
        }, {
            rootMargin: '200px'
        });

        if (nextCursor) observer.observe(trigger);

        Object.keys(cart).forEach(function(id) {
            updateMenuDisplay(id);
        });
        updateCart();
    </script>
@endpush
