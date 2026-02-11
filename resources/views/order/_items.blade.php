<style>
    .menu-disabled {
        opacity: 0.5;
        pointer-events: none;
        /* 🔥 bikin benar-benar tidak bisa diklik */
        cursor: not-allowed;
    }
</style>
@forelse ($data as $menu)
    {{-- <div class="col-12 col-sm-6 col-md-4 menu-item" data-category="{{ $menu->category_id }}"
        data-name="{{ strtolower($menu->name) }}"> --}}
    <div class="col-12 col-sm-6 col-md-4 menu-item
    {{ !$menu->is_available ? 'menu-disabled' : '' }}"
        data-category="{{ $menu->category_id }}" data-name="{{ strtolower($menu->name) }}">

        <div class="menu-card">
            <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://via.placeholder.com/300x140?text=No+Image' }}"
                class="menu-card-img" alt="{{ $menu->name }}" loading="lazy">

            <div class="menu-card-body">
                <div class="menu-category">
                    <i class="mdi mdi-tag-outline"></i>
                    {{ $menu->category->name ?? 'Uncategorized' }}
                </div>

                <h6 class="menu-name">{{ $menu->name }}</h6>

                <div class="menu-price">
                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                </div>

                <div class="qty-selector">
                    <button type="button" class="qty-btn qty-minus" data-menu-id="{{ $menu->id }}"
                        data-price="{{ $menu->price }}" disabled>
                        <i class="mdi mdi-minus"></i>
                    </button>

                    <span class="qty-display" id="qty-{{ $menu->id }}">0</span>

                    <button type="button" class="qty-btn qty-plus" data-menu-id="{{ $menu->id }}"
                        data-price="{{ $menu->price }}">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            <h5>Menu Tidak Ditemukan</h5>
            <p>Coba kata kunci lain atau pilih kategori lain</p>
        </div>
    </div>
@endforelse
