@extends('layouts.app')

@section('title', 'Tambah Category')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tambah Category
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        {{-- NAMA CATEGORY --}}
                        <div class="form-group mb-3">
                            <label for="name">Nama Category</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Makanan"
                                value="{{ old('name') }}" required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-light">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-gradient-primary">
                                Simpan Category
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

                    <ul class="mb-0">
                        <li>Nama category harus unik</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
