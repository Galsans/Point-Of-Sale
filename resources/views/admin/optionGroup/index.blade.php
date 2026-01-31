@extends('layouts.app')

@section('title', 'Option Group Management')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-tune-vertical"></i>
            </span>
            Option Groups (Pilihan Group Menu)
        </h3>

        <a href="{{ route('option-groups.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Option Group
        </a>
    </div>

    <div class="row">
        @forelse ($data as $optionGroup)
            <div class="col-md-3 col-sm-6 grid-margin stretch-card">
                <div class="card shadow-sm position-relative h-100">

                    {{-- DROPDOWN ACTION --}}
                    <div class="dropdown position-absolute top-0 end-0 m-2">
                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                            <i class="mdi mdi-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <button class="dropdown-item btn-edit-option-group text-dark"
                                    data-id="{{ $optionGroup->id }}" data-name="{{ $optionGroup->name }}"
                                    data-type="{{ $optionGroup->type }}" data-description="{{ $optionGroup->description }}"
                                    data-bs-toggle="modal" data-bs-target="#editOptionGroupModal">
                                    <i class="mdi mdi-pencil me-2"></i> Edit Group
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger btn-delete-option-group"
                                    data-id="{{ $optionGroup->id }}" data-name="{{ $optionGroup->name }}"
                                    data-bs-toggle="modal" data-bs-target="#deleteOptionGroupModal">
                                    <i class="mdi mdi-delete me-2"></i> Hapus Group
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body text-center">

                        {{-- ICON --}}
                        <i class="mdi mdi-tune-vertical mdi-40px text-primary mb-2"></i>

                        {{-- GROUP NAME --}}
                        <h5 class="fw-bold mb-1">
                            {{ $optionGroup->name }}
                        </h5>

                        {{-- TYPE BADGE --}}
                        <span
                            class="badge
                {{ $optionGroup->type === 'single'
                    ? 'bg-info'
                    : ($optionGroup->type === 'multiple'
                        ? 'bg-warning'
                        : 'bg-secondary') }}">
                            {{ strtoupper($optionGroup->type) }}
                        </span>

                        {{-- DESCRIPTION --}}
                        <p class="text-muted small mt-2 mb-3">
                            Pilihan yang muncul saat customer memesan menu
                        </p>

                        {{-- PRIMARY ACTION --}}
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#optionModal"
                            data-id="{{ $optionGroup->id }}" data-name="{{ $optionGroup->name }}">
                            <i class="mdi mdi-eye me-1"></i> Lihat Options
                        </button>

                        {{-- <a href="{{ route('options.index', ['group' => $optionGroup->id]) }}"
                            class="btn btn-sm btn-outline-primary px-4">
                            <i class="mdi mdi-format-list-bulleted"></i>

                        </a> --}}

                    </div>
                </div>
            </div>

            {{-- MODAL FADE LIHAT OPTION --}}
            <div class="modal fade" id="optionModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        {{-- HEADER --}}
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Opsi untuk: <span id="optionGroupName"></span>
                            </h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        {{-- BODY --}}
                        <div class="modal-body">
                            <div id="optionList" class="row g-3">
                                {{-- options injected by JS --}}
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>
            </div>


            {{-- MODAL FADE EDIT --}}
            @include('admin.optionGroup.edit')


            {{-- MODAL FADE DELETE --}}
            <div class="modal fade" id="deleteOptionGroupModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="mdi mdi-alert"></i> Hapus Option Group
                            </h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="deleteOptionGroupForm" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-body text-center">
                                <p>Yakin ingin menghapus option group:</p>
                                <h5 id="deleteOptionGroupName" class="text-danger"></h5>
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
        @empty
            {{-- DATA KOSONG --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-food-off mdi-48px text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada option group</h5>
                        <p class="text-muted mb-3">
                            Tambahkan option group agar produk bisa dikelompokkan
                        </p>
                        <a href="{{ route('option-groups.create') }}" class="btn btn-gradient-primary btn-sm">
                            + Tambah Option Group
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @include('admin.optionGroup.script')

@endsection
