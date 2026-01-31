@extends('layouts.app')

@section('title', 'Menus Management')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .category-tabs {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: none;
            /* Firefox */
        }

        .category-tabs::-webkit-scrollbar {
            display: none;
            /* Chrome */
        }

        .category-tab {
            border: none;
            background: #f3f4f6;
            color: #555;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.25s ease;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .category-tab:hover {
            background: #e0e7ff;
            color: #1e40af;
            transform: translateY(-1px);
        }

        /* ACTIVE TAB */
        .category-tab.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        /* MOBILE FRIENDLY */
        @media (max-width: 576px) {
            .category-tab {
                font-size: 0.8rem;
                padding: 7px 14px;
            }
        }

        /* Custom styling untuk select2 */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-food"></i>
            </span>
            Menus
        </h3>

        <a href="{{ route('menus.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Menu
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-2 mb-md-0">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari menu atau kategori..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-6 d-flex justify-content-md-end justify-content-start align-items-center">
            <div class="btn-group flex-wrap" role="group" aria-label="Filter Status" id="availableFilterGroup">
                <button type="button" class="btn btn-outline-primary active mb-1 me-1" data-value="">Semua</button>
                <button type="button" class="btn btn-outline-primary mb-1 me-1" data-value="1">Tersedia</button>
                <button type="button" class="btn btn-outline-primary mb-1" data-value="0">Tidak Tersedia</button>
            </div>
        </div>
    </div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <ul class="nav category-tabs mb-4" id="categoryTabs">
        <li class="nav-item">
            <button class="category-tab active" data-category="">
                Semua
            </button>
        </li>

        @foreach ($categories as $category)
            <li class="nav-item">
                <button class="category-tab" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
            </li>
        @endforeach
    </ul>

    {{-- LOADING --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div class="row" id="table-wrapper">
        @include('admin.menus._items', ['data' => $data])
    </div>


    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    <!-- DETAIL MENU MODAL -->
    @include('admin.menus.detail')


    <!-- EDIT MENU MODAL -->
    @include('admin.menus.edit')


    <!-- DELETE MENU MODAL -->
    <div class="modal fade" id="deleteMenuModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="mdi mdi-alert"></i> Hapus Menu</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteMenuForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <p>Yakin ingin menghapus menu:</p>
                        <h5 id="deleteMenuName" class="text-danger"></h5>
                        <small class="text-muted">Data tidak bisa dikembalikan</small>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.menus.script')
@endsection
