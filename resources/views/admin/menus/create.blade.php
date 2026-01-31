@extends('layouts.app')

@section('title', 'Tambah Menu')

{{-- CSS Select2 di head --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 4px 12px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tambah Menu
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- CATEGORY SELECT2 --}}
                        <div class="form-group mb-3">
                            <label for="category_id">Kategori Menu</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NAMA MENU --}}
                        <div class="form-group mb-3">
                            <label for="name">Nama Menu</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Nasi Goreng"
                                value="{{ old('name') }}" required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HARGA --}}
                        <div class="form-group mb-3">
                            <label for="price">Harga</label>
                            <input type="number" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                min="0" required>

                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- IMAGE --}}
                        <div class="form-group mb-3">
                            <label for="image">Gambar</label>
                            <input type="file" name="image" id="image" required
                                class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- DESCRPTION MENU --}}
                        <div class="form-group mb-3">
                            <label for="description">Deskripsi Menu</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Contoh: Nasi goreng dengan telur dan sayuran" required>{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group mb-4">
                            <label>Status Menu</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_available" id="available1"
                                    value="1" {{ old('is_available', 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="available1">Tersedia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_available" id="available0"
                                    value="0" {{ old('is_available') === '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="available0">Tidak Tersedia</label>
                            </div>

                            @error('is_available')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('menus.index') }}" class="btn btn-light">Kembali</a>
                            <button type="submit" class="btn btn-gradient-primary">Simpan Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- INFO / HELP --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="mb-3">
                        <i class="mdi mdi-information-outline"></i>
                        Informasi Tambah Menu
                    </h5>

                    <ul class="mb-0">
                        <li><strong>Kategori:</strong> Pilih kategori menu yang sesuai. Jika belum ada kategori, silakan
                            buat terlebih dahulu.</li>
                        <li><strong>Nama Menu:</strong> Harus unik dan mudah dikenali oleh pelanggan.</li>
                        <li><strong>Harga:</strong> Masukkan harga dalam format angka. Tidak boleh negatif.</li>
                        <li><strong>Gambar:</strong> Upload gambar menu agar terlihat menarik. Maksimal ukuran 2MB. Jika
                            tidak ada, sistem akan menampilkan gambar default.</li>
                        <li><strong>Status Menu:</strong> Tentukan apakah menu tersedia atau tidak. Status "Tersedia"
                            berarti menu bisa dipesan, "Tidak Tersedia" berarti menu sementara tidak bisa dipesan.</li>
                        <li><strong>Tips Tambahan:</strong> Pastikan semua field wajib diisi agar menu bisa tersimpan dengan
                            benar.</li>
                    </ul>

                    <small class="text-muted d-block mt-3">
                        Semua informasi ini membantu agar menu yang ditambahkan lebih rapi dan mudah di-manage.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS Select2 di bagian scripts (setelah jQuery dari layout) --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#category_id').select2({
            placeholder: 'Cari kategori...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: '/api/categories/search',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(response, params) {
                    params.page = params.page || 1;

                    return {
                        results: response.data.map(item => ({
                            id: item.id,
                            text: item.name
                        })),
                        pagination: {
                            more: response.pagination.more
                        }
                    };
                },
                cache: true
            }
        });
    </script>
@endpush
