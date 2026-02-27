@extends('layouts.order')

@section('title', 'Pilih Menu - ' . $table->kode_table)
@section('table-info', $table->kode_table)

@section('content')
    <div class="container">

        {{-- SEARCH BAR --}}
        <div class="search-bar">
            <i class="mdi mdi-magnify search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari menu..."
                value="{{ request('search') }}">
        </div>

        {{-- PAKET BUNDLING --}}
        @if (isset($packages) && $packages->count() > 0)
            <div class="pkg-section">
                <div class="pkg-header">
                    <div class="pkg-header-left">
                        <span class="pkg-icon">🎁</span>
                        <div>
                            <h2 class="pkg-title">Paket Hemat</h2>
                            <p class="pkg-subtitle">Lebih hemat dari beli satuan</p>
                        </div>
                    </div>
                    <div class="pkg-nav">
                        <button class="pkg-nav-btn" id="pkgPrev"><i class="mdi mdi-chevron-left"></i></button>
                        <button class="pkg-nav-btn" id="pkgNext"><i class="mdi mdi-chevron-right"></i></button>
                    </div>
                </div>

                <div class="pkg-track-wrapper">
                    <div class="pkg-track" id="pkgTrack">
                        {{-- Data paket disimpan di window.__pkgData, BUKAN di HTML attribute --}}
                        {{-- Ini menghindari JSON corrupt akibat htmlspecialchars --}}
                        <script>
                            window.__pkgData = window.__pkgData || {};
                            @foreach ($packages as $pkg)
                                window.__pkgData[{{ $pkg->id }}] = {!! json_encode([
                                    'id' => $pkg->id,
                                    'name' => $pkg->name,
                                    'description' => $pkg->description,
                                    'badge' => $pkg->badge,
                                    'image' => $pkg->image ? asset('storage/' . $pkg->image) : null,
                                    'package_price' => (float) $pkg->package_price,
                                    'original_price' => (float) $pkg->original_price,
                                    'savings' => (float) $pkg->savings,
                                    'savings_percent' => (float) $pkg->savings_percent,
                                    'items' => $pkg->items->map(
                                            fn($i) => [
                                                'name' => $i->item_name,
                                                'category' => $i->category,
                                                'quantity' => $i->quantity,
                                                'price' => (float) $i->item_price,
                                            ],
                                        )->values()->all(),
                                ]) !!};
                            @endforeach
                        </script>

                        @foreach ($packages as $pkg)
                            <div class="pkg-card" data-id="{{ $pkg->id }}">

                                @if ($pkg->badge)
                                    <div class="pkg-badge">{{ $pkg->badge }}</div>
                                @endif

                                <div class="pkg-img-wrap">
                                    @if ($pkg->image)
                                        <img src="{{ asset('storage/' . $pkg->image) }}" alt="{{ $pkg->name }}"
                                            class="pkg-img">
                                    @else
                                        <div class="pkg-img-placeholder"><span>🍱</span></div>
                                    @endif
                                    @if ($pkg->savings > 0)
                                        <div class="pkg-savings-chip">Hemat {{ $pkg->savings_percent }}%</div>
                                    @endif
                                </div>

                                <div class="pkg-body">
                                    <h3 class="pkg-name">{{ $pkg->name }}</h3>
                                    <ul class="pkg-items">
                                        @foreach ($pkg->items as $item)
                                            <li class="pkg-item">
                                                <span class="pkg-item-dot"></span>
                                                <span class="pkg-item-name">
                                                    @if ($item->quantity > 1)
                                                        <strong>{{ $item->quantity }}x</strong>
                                                    @endif
                                                    {{ $item->item_name }}
                                                </span>
                                                <span
                                                    class="pkg-item-cat {{ strtolower($item->category ?? '') }}">{{ $item->category }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="pkg-footer">
                                        <div class="pkg-price-block">
                                            @if ($pkg->savings > 0)
                                                <span class="pkg-price-original">Rp
                                                    {{ number_format($pkg->original_price, 0, ',', '.') }}</span>
                                            @endif
                                            <span class="pkg-price-main">Rp
                                                {{ number_format($pkg->package_price, 0, ',', '.') }}</span>
                                        </div>
                                        {{-- Tombol + tanpa onclick, pakai class js-pkg-add --}}
                                        <button type="button" class="pkg-add-btn js-pkg-add"
                                            aria-label="Tambah {{ $pkg->name }}">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pkg-fade pkg-fade-left"></div>
                    <div class="pkg-fade pkg-fade-right"></div>
                </div>

                <div class="pkg-dots" id="pkgDots">
                    @foreach ($packages as $pkg)
                        <button class="pkg-dot {{ $loop->first ? 'active' : '' }}"
                            data-index="{{ $loop->index }}"></button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- CATEGORY FILTER --}}
        <div class="category-filter">
            <button class="category-tab category-btn active" data-category=""><i class="mdi mdi-menu"></i> Semua</button>
            @foreach ($categories as $category)
                <button class="category-tab category-btn"
                    data-category="{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3" id="table-wrapper">
            @include('order._items', ['data' => $data])
        </div>

        <div id="loading" class="text-center py-4 d-none">
            <div class="spinner-border text-primary"><span class="visually-hidden">Loading...</span></div>
        </div>
        <div id="load-more-trigger" style="height:1px;"></div>

        {{-- MODAL DETAIL PAKET --}}
        <div class="modal fade" id="packageDetailModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content pkg-modal-content">
                    <div class="pkg-modal-img-wrap">
                        <div class="pkg-modal-img-placeholder" id="pkgModalImgPlaceholder"><span>🍱</span></div>
                        <img id="pkgModalImg" src="" alt="" class="pkg-modal-img d-none">
                        <div class="pkg-modal-img-overlay"></div>
                        <button type="button" class="pkg-modal-close" data-bs-dismiss="modal"><i
                                class="mdi mdi-close"></i></button>
                        <div id="pkgModalBadge" class="pkg-modal-badge d-none"></div>
                    </div>
                    <div class="modal-body pkg-modal-body">
                        <h4 class="pkg-modal-title" id="pkgModalTitle">—</h4>
                        <p class="pkg-modal-desc" id="pkgModalDesc"></p>
                        <div class="pkg-modal-price-row">
                            <div>
                                <div class="pkg-modal-price-original d-none" id="pkgModalPriceOriginal"></div>
                                <div class="pkg-modal-price-main" id="pkgModalPriceMain">Rp 0</div>
                            </div>
                            <span class="pkg-modal-savings-badge d-none" id="pkgModalSavingsBadge"></span>
                        </div>
                        <div class="pkg-modal-divider"><span>Isi Paket</span></div>
                        <ul class="pkg-modal-items" id="pkgModalItems"></ul>
                    </div>
                    <div class="modal-footer pkg-modal-footer">
                        <div class="pkg-modal-qty">
                            <button type="button" class="pkg-qty-btn" id="pkgQtyMinus"><i
                                    class="mdi mdi-minus"></i></button>
                            <span class="pkg-qty-value" id="pkgQtyValue">1</span>
                            <button type="button" class="pkg-qty-btn" id="pkgQtyPlus"><i
                                    class="mdi mdi-plus"></i></button>
                        </div>
                        <button type="button" class="pkg-modal-add-btn" id="pkgModalAddBtn">
                            <i class="mdi mdi-cart-plus"></i>
                            <span id="pkgModalAddBtnText">Tambah — Rp 0</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL OPTIONS --}}
        <div class="modal fade" id="menuOptionsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMenuName">Menu Options</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="selectedMenuId">
                        <input type="hidden" id="selectedMenuPrice">
                        <div id="optionGroupsContainer"></div>
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Harga:</strong>
                                <strong class="text-primary" id="modalTotalPrice">Rp 0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="addToCartBtn"><i
                                class="mdi mdi-cart-plus"></i> Tambah</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS SEMUA --}}
        <div class="modal fade" id="clearCartModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="mb-3"><i class="mdi mdi-cart-remove" style="font-size:48px;color:#dc3545;"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Hapus Semua Menu?</h6>
                        <p class="text-muted small mb-0">Semua menu yang sudah dipilih akan dihapus dari keranjang.</p>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger px-4" id="confirmClearBtn"><i
                                class="mdi mdi-delete-sweep"></i> Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FIXED CART BUTTON --}}
    <div class="cart-fixed-bottom">
        <button type="button" class="btn-clear-cart d-none" id="clearCartBtn">
            <i class="mdi mdi-delete-sweep"></i>
        </button>
        <button type="button" class="cart-btn" id="submitOrderBtn" disabled>
            <span><i class="mdi mdi-cart"></i> <span id="cartItemCount">Pilih Menu</span></span>
            <span class="cart-total" id="cartTotal">Rp 0</span>
        </button>
    </div>

    @include('order._style')
@endsection

@include('order._script')
