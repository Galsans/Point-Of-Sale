@forelse ($data as $category)
    <div class="col-md-3 col-sm-6 grid-margin stretch-card">
        <div class="card shadow-sm position-relative">
            <div class="card-body text-center">

                {{-- DROPDOWN ACTION --}}
                <div class="dropdown position-absolute top-0 end-0 m-2">
                    <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                        <i class="mdi mdi-dots-vertical"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <button class="dropdown-item btn-edit-category text-dark" data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal">
                                <i class="mdi mdi-pencil me-2"></i> Edit
                            </button>

                        </li>
                        <li>
                            <button class="dropdown-item text-danger btn-delete-category" data-id="{{ $category->id }}"
                                data-name="{{ $category->name }}" data-bs-toggle="modal"
                                data-bs-target="#deleteCategoryModal">
                                <i class="mdi mdi-delete me-2"></i> Hapus
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- ICON --}}
                <i class="mdi mdi-silverware-fork-knife mdi-36px text-primary mb-3"></i>

                {{-- TITLE --}}
                <h5 class="fw-bold mb-3">
                    {{ $category->name }}
                </h5>

                {{-- PRIMARY ACTION --}}
                {{-- <a href="#" class="btn btn-sm btn-gradient-primary px-4">
                    Menu
                </a> --}}

            </div>
        </div>
    </div>


    {{-- MODAL FADE EDIT --}}
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-gradient-secondary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-pencil"></i> Edit Kategori
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <form id="editCategoryForm" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label>Nama Kategori</label>
                                    <input type="text" name="name" id="editCategoryName" class="form-control"
                                        required>
                                </div>

                                <div class="d-flex flex-wrap gap-2 justify-content-end">
                                    <button class="btn btn-secondary w-100 w-md-auto">
                                        Update
                                    </button>

                                    <button type="button" class="btn btn-light w-100 w-md-auto"
                                        data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <h6><i class="mdi mdi-information-outline"></i> Informasi</h6>
                                    <ul class="mb-0">
                                        <li>Nama kategori harus unik</li>
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
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-alert"></i> Hapus Kategori
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="deleteCategoryForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body text-center">
                        <p>Yakin ingin menghapus kategori:</p>
                        <h5 id="deleteCategoryName" class="text-danger"></h5>
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
                <h5 class="text-muted">Belum ada kategori</h5>
                <p class="text-muted mb-3">
                    Tambahkan kategori menu agar produk bisa dikelompokkan
                </p>
                <a href="{{ route('categories.create') }}" class="btn btn-gradient-primary btn-sm">
                    + Tambah Kategori
                </a>
            </div>
        </div>
    </div>
@endforelse
