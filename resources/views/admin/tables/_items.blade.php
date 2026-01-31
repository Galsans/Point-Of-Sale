@forelse ($data as $table)
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 grid-margin stretch-card">

        <div
            class="card h-100 {{ $table->status === 'occupied' ? 'bg-gradient-danger text-white' : 'bg-gradient-success text-white' }}">

            <div class="card-body position-relative text-center">

                {{-- DROPDOWN ACTION --}}
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                        <i class="mdi mdi-dots-vertical"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <button class="dropdown-item text-dark" data-bs-toggle="modal"
                                data-bs-target="#editTableModal{{ $table->id }}">
                                <i class="mdi mdi-pencil me-2"></i> Edit
                            </button>
                        </li>

                        <li>
                            <button class="dropdown-item text-danger btn-delete-table" data-id="{{ $table->id }}"
                                data-kode="{{ $table->kode_table }}" data-bs-toggle="modal"
                                data-bs-target="#deleteTableModal" {{ $table->status === 'occupied' ? 'hidden' : '' }}>
                                <i class="mdi mdi-delete me-2"></i> Hapus
                            </button>

                        </li>

                        @if ($table->status === 'occupied')
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="mdi mdi-receipt me-2"></i> Lihat Pesanan
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
                {{-- FLOOR INFO --}}
                <span class="badge bg-dark position-absolute top-0 start-0 m-2">
                    <i class="mdi mdi-office-building-outline me-1"></i>
                    Lantai {{ $table->floor }}
                </span>

                {{-- ICON --}}
                {{-- <i class="mdi mdi-table-furniture mdi-48px mb-3"></i> --}}
                <img src="{{ $table->qr_code ? asset('storage/' . $table->qr_code) : asset('assets/images/unnamed.jpg') }}"
                    class="card-img-top rounded mt-2 mb-2" style="height:auto; object-fit:contain;"
                    alt="{{ $table->kode_table }}">


                {{-- TITLE --}}
                <h4 class="mb-1">Meja {{ $table->kode_table }}</h4>


                {{-- <small class="d-block mb-2 opacity-75">
                    <i class="mdi mdi-office-building-outline me-1"></i>
                    Lantai {{ $table->floor }}
                </small> --}}



                {{-- STATUS BADGE --}}
                <span
                    class="badge rounded-pill px-3 py-2
                {{ $table->status === 'occupied' ? 'bg-light text-danger' : 'bg-light text-success' }}">
                    {{ $table->status === 'occupied' ? 'Terisi' : 'Kosong' }}
                </span>

            </div>
        </div>
    </div>

    {{-- MODAL FADE EDIT --}}
    <div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header bg-gradient-secondary text-white">
                    <h5 class="modal-title text-dark">
                        <i class="mdi mdi-pencil"></i>
                        Edit Meja {{ $table->kode_table }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    <div class="row">

                        {{-- FORM --}}
                        <div class="col-md-6">
                            <form action="{{ route('tables.update', $table->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- KODE MEJA --}}
                                <div class="form-group mb-3">
                                    <label>Kode Meja</label>
                                    <input type="text" name="kode_table" class="form-control"
                                        value="{{ old('kode_table', $table->kode_table) }}" required>
                                </div>

                                {{-- LANTAI --}}
                                <div class="form-group mb-4">
                                    <label>Lantai Tingkat Meja</label>
                                    <select name="floor" class="form-control">
                                        <option value="1" {{ $table->floor === 1 ? 'selected' : '' }}>
                                            Lantai 1
                                        </option>
                                        <option value="2" {{ $table->floor === 2 ? 'selected' : '' }}>
                                            Lantai 2
                                        </option>
                                        <option value="3" {{ $table->floor === 3 ? 'selected' : '' }}>
                                            Lantai 3
                                        </option>
                                    </select>
                                </div>

                                {{-- STATUS --}}
                                <div class="form-group mb-4">
                                    <label>Status Meja</label>
                                    <select name="status" class="form-control">
                                        <option value="available"
                                            {{ $table->status === 'available' ? 'selected' : '' }}>
                                            Kosong
                                        </option>
                                        <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>
                                            Terisi
                                        </option>
                                    </select>
                                </div>

                                {{-- STATUS --}}
                                <div class="form-group mb-4">
                                    <label>Status Meja</label>
                                    <select name="status" class="form-control">
                                        <option value="available"
                                            {{ $table->status === 'available' ? 'selected' : '' }}>
                                            Kosong
                                        </option>
                                        <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>
                                            Terisi
                                        </option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button class="btn btn-secondary text-dark">
                                        Update Meja
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- INFO --}}
                        <div class="col-md-6">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="mdi mdi-information-outline"></i>
                                        Informasi
                                    </h5>
                                    <ul class="mb-0">
                                        <li>Kode meja harus unik</li>
                                        <li>Status <strong>Terisi</strong> menandakan meja sedang digunakan</li>
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
    <div class="modal fade" id="deleteTableModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-alert"></i> Konfirmasi Hapus
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="deleteTableForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body text-center">
                        <p class="mb-2">
                            Yakin ingin menghapus meja:
                        </p>
                        <h4 id="deleteTableName" class="text-danger"></h4>

                        <small class="text-muted">
                            Data yang sudah dihapus tidak bisa dikembalikan
                        </small>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Ya, Hapus
                        </button>
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
                <i class="mdi mdi-table-off mdi-48px text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada meja</h5>
                <p class="text-muted mb-3">
                    Silakan tambahkan data meja terlebih dahulu
                </p>
                <a href="{{ route('tables.create') }}" class="btn btn-gradient-primary btn-sm">
                    + Tambah Meja
                </a>
            </div>
        </div>
    </div>
@endforelse

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-delete-table').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const kode = this.dataset.kode;

                document.getElementById('deleteTableName').innerText = `Meja ${kode}`;
                document.getElementById('deleteTableForm').action =
                    `/tables/${id}`;
            });
        });
    });
</script>
