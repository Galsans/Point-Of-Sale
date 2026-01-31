@extends('layouts.app')

@section('title', 'Tambah Meja')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tambah Meja
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('tables.store') }}" method="POST">
                        @csrf

                        {{-- KODE MEJA --}}
                        <div class="form-group mb-3">
                            <label for="kode_table">Kode Meja</label>
                            <input type="text" name="kode_table" id="kode_table"
                                class="form-control @error('kode_table') is-invalid @enderror" placeholder="Contoh: T-01"
                                value="{{ old('kode_table') }}" required>

                            @error('kode_table')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- LANTAI --}}
                        <div class="form-group mb-3">
                            <label for="floor">Lantai Tingkat Meja</label>
                            <select name="floor" id="floor" class="form-control @error('floor') is-invalid @enderror">
                                <option value="">Pilih Lantai</option>
                                <option value="1">Lantai 1</option>
                                <option value="2">Lantai 2</option>
                                <option value="3">Lantai 3</option>
                            </select>

                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div class="form-group mb-4">
                            <label for="status">Status Meja</label>
                            <select name="status" id="status"
                                class="form-control @error('status') is-invalid @enderror">
                                <option value="available">Kosong</option>
                                <option value="occupied">Terisi</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('tables.index') }}" class="btn btn-light">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-gradient-primary">
                                Simpan Meja
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
                        <li>Kode meja harus unik</li>
                        <li>Status default sebaiknya <strong>Kosong</strong></li>
                        <li>QR Code akan otomatis di-generate</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
