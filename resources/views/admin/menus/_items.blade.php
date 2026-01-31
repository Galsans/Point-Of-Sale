@forelse ($data as $menu)
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 grid-margin stretch-card">
        <div class="card shadow-sm h-100 position-relative">

            
            {{-- DROPDOWN ACTION --}}
            <div class="dropdown position-absolute top-0 end-0 m-2" style="z-index: 5;">
                <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                    <i class="mdi mdi-dots-vertical"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    <li>
                        {{-- <button class="dropdown-item btn-edit-menus text-dark" data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}" data-bs-toggle="modal" data-bs-target="#editMenuModal">
                            <i class="mdi mdi-pencil me-2"></i> Edit
                        </button> --}}
                        {{-- <button class="dropdown-item btn-edit-menus text-dark" data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}">
                            <i class="mdi mdi-pencil me-2"></i> Edit
                        </button> --}}
                        <button class="dropdown-item btn-edit-menus text-dark" data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}" data-description="{{ $menu->description }}"
                            data-price="{{ $menu->price }}" data-category-id="{{ $menu->category_id }}"
                            data-category-name="{{ $menu->category->name }}"
                            data-is-available="{{ $menu->is_available }}"
                            data-image="{{ $menu->image ? asset('storage/' . $menu->image) : '' }}">
                            <i class="mdi mdi-pencil me-2"></i> Edit
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item text-danger btn-delete-category" data-id="{{ $menu->id }}"
                            data-name="{{ $menu->name }}" data-bs-toggle="modal" data-bs-target="#deleteMenuModal">
                            <i class="mdi mdi-delete me-2"></i> Hapus
                        </button>
                    </li>
                </ul>
            </div>

            {{-- IMAGE --}}
            <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('assets/images/unnamed.jpg') }}"
                class="card-img-top" style="height:180px; object-fit:cover;" alt="{{ $menu->name }}">


            {{-- BADGE STATUS --}}
            <span
                class="badge
            {{ $menu->is_available ? 'bg-success' : 'bg-danger' }}
            position-absolute top-0 start-0 m-2">
                {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
            </span>

            <div class="card-body text-center d-flex flex-column">

                {{-- CATEGORY --}}
                <span class="text-muted small mb-1">
                    {{ $menu->category->name ?? '-' }}
                </span>

                {{-- NAME --}}
                <h5 class="fw-bold mb-2 text-truncate">
                    {{ $menu->name }}
                </h5>

                {{-- PRICE --}}
                <h6 class="text-primary fw-semibold mb-3">
                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                </h6>

                {{-- BUTTON --}}
                <a href="#" class="btn btn-sm btn-gradient-info btn-detail-menu mt-2"
                    data-name="{{ $menu->name }}" data-category="{{ $menu->category->name ?? '-' }}"
                    data-price="{{ $menu->price }}" data-is-available="{{ $menu->is_available }}"
                    data-image="{{ $menu->image ? asset('storage/' . $menu->image) : '' }}"
                    data-description="{{ $menu->description ?? '-' }}">
                    Detail Menu
                </a>

                <a href="{{ route('menus.show', $menu->id) }}"
                    class="btn btn-sm btn-secondary text-dark btn-config-menu mt-2">
                    Konfigurasi Menu
                </a>
            </div>
        </div>
    </div>
@empty
    {{-- DATA KOSONG --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="mdi mdi-food-off mdi-48px text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada menu</h5>
                <p class="text-muted mb-3">
                    Tambahkan menu agar produk bisa dikelompokkan
                </p>
                <a href="{{ route('menus.create') }}" class="btn btn-gradient-primary btn-sm">
                    + Tambah Menu
                </a>
            </div>
        </div>
    </div>
@endforelse
