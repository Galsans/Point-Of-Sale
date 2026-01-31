@forelse ($data as $option)
    <div class="col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card shadow-sm h-100 position-relative p-3 hover-shadow">

            {{-- DROPDOWN ACTION --}}
            <div class="dropdown position-absolute top-0 end-0 m-2">
                <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                    <i class="mdi mdi-dots-vertical"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li>
                        <button class="dropdown-item text-dark btn-edit-option" data-id="{{ $option->id }}"
                            data-name="{{ $option->name }}" data-extra-price="{{ $option->extra_price }}"
                            data-option-group-id="{{ $option->option_group_id }}"
                            data-is-active="{{ $option->is_active }}" data-bs-toggle="modal"
                            data-bs-target="#editOptionModal">
                            <i class="mdi mdi-pencil me-2"></i> Edit
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item text-danger btn-delete-option" data-id="{{ $option->id }}"
                            data-name="{{ $option->name }}" data-bs-toggle="modal" data-bs-target="#deleteOptionModal">
                            <i class="mdi mdi-delete me-2"></i> Hapus
                        </button>
                    </li>
                </ul>
            </div>

            <div class="text-center mt-2">

                {{-- ICON --}}
                <i class="mdi mdi-checkbox-marked-circle-outline mdi-36px text-primary mb-2"></i>

                {{-- OPTION NAME --}}
                <h5 class="fw-bold mb-1 text-truncate" title="{{ $option->name }}">
                    {{ $option->name }}
                </h5>

                {{-- INFORMASI INLINE --}}
                <div class="text-muted small">
                    <div><i class="mdi mdi-tune-vertical"></i> {{ $option->optionGroup->name ?? 'Tanpa Group' }}</div>
                    @if ($option->extra_price > 0)
                        <div><i class="mdi mdi-cash-multiple"></i> +Rp
                            {{ number_format($option->extra_price, 0, ',', '.') }}</div>
                    @endif
                    <div>
                        <i class="mdi {{ $option->is_active ? 'mdi-check-circle' : 'mdi-cancel' }}"></i>
                        {{ $option->is_active ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }
    </style>


    {{-- MODAL FADE EDIT --}}
    <div class="modal fade" id="editOptionModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-gradient-secondary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-pencil"></i> Edit Option Group
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <form id="editOptionForm" method="POST">
                                @csrf
                                @method('PUT')
                                {{-- OPTION GROUP --}}
                                <div class="form-group mb-3">
                                    <label for="option_group_id">
                                        Option Group <span class="text-danger">*</span>
                                    </label>

                                    <select name="option_group_id" id="editOptionGroupId"
                                        class="form-control text-dark @error('option_group_id') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Option Group --</option>
                                        @foreach ($optionGroups as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('option_group_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('option_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- OPTION NAME --}}
                                <div class="form-group mb-3">
                                    <label for="name">
                                        Nama Option <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="name" id="editOptionName" class="form-control"
                                        required>

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- EXTRA PRICE --}}
                                <div class="form-group mb-3">
                                    <label for="extra_price">
                                        Tambahan Harga
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="extra_price" id="editOptionPrice"
                                            class="form-control @error('extra_price') is-invalid @enderror"
                                            value="{{ old('extra_price', 0) }}" min="0" step="1000">
                                    </div>

                                    @error('extra_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- STATUS --}}
                                <div class="form-group mb-4">
                                    <label for="is_active">Status</label>

                                    <select name="is_active" id="editActive"
                                        class="form-control text-dark @error('is_active') is-invalid @enderror">
                                        <option value="1" id="editActive1"
                                            {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="0" id="editActive0"
                                            {{ old('is_active') == 0 ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>

                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- BUTTON --}}
                                <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-secondary text-dark">Update</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="mdi mdi-information-outline"></i>
                                        Informasi
                                    </h5>

                                    <ul class="mb-0 small">
                                        <li>
                                            Option Group digunakan untuk mengelompokkan pilihan
                                            (contoh: <strong>Topping</strong>, <strong>Level Pedas</strong>)
                                            .
                                        </li>
                                        <li>
                                            Nama option harus <strong>unik dalam satu option group</strong>.
                                        </li>
                                        <li>
                                            Tambahan harga akan otomatis ditambahkan ke harga menu.
                                        </li>
                                        <li>
                                            Jika option tidak digunakan sementara, ubah status menjadi
                                            <strong>Nonaktif</strong>.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- MODAL FADE DELETE --}}
    <div class="modal fade" id="deleteOptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-alert"></i> Hapus Option
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="deleteOptionForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body text-center">
                        <p>Yakin ingin menghapus option:</p>
                        <h5 id="deleteOptionName" class="text-danger"></h5>
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
                <h5 class="text-muted">Belum ada option</h5>
                <p class="text-muted mb-3">
                    Tambahkan option agar produk bisa dikelompokkan
                </p>
                <a href="{{ route('options.create') }}" class="btn btn-gradient-primary btn-sm">
                    + Tambah Option
                </a>
            </div>
        </div>
    </div>
@endforelse
