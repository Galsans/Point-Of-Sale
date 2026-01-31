<script>
    document.addEventListener('DOMContentLoaded', () => {

        let nextCursor = @json($data->nextCursor()?->encode());
        let loading = false;
        let activeOptionGroup = '';

        const trigger = document.getElementById('load-more-trigger');
        const wrapper = document.getElementById('table-wrapper');
        const loadingEl = document.getElementById('loading');
        const optionGroupTabs = document.querySelectorAll('.optionGroup-tab');

        // =============================
        // LOADING
        // =============================
        function showLoading() {
            loadingEl.classList.remove('d-none');
        }

        function hideLoading() {
            loadingEl.classList.add('d-none');
        }

        // =============================
        // LOAD DATA (SEARCH / FILTER)
        // =============================
        async function triggerSearch() {
            if (loading) return;

            loading = true;
            observer.disconnect();
            showLoading();

            nextCursor = null;
            wrapper.innerHTML = '';

            await new Promise(r => requestAnimationFrame(r));

            const response = await fetch(
                `?option_group_id=${activeOptionGroup}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const html = await response.text();
            wrapper.innerHTML = html;

            nextCursor = response.headers.get('X-Cursor');

            loading = false;
            hideLoading();

            if (nextCursor) observer.observe(trigger);
        }

        // =============================
        // TAB CLICK
        // =============================
        optionGroupTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                if (tab.classList.contains('active')) return;

                optionGroupTabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                activeOptionGroup = tab.dataset.optionGroup || '';
                triggerSearch();
            });
        });

        // =============================
        // INFINITE SCROLL
        // =============================
        const observer = new IntersectionObserver(async ([entry]) => {
            if (!entry.isIntersecting || loading) return;
            if (!nextCursor) return;

            loading = true;
            showLoading();

            const response = await fetch(
                `?cursor=${nextCursor}&option_group_id=${activeOptionGroup}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const html = await response.text();
            wrapper.insertAdjacentHTML('beforeend', html);

            nextCursor = response.headers.get('X-Cursor') ?? null;

            loading = false;
            hideLoading();
        }, {
            rootMargin: '200px'
        });


        observer.observe(trigger);

        // =============================
        // EVENT DELEGATION (EDIT / DELETE)
        // =============================
        wrapper.addEventListener('click', function(e) {

            // EDIT
            const editBtn = e.target.closest('.btn-edit-option');
            if (editBtn) {
                // set form action
                document.getElementById('editOptionForm').action = `/options/${editBtn.dataset.id}`;

                // set input values
                document.getElementById('editOptionName').value = editBtn.dataset.name || '';
                document.getElementById('editOptionPrice').value = editBtn.dataset.extraPrice || 0;

                // set select values
                document.getElementById('editOptionGroupId').value = editBtn.dataset.optionGroupId ||
                '';
                document.getElementById('editActive').value = editBtn.dataset.isActive || 1;

                return;
            }


            // DELETE
            const deleteBtn = e.target.closest('.btn-delete-option');
            if (deleteBtn) {
                document.getElementById('deleteOptionName').innerText =
                    deleteBtn.dataset.name;
                document.getElementById('deleteOptionForm').action =
                    `/options/${deleteBtn.dataset.id}`;
            }
        });

    });
</script>
