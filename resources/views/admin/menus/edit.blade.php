<div class="modal fade" id="editMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-gradient-secondary text-white">
                <h5 class="modal-title"><i class="mdi mdi-pencil"></i> Edit Menu</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-3">
                <div class="row g-3">
                    {{-- FORM --}}
                    <div class="col-md-6">
                        <form id="editMenuForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- CATEGORY --}}
                            <div class="mb-3">
                                <label for="editCategoryId">Kategori Menu</label> <br>
                                {{-- <select name="category_id" id="editCategoryId" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select> --}}
                                <select name="category_id" id="editCategoryId" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                </select>

                            </div>

                            {{-- NAME --}}
                            <div class="mb-3">
                                <label>Nama Menu</label>
                                <input type="text" name="name" id="editMenuName" class="form-control" required>
                            </div>

                            {{-- PRICE --}}
                            <div class="mb-3">
                                <label>Harga</label>
                                <input type="number" name="price" id="editMenuPrice" class="form-control"
                                    min="0" required>
                            </div>

                            {{-- IMAGE --}}
                            <div class="mb-3">
                                <label>Gambar</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                <div class="mt-2">
                                    <img id="editMenuImagePreview" src="" alt="Preview"
                                        style="height:100px; object-fit:cover; display:none;">
                                </div>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="mb-3">
                                <label>Deskripsi Menu</label>
                                <textarea name="description" id="editMenuDescription" class="form-control" required>{{ old('description') }}</textarea>
                            </div>

                            {{-- STATUS --}}
                            <div class="mb-3">
                                <label class="d-block mb-2">Status Menu</label>
                                <div class="form-check mb-2">
                                    <input type="radio" name="is_available" id="editAvailable1" value="1">
                                    <label for="editAvailable1">Tersedia</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="radio" name="is_available" id="editAvailable0" value="0">
                                    <label for="editAvailable0">Tidak Tersedia</label>
                                </div>
                            </div>


                            {{-- BUTTON --}}
                            <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-secondary text-dark">Update</button>
                            </div>
                        </form>
                    </div>

                    {{-- INFORMASI TAMBAHAN --}}
                    <div class="col-md-6">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h6><i class="mdi mdi-information-outline"></i> Informasi</h6>
                                <ul class="mb-0 mt-2">
                                    <li>Nama menu harus unik</li>
                                    <li>Harga harus diisi dan minimal 0</li>
                                    <li>Jika tidak ingin mengganti gambar, biarkan kosong</li>
                                    <li>Status "Tersedia" berarti menu siap dijual, "Tidak Tersedia" berarti stok habis
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- END ROW -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).on('shown.bs.modal', '#editMenuModal', function() {

            const $select = $('#editCategoryId');

            if ($select.hasClass('select2-hidden-accessible')) return;

            $select.select2({
                placeholder: 'Cari kategori...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editMenuModal'),
                ajax: {
                    url: '/api/categories/search',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term || '',
                        page: params.page || 1
                    }),
                    processResults: (res, params) => ({
                        results: res.data.map(i => ({
                            id: i.id,
                            text: i.name
                        })),
                        pagination: {
                            more: res.pagination.more
                        }
                    })
                }
            });

        });
    </script>
@endpush
