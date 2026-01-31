<div class="modal fade" id="editOptionGroupModal" tabindex="-1">
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
                        <form id="editOptionGroupForm" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- NAMA OPTION GROUP --}}
                            <div class="form-group mb-3">
                                <label for="name">Nama Option Group</label>
                                <input type="text" name="name" id="editOptionGroupName"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Makanan" value="{{ old('name') }}" required>

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="type">Type Option Group</label>
                                <select name="type" id="editOptionGroupType"
                                    class="form-control text-dark @error('type') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Type Option Group</option>
                                    <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>
                                        Single Select
                                    </option>
                                    <option value="multiple" {{ old('type') == 'multiple' ? 'selected' : '' }}>
                                        Multiple Select
                                    </option>
                                    <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>
                                        Text Input
                                    </option>
                                </select>
                                {{-- <select name="type" id="editOptionGroupType" class="form-control" required>
                                    <option value="" disabled>Pilih Type Option Group</option>
                                    <option value="single">Single Select</option>
                                    <option value="multiple">Multiple Select</option>
                                    <option value="text">Text Input</option>
                                </select> --}}


                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="form-group mb-3">
                                <label for="name">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                    id="editOptionGroupDescription" placeholder="Deskripsi option group">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <button class="btn btn-secondary w-100 w-md-auto">
                                    Update
                                </button>

                                <button type="button" class="btn btn-light w-100 w-md-auto" data-bs-dismiss="modal">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Informasi Option Group --}}
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="mdi mdi-information-outline"></i>
                                    Informasi Option Group
                                </h5>

                                <ul class="mb-0">
                                    <li>Nama option group harus <strong>unik</strong> dan tidak boleh sama</li>
                                    <li>Type <strong>Single Select</strong> hanya memperbolehkan memilih satu opsi</li>
                                    <li>Type <strong>Multiple Select</strong> memperbolehkan memilih lebih dari satu
                                        opsi</li>
                                    <li>Type <strong>Text Input</strong> digunakan untuk input teks bebas dari pelanggan
                                    </li>
                                    <li>Option group akan ditampilkan pada menu sesuai pengaturan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
