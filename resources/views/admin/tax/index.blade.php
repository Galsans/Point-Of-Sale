@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-food"></i>
            </span>
            Tax
        </h3>

        {{-- <a href="{{ route('taxes.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Taxes
        </a> --}}
    </div>

    <div class="row" id="table-wrapper">
        {{-- @include('admin.tax._item', ['taxes' => $data]) --}}
        @if ($tax)
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5 grid-margin stretch-card">
                    <div class="card shadow-sm position-relative">

                        {{-- ACTION DROPDOWN --}}
                        {{-- <div class="dropdown position-absolute top-0 end-0 m-3"> --}}
                        {{-- <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button> --}}

                        {{-- <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <button class="dropdown-item btn-edit-tax" data-id="{{ $tax->id }}"
                                        data-ppn="{{ $tax->ppn }}" data-service_fee="{{ $tax->service_fee }}"
                                        data-bs-toggle="modal" data-bs-target="#editTaxModal">
                                        <i class="mdi mdi-pencil me-2"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger btn-delete-tax" data-id="{{ $tax->id }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteTaxModal">
                                        <i class="mdi mdi-delete me-2"></i> Hapus
                                    </button>
                                </li>
                            </ul> --}}
                        {{-- </div> --}}

                        <div class="card-body text-center py-5">

                            {{-- ICON --}}
                            <div class="mb-3">
                                <i class="mdi mdi-cash-percent mdi-48px text-primary"></i>
                            </div>

                            {{-- TITLE --}}
                            <h4 class="fw-bold mb-1">Konfigurasi Pajak</h4>
                            <p class="text-muted mb-4">PPN & Service Fee</p>

                            {{-- VALUES --}}
                            <div class="d-flex justify-content-center gap-4 mb-4">

                                <div>
                                    <p class="mb-1 text-muted">PPN</p>
                                    <h3 class="fw-bold text-primary">
                                        {{ $tax->ppn }}%
                                    </h3>
                                </div>

                                <div class="border-start ps-4">
                                    <p class="mb-1 text-muted">Service Fee</p>
                                    <h3 class="fw-bold text-success">
                                        {{ $tax->service_fee }}%
                                    </h3>
                                </div>

                            </div>

                            {{-- PRIMARY ACTION --}}
                            <button class="btn btn-gradient-primary px-4 btn-edit-tax" data-id="{{ $tax->id }}"
                                data-ppn="{{ $tax->ppn }}" data-service_fee="{{ $tax->service_fee }}"
                                data-bs-toggle="modal" data-bs-target="#editTaxModal">
                                <i class="mdi mdi-pencil me-1"></i>
                                Edit Konfigurasi
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-dashed text-center py-5">
                        <i class="mdi mdi-alert-circle-outline mdi-48px text-warning mb-3"></i>
                        <h5 class="fw-bold">Konfigurasi Pajak Belum Diatur</h5>
                        <p class="text-muted mb-4">Silakan buat konfigurasi PPN & Service Fee</p>

                        <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#createTaxModal">
                            <i class="mdi mdi-plus me-1"></i>
                            Buat Konfigurasi
                        </button>
                    </div>
                </div>
            </div>
        @endif


    </div>
    {{-- LOADING --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.btn-edit-tax').forEach(button => {
                button.addEventListener('click', function() {

                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    // set value input
                    document.getElementById('editTaxName').value = name;

                    // set action form
                    document.getElementById('editTaxForm').action =
                        `/taxes/${id}`;

                });
            });

            document.querySelectorAll('.btn-delete-tax').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('deleteTaxName').innerText = this.dataset.name;
                    document.getElementById('deleteTaxForm').action =
                        `/taxes/${this.dataset.id}`;
                });
            });

        });
    </script>

    {{-- <script>
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
    </script> --}}

@endsection
