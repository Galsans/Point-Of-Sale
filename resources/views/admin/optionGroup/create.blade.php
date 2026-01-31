@extends('layouts.app')

@section('title', 'Tambah Option Group')

@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-furniture"></i>
            </span>
            Tambah Option Group
        </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('option-groups.store') }}" method="POST">
                        @csrf
                        {{-- NAMA OPTION GROUP --}}
                        <div class="form-group mb-3">
                            <label for="name">Nama Option Group</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Makanan"
                                value="{{ old('name') }}" required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="type">Type Option Group</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror"
                                required>
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

                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="name">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                placeholder="Deskripsi option group">{{ old('description') }}</textarea></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('option-groups.index') }}" class="btn btn-light">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-gradient-primary">
                                Simpan Option Group
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
                        Informasi Option Group
                    </h5>

                    <ul class="mb-0">
                        <li>Nama option group harus <strong>unik</strong> dan tidak boleh sama</li>
                        <li>Type <strong>Single Select</strong> hanya memperbolehkan memilih satu opsi</li>
                        <li>Type <strong>Multiple Select</strong> memperbolehkan memilih lebih dari satu opsi</li>
                        <li>Type <strong>Text Input</strong> digunakan untuk input teks bebas dari pelanggan</li>
                        <li>Option group akan ditampilkan pada menu sesuai pengaturan</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection
