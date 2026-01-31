@extends('layouts.app')

@section('title', 'Detail Menu dan Konfigurasi Menu')
@section('content')
    <style>
        .fab-plus {
            position: fixed;
            bottom: 30px;
            right: 30px;

            width: 56px;
            height: 56px;

            border-radius: 50%;
            /* kunci bulat sempurna */
            background-color: #4f46e5;
            /* indigo / bisa kamu ganti */

            display: flex;
            align-items: center;
            justify-content: center;

            color: #ffffff;
            text-decoration: none;

            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            z-index: 1050;

            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .fab-plus i {
            font-size: 28px;
            line-height: 1;
            /* biar icon benar-benar center */
        }

        .fab-plus:hover {
            transform: scale(1.08);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.35);
        }

        .fab-plus:active {
            transform: scale(0.95);
        }

        .modal .checkmark,
        .modal .custom-radio .checkmark,
        .modal .form-check .checkmark {
            position: absolute !important;
            left: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
    </style>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('assets/images/unnamed.jpg') }}"
                    class="card-img-top" style="object-fit: cover; height: 220px;">
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">

                    <h3 class="fw-bold mb-1">{{ $menu->name }}</h3>

                    <h5 class="text-primary mb-2">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </h5>

                    <span class="badge {{ $menu->is_available ? 'bg-success' : 'bg-danger' }}">
                        {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>

                    <hr>

                    <p class="text-muted mb-0">
                        {{ $menu->description ?? 'Tidak ada deskripsi' }}
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="mdi mdi-tune-vertical me-1"></i>
                Konfigurasi Pilihan Menu
            </h5>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle-outline me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card-body">

            @forelse ($menu->menuOptionGroups as $mog)
                <div class="border rounded p-3 mb-3">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="mb-2">{{ $mog->optionGroup->name }}</strong>
                            <div class="small text-muted">
                                {{ $mog->is_required ? 'Wajib' : 'Opsional' }}
                                · Min: {{ $mog->min_choice ?? '-' }}
                                · Max: {{ $mog->max_choice ?? '-' }}
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                <i class="mdi mdi-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item text-dark btn-edit-config" data-bs-toggle="modal"
                                        data-bs-target="#editMenuOptionGroupModal" data-id="{{ $mog->id }}"
                                        data-option-group-id="{{ $mog->option_group_id }}"
                                        data-is-required="{{ $mog->is_required }}" data-min="{{ $mog->min_choice }}"
                                        data-max="{{ $mog->max_choice }}" data-sort="{{ $mog->sort_order }}">
                                        <i class="mdi mdi-pencil me-2"></i> Edit
                                    </button>

                                </li>
                                <li>
                                    <button class="dropdown-item text-danger btn-delete-category"
                                        data-id="{{ $mog->id }}" data-name="{{ $mog->optionGroup->name }}">
                                        <i class="mdi mdi-delete me-2"></i> Hapus
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- OPTIONS --}}
                    <div class="mt-2">
                        @foreach ($mog->optionGroup->options as $option)
                            <span class="badge bg-light text-dark me-1 mb-1">
                                {{ $option->name }}
                                @if ($option->extra_price)
                                    (+Rp {{ number_format($option->extra_price, 0, ',', '.') }})
                                @endif
                            </span>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="mdi mdi-information-outline mdi-36px mb-2"></i>
                    <p>Belum ada konfigurasi pilihan untuk menu ini</p>
                </div>
            @endforelse

        </div>
    </div>

    <a href="javascript:void(0)" class="fab-plus" data-bs-toggle="modal" data-bs-target="#createMenuOptionGroupModal"
        title="Tambah Konfigurasi">
        <i class="mdi mdi-plus"></i>
    </a>

    {{-- MODAL CREATE FADE --}}
    <div class="modal fade" id="createMenuOptionGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="mdi mdi-tune-vertical me-2"></i>
                        Tambah Konfigurasi Option Group
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- FORM -->
                <form action="{{ route('menu-option-groups.store') }}" method="POST">
                    @csrf

                    <!-- menu_id (hidden, dari URL / detail menu) -->
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                    <div class="modal-body">

                        <!-- OPTION GROUP -->
                        <div class="mb-3">
                            <label class="form-label">Option Group</label>
                            <select name="option_group_id" class="form-select" required>
                                <option value="">-- Pilih Option Group --</option>
                                @foreach ($optionGroups as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- IS REQUIRED -->
                        <div class="mb-3">
                            <label class="form-label">Wajib Dipilih?</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input type="radio" name="is_required" value="1" id="requiredYes" checked>
                                    <label for="requiredYes">
                                        Ya
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input type="radio" name="is_required" value="0" id="requiredNo">
                                    <label for="requiredNo">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- MIN & MAX CHOICE -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Minimal Pilihan</label>
                                <input type="number" name="min_choice" class="form-control" min="0"
                                    placeholder="Contoh: 1">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Maksimal Pilihan</label>
                                <input type="number" name="max_choice" class="form-control" min="0"
                                    placeholder="Contoh: 3">
                            </div>
                        </div>

                        <!-- SORT ORDER -->
                        {{-- <div class="mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="form-control" min="1" value="1"
                                required>
                        </div> --}}

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- MODAL EDIT FADE --}}
    <div class="modal fade" id="editMenuOptionGroupModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-pencil"></i> Edit Konfigurasi Option Group
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="editConfigForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">

                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                        {{-- OPTION GROUP --}}
                        <div class="mb-3">
                            <label class="form-label">Option Group</label>
                            <select name="option_group_id" id="editOptionGroup" class="form-select textdark" required>
                                @foreach ($optionGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- REQUIRED --}}
                        <div class="mb-3">
                            <label class="form-label">Wajib Dipilih?</label>
                            <div class="d-flex gap-4">
                                <label class="form-check">
                                    <input type="radio" name="is_required" value="1" id="editRequiredYes">
                                    Ya
                                </label>
                                <label class="form-check">
                                    <input type="radio" name="is_required" value="0" id="editRequiredNo">
                                    Tidak
                                </label>
                            </div>
                        </div>

                        {{-- MIN MAX --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Minimal Pilihan</label>
                                <input type="number" name="min_choice" id="editMin" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Maksimal Pilihan</label>
                                <input type="number" name="max_choice" id="editMax" class="form-control">
                            </div>
                        </div>

                        {{-- SORT --}}
                        <div class="mb-3">
                            <label>Urutan Tampil</label>
                            <input type="number" name="sort_order" id="editSort" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- MODAL DELETE FADE -->
    <div class="modal fade" id="deleteConfigModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="mdi mdi-alert"></i> Hapus Menu</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteConfigForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <p>Yakin ingin menghapus config:</p>
                        <h5 id="deleteConfigName" class="text-danger"></h5>
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

    <script>
        document.addEventListener('click', function(e) {

            // =====================
            // EDIT CONFIG
            // =====================
            const editBtn = e.target.closest('.btn-edit-config');
            if (editBtn) {
                document.getElementById('editConfigForm').action =
                    `/menu-option-groups/${editBtn.dataset.id}`;

                document.getElementById('editOptionGroup').value =
                    editBtn.dataset.optionGroupId;

                document.getElementById('editMin').value =
                    editBtn.dataset.min || '';

                document.getElementById('editMax').value =
                    editBtn.dataset.max || '';

                document.getElementById('editSort').value =
                    editBtn.dataset.sort || 1;

                document.getElementById('editRequiredYes').checked =
                    editBtn.dataset.isRequired == 1;

                document.getElementById('editRequiredNo').checked =
                    editBtn.dataset.isRequired == 0;
            }

            // =====================
            // DELETE CONFIG
            // =====================
            const deleteBtn = e.target.closest('.btn-delete-category');
            if (deleteBtn) {
                document.getElementById('deleteConfigName').innerText =
                    deleteBtn.dataset.name;

                document.getElementById('deleteConfigForm').action =
                    `/menu-option-groups/${deleteBtn.dataset.id}`;

                new bootstrap.Modal(
                    document.getElementById('deleteConfigModal')
                ).show();
            }

        });
    </script>
@endsection
