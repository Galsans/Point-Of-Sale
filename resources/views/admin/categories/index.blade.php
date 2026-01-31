@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-shape-outline"></i>
            </span>
            Categories (Kategori Menu)
        </h3>

        <a href="{{ route('categories.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Category
        </a>
    </div>

    <div class="row" id="table-wrapper">
        @include('admin.categories._item', ['categories' => $data])
    </div>
    {{-- LOADING --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.btn-edit-category').forEach(button => {
                button.addEventListener('click', function() {

                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    // set value input
                    document.getElementById('editCategoryName').value = name;

                    // set action form
                    document.getElementById('editCategoryForm').action =
                        `/categories/${id}`;

                });
            });

            document.querySelectorAll('.btn-delete-category').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('deleteCategoryName').innerText = this.dataset.name;
                    document.getElementById('deleteCategoryForm').action =
                        `/categories/${this.dataset.id}`;
                });
            });

        });
    </script>

    <script>
        let nextCursor = @json($data->nextCursor()?->encode());
        let loading = false;
        let floor = '';

        const trigger = document.getElementById('load-more-trigger');
        const wrapper = document.getElementById('table-wrapper');
        const loadingEl = document.getElementById('loading');

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

            let url = `?floor=${floor}`;
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

                floor = btn.dataset.value; // ✅ FIXED
                loadData(true); // reset data + cursor
            });
        });
    </script>

@endsection
