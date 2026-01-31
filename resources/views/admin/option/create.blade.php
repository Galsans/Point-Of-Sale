@extends('layouts.app')

@section('title', 'Tambah Category')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tambah Option
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('options.store') }}" method="POST">
                        @csrf

                        {{-- OPTION GROUP --}}
                        <div class="form-group mb-3">
                            <label for="option_group_id">
                                Option Group <span class="text-danger">*</span>
                            </label>

                            <select name="option_group_id" id="option_group_id"
                                class="form-control @error('option_group_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Option Group --</option>
                                @foreach ($optionGroup as $item)
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

                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Extra Cheese"
                                value="{{ old('name') }}" required>

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
                                <input type="number" name="extra_price" id="extra_price"
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

                            <select name="is_active" id="is_active"
                                class="form-control @error('is_active') is-invalid @enderror">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                                    Nonaktif
                                </option>
                            </select>

                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('options.index') }}" class="btn btn-light">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-gradient-primary">
                                Simpan Option
                            </button>
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
                        Informasi
                    </h5>

                    <ul class="mb-0 small">
                        <li>
                            Option Group digunakan untuk mengelompokkan pilihan
                            (contoh: <strong>Topping</strong>, <strong>Level Pedas</strong>).
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

@endsection
