@extends('layouts.app')

@section('title', 'Tables Management')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tables (Meja)
        </h3>

        <a href="{{ route('tables.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Meja
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-md-end justify-content-start align-items-center">
            <div class="btn-group flex-wrap" role="group" aria-label="Filter Status" id="availableFilterGroup">
                <button type="button" class="btn btn-outline-primary active mb-1 me-1" data-value="">Semua</button>
                <button type="button" class="btn btn-outline-primary mb-1 me-1" data-value="1">Lantai 1</button>
                <button type="button" class="btn btn-outline-primary mb-1 me-1" data-value="2">Lantai 2</button>
                <button type="button" class="btn btn-outline-primary mb-1" data-value="3">Lantai 3</button>
            </div>
        </div>
    </div>

    <div class="row" id="table-wrapper">
        @include('admin.tables._items', ['tables' => $data])
    </div>

    {{-- LOADING --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    <script>
        let nextCursor = @json($data->nextCursor()?->encode());
        let loading = false;
        let floor = '';

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
