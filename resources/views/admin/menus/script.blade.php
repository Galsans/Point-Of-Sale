<script>
    document.addEventListener('DOMContentLoaded', function() {

        const deleteModalEl = document.getElementById('deleteMenuModal');
        const deleteModal = new bootstrap.Modal(deleteModalEl);

        document.getElementById('table-wrapper').addEventListener('click', function(e) {

            // =====================
            // DETAIL MENU
            // =====================
            if (e.target.closest('.btn-detail-menu')) {
                const btn = e.target.closest('.btn-detail-menu');

                document.getElementById('detailMenuName').innerText = btn.dataset.name;
                document.getElementById('detailMenuCategory').innerText = btn.dataset.category;
                document.getElementById('detailMenuPrice').innerText =
                    Number(btn.dataset.price).toLocaleString('id-ID');
                document.getElementById('detailMenuDescription').innerText = btn.dataset.description ||
                    '';

                const status = btn.dataset.isAvailable;
                const statusEl = document.getElementById('detailMenuStatus');
                statusEl.innerText = status == 1 ? 'Tersedia' : 'Tidak Tersedia';
                statusEl.className = 'badge ' + (status == 1 ? 'bg-success' : 'bg-danger');

                document.getElementById('detailMenuImage').src =
                    btn.dataset.image || '/assets/images/unnamed.jpg';

                new bootstrap.Modal(
                    document.getElementById('detailMenuModal')
                ).show();

                return;
            }

            // =====================
            // EDIT MENU
            // =====================
            if (e.target.closest('.btn-edit-menus')) {
                const btn = e.target.closest('.btn-edit-menus');

                const form = document.getElementById('editMenuForm');
                form.action = `/menus/${btn.dataset.id}`;

                document.getElementById('editMenuName').value = btn.dataset.name || '';
                document.getElementById('editMenuPrice').value = btn.dataset.price || 0;
                document.getElementById('editMenuDescription').value =
                    btn.dataset.description || '';
                document.getElementById('editAvailable1').checked = btn.dataset.isAvailable == 1;
                document.getElementById('editAvailable0').checked = btn.dataset.isAvailable == 0;
                // document.getElementById('editCategoryId').value = btn.dataset.categoryId || '';
                const categoryId = $(this).data('category-id');
                const categoryName = $(this).data('category-name');

                const $select = $('#editCategoryId');

                // 🔴 reset dulu
                $select.val(null).trigger('change');

                // 🔴 inject option lama
                const option = new Option(categoryName, categoryId, true, true);
                $select.append(option).trigger('change');


                const imgPreview = document.getElementById('editMenuImagePreview');
                if (btn.dataset.image) {
                    imgPreview.src = btn.dataset.image;
                    imgPreview.style.display = 'block';
                } else {
                    imgPreview.style.display = 'none';
                }

                new bootstrap.Modal(
                    document.getElementById('editMenuModal')
                ).show();

                return;
            }

            // =====================
            // DELETE MENU
            // =====================
            if (e.target.closest('.btn-delete-category')) {
                const btn = e.target.closest('.btn-delete-category');

                document.getElementById('deleteMenuName').innerText = btn.dataset.name;
                document.getElementById('deleteMenuForm').action = `/menus/${btn.dataset.id}`;

                deleteModal.show();
            }
        });
    });
</script>

<script>
    let nextCursor = @json($data->nextCursor()?->encode());
    let loading = false;
    let searchQuery = '';
    let isAvailable = '';
    let activeCategory = '';

    const trigger = document.getElementById('load-more-trigger');
    const wrapper = document.getElementById('table-wrapper');
    const loadingEl = document.getElementById('loading');
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('#availableFilterGroup button');
    const categoryTabs = document.querySelectorAll('.category-tab');

    // =============================
    // SHOW / HIDE LOADING
    // =============================
    function showLoading(clearData = true) {
        // loadingEl.classList.remove('d-none');
        loading = true;

        observer.disconnect();

        if (clearData) {
            wrapper.innerHTML = ''; // 🔥 HILANGKAN DATA LAMA
        }

        loadingEl.classList.remove('d-none');
    }

    function hideLoading() {
        // loadingEl.classList.add('d-none');
        loading = false;
        loadingEl.classList.add('d-none');
    }

    // =============================
    // FUNCTION UTAMA: TRIGGER SEARCH / FILTER
    // =============================
    async function triggerSearch() {
        if (loading) return;

        // loading = true;
        showLoading(true); // 🔥 clear data + tampilkan loader

        observer.disconnect();

        // 🔥 PAKSA RENDER LOADING DULU
        loadingEl.classList.remove('d-none');

        await new Promise(resolve => requestAnimationFrame(resolve));

        nextCursor = null;

        const response = await fetch(
            `?search=${encodeURIComponent(searchQuery)}
     &is_available=${isAvailable}
     &category_id=${activeCategory}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );


        const html = await response.text();
        wrapper.innerHTML = html;

        nextCursor = response.headers.get('X-Cursor') ?? null;

        hideLoading();

        // loading = false;
        // loadingEl.classList.add('d-none');

        if (nextCursor) observer.observe(trigger);
    }


    // =============================
    // SEARCH INPUT (DEBOUNCE)
    // =============================
    let debounceTimer;
    searchInput?.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        searchQuery = this.value;

        debounceTimer = setTimeout(() => {
            triggerSearch();
        }, 400);
    });

    // =============================
    // BUTTON FILTER is_available
    // =============================
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('active')) return;

            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            isAvailable = btn.dataset.value;
            triggerSearch();
        });
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


    // =============================
    // INTERSECTION OBSERVER (INFINITE SCROLL)
    // =============================
    const observer = new IntersectionObserver(async (entries) => {
        if (!entries[0].isIntersecting || loading || !nextCursor) return;

        // loading = true;
        // showLoading();
        loading = true;
        loadingEl.classList.remove('d-none');

        const response = await fetch(
            `?cursor=${nextCursor}
     &search=${encodeURIComponent(searchQuery)}
     &is_available=${isAvailable}
     &category_id=${activeCategory}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        );


        const html = await response.text();
        wrapper.insertAdjacentHTML('beforeend', html);

        nextCursor = response.headers.get('X-Cursor') ?? null;

        // loading = false;
        // hideLoading();
        loading = false;
        loadingEl.classList.add('d-none');

        if (!nextCursor) observer.disconnect();
    }, {
        rootMargin: '200px'
    });

    observer.observe(trigger);
</script>
