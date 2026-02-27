{{--
    resources/views/admin/price-offers/_form.blade.php
    Digunakan oleh create.blade.php dan edit.blade.php

    create.blade.php:
        @extends('layouts.app')
        @section('title', 'Buat Paket Baru')
        @section('content') @include('price-offers._form',['isEdit'=>false]) @endsection

    edit.blade.php:
        @extends('layouts.app')
        @section('title', 'Edit: ' . $priceOffer->name)
        @section('content') @include('price-offers._form',['isEdit'=>true]) @endsection
--}}

@php
    $isEdit = $isEdit ?? false;
    $offer = $isEdit ? $priceOffer : null;
    $action = $isEdit ? route('price-offers.update', $offer) : route('price-offers.store');

    $savedItems = old(
        'items',
        $isEdit ? $offer->items->map(fn($i) => ['menu_id' => $i->menu_id, 'quantity' => $i->quantity])->toArray() : [],
    );

    $menuGroupsForJs = $menus->map(
        fn($items) => $items
            ->map(
                fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'price' => (float) $m->price,
                ],
            )
            ->values(),
    );
@endphp

@push('styles')
    <style>
        :root {
            --pf-accent: #7c3aed;
            --pf-accent-lt: #ede9fe;
            --pf-accent-md: #c4b5fd;
            --pf-success: #16a34a;
            --pf-success-lt: #dcfce7;
            --pf-danger: #dc2626;
            --pf-danger-lt: #fee2e2;
            --pf-warn: #d97706;
            --pf-warn-lt: #fef3c7;
            --pf-gray-50: #f9fafb;
            --pf-gray-100: #f3f4f6;
            --pf-gray-200: #e5e7eb;
            --pf-gray-300: #d1d5db;
            --pf-gray-400: #9ca3af;
            --pf-gray-500: #6b7280;
            --pf-gray-600: #4b5563;
            --pf-gray-700: #374151;
            --pf-gray-800: #1f2937;
            --pf-white: #ffffff;
            --pf-r: 10px;
            --pf-r-sm: 7px;
            --pf-shadow-sm: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .04);
            --pf-shadow: 0 4px 16px rgba(0, 0, 0, .08), 0 1px 4px rgba(0, 0, 0, .04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── Breadcrumb ── */
        .pf-bc {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .78rem;
            color: var(--pf-gray-400);
            margin-bottom: 1.25rem;
        }

        .pf-bc a {
            color: var(--pf-gray-500);
            text-decoration: none;
            transition: color .15s;
        }

        .pf-bc a:hover {
            color: var(--pf-accent);
        }

        .pf-bc__sep {
            color: var(--pf-gray-300);
        }

        /* ── Page title ── */
        .pf-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pf-gray-800);
            letter-spacing: -.025em;
            margin-bottom: 1.5rem;
        }

        /* ── Layout ── */
        .pf-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.25rem;
            align-items: start;
        }

        @media(max-width:900px) {
            .pf-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Panel / Card ── */
        .pf-panel {
            background: var(--pf-white);
            border: 1px solid var(--pf-gray-200);
            border-radius: var(--pf-r);
            box-shadow: var(--pf-shadow-sm);
            overflow: hidden;
            margin-bottom: 1.1rem;
        }

        .pf-panel:last-child {
            margin-bottom: 0;
        }

        .pf-panel__hd {
            padding: .875rem 1.25rem;
            border-bottom: 1px solid var(--pf-gray-100);
            display: flex;
            align-items: center;
            gap: .625rem;
        }

        .pf-panel__hd-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--pf-r-sm);
            background: var(--pf-accent-lt);
            color: var(--pf-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pf-panel__hd h2 {
            font-size: .85rem;
            font-weight: 700;
            color: var(--pf-gray-700);
        }

        .pf-panel__bd {
            padding: 1.25rem;
        }

        /* ── Form elements ── */
        .pf-field {
            margin-bottom: 1.1rem;
        }

        .pf-field:last-child {
            margin-bottom: 0;
        }

        .pf-label {
            display: block;
            font-size: .73rem;
            font-weight: 600;
            color: var(--pf-gray-600);
            margin-bottom: .4rem;
        }

        .pf-label em {
            color: var(--pf-danger);
            font-style: normal;
        }

        .pf-input,
        .pf-textarea,
        .pf-select {
            width: 100%;
            border: 1px solid var(--pf-gray-200);
            background: var(--pf-white);
            color: var(--pf-gray-700);
            padding: .625rem .875rem;
            border-radius: var(--pf-r-sm);
            font-size: .825rem;
            transition: border-color .15s, box-shadow .15s;
        }

        .pf-input:focus,
        .pf-textarea:focus,
        .pf-select:focus {
            outline: none;
            border-color: var(--pf-accent);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .1);
        }

        .pf-textarea {
            min-height: 80px;
            resize: vertical;
            line-height: 1.6;
        }

        .pf-hint {
            font-size: .7rem;
            color: var(--pf-gray-400);
            margin-top: .3rem;
        }

        .pf-err {
            font-size: .72rem;
            color: var(--pf-danger);
            margin-top: .3rem;
        }

        .pf-row {
            display: grid;
            gap: .875rem;
        }

        .pf-row-2 {
            grid-template-columns: 1fr 1fr;
        }

        .pf-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        @media(max-width:560px) {

            .pf-row-2,
            .pf-row-3 {
                grid-template-columns: 1fr;
            }
        }

        option,
        optgroup {
            background: var(--pf-white);
            color: var(--pf-gray-700);
        }

        /* ── Toggle ── */
        .pf-toggle {
            display: flex;
            align-items: center;
            gap: .625rem;
            cursor: pointer;
            user-select: none;
        }

        .pf-toggle input {
            display: none;
        }

        .pf-toggle__track {
            width: 40px;
            height: 22px;
            border-radius: 11px;
            background: var(--pf-gray-300);
            position: relative;
            transition: background .18s;
            flex-shrink: 0;
        }

        .pf-toggle__track::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            border-radius: 9px;
            background: #fff;
            transition: transform .18s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .pf-toggle input:checked+.pf-toggle__track {
            background: var(--pf-accent);
        }

        .pf-toggle input:checked+.pf-toggle__track::after {
            transform: translateX(18px);
        }

        .pf-toggle__txt {
            font-size: .8rem;
            color: var(--pf-gray-600);
        }

        /* ── Image upload ── */
        .pf-upload {
            border: 2px dashed var(--pf-gray-200);
            border-radius: var(--pf-r);
            padding: 1.75rem 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s, background .18s;
        }

        .pf-upload:hover {
            border-color: var(--pf-accent);
            background: var(--pf-accent-lt);
        }

        .pf-upload input {
            display: none;
        }

        .pf-upload__icon {
            color: var(--pf-gray-300);
            margin-bottom: .625rem;
        }

        .pf-upload__text {
            font-size: .8rem;
            font-weight: 500;
            color: var(--pf-gray-500);
        }

        .pf-upload__sub {
            font-size: .7rem;
            color: var(--pf-gray-400);
            margin-top: .25rem;
        }

        .pf-preview {
            position: relative;
            border-radius: var(--pf-r);
            overflow: hidden;
        }

        .pf-preview img {
            width: 100%;
            object-fit: cover;
            max-height: 180px;
            border-radius: var(--pf-r);
            display: block;
        }

        .pf-preview__rm {
            position: absolute;
            top: .5rem;
            right: .5rem;
            background: rgba(255, 255, 255, .9);
            border: 1px solid var(--pf-gray-200);
            color: var(--pf-danger);
            width: 26px;
            height: 26px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--pf-shadow-sm);
            transition: background .15s;
        }

        .pf-preview__rm:hover {
            background: var(--pf-danger-lt);
        }

        /* ── Price preview ── */
        .pf-price-box {
            background: var(--pf-gray-50);
            border: 1px solid var(--pf-gray-200);
            border-radius: var(--pf-r-sm);
            overflow: hidden;
        }

        .pf-price-row {
            display: flex;
            justify-content: space-between;
            padding: .55rem .875rem;
            font-size: .78rem;
            color: var(--pf-gray-500);
            border-bottom: 1px solid var(--pf-gray-100);
        }

        .pf-price-row:last-child {
            border-bottom: none;
        }

        .pf-price-row--main {
            color: var(--pf-gray-800);
            font-weight: 700;
            font-size: .875rem;
        }

        .pf-price-row--saving {
            color: var(--pf-success);
            font-weight: 700;
        }

        .pf-price-row__val {
            font-weight: 600;
        }

        /* ── Items builder ── */
        .pf-items {
            display: flex;
            flex-direction: column;
            gap: .625rem;
            margin-bottom: .875rem;
        }

        .pf-item {
            background: var(--pf-gray-50);
            border: 1px solid var(--pf-gray-200);
            border-radius: var(--pf-r-sm);
            padding: .75rem .875rem;
            display: grid;
            grid-template-columns: 1fr 80px 30px;
            gap: .625rem;
            align-items: end;
            animation: itemIn .18s ease;
        }

        @keyframes itemIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .pf-item__lbl {
            font-size: .68rem;
            font-weight: 600;
            color: var(--pf-gray-400);
            text-transform: uppercase;
            letter-spacing: .06em;
            display: block;
            margin-bottom: .3rem;
        }

        .pf-item__rm {
            background: transparent;
            border: 1px solid var(--pf-gray-200);
            color: var(--pf-gray-400);
            width: 30px;
            height: 34px;
            border-radius: var(--pf-r-sm);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .pf-item__rm:hover {
            border-color: var(--pf-danger);
            color: var(--pf-danger);
            background: var(--pf-danger-lt);
        }

        .btn-add-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            width: 100%;
            padding: .6rem;
            border-radius: var(--pf-r-sm);
            background: transparent;
            border: 1px dashed var(--pf-gray-200);
            color: var(--pf-gray-500);
            font-size: .78rem;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-add-item:hover {
            border-color: var(--pf-accent);
            color: var(--pf-accent);
            background: var(--pf-accent-lt);
        }

        /* ── Footer buttons ── */
        .pf-footer {
            display: flex;
            gap: .625rem;
            justify-content: flex-end;
            padding-top: .875rem;
        }

        .btn-submit {
            background: var(--pf-accent);
            color: #fff;
            font-weight: 600;
            font-size: .825rem;
            padding: .65rem 1.75rem;
            border-radius: var(--pf-r-sm);
            border: none;
            cursor: pointer;
            transition: background .18s, transform .12s, box-shadow .18s;
            box-shadow: 0 2px 8px rgba(124, 58, 237, .25);
        }

        .btn-submit:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, .3);
        }

        .btn-cancel {
            background: var(--pf-white);
            border: 1px solid var(--pf-gray-200);
            color: var(--pf-gray-600);
            padding: .65rem 1.25rem;
            border-radius: var(--pf-r-sm);
            font-size: .825rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all .15s;
        }

        .btn-cancel:hover {
            border-color: var(--pf-danger);
            color: var(--pf-danger);
        }

        /* ── Flash ── */
        .pf-flash {
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .875rem 1rem;
            border-radius: var(--pf-r-sm);
            font-size: .8rem;
            font-weight: 500;
            margin-bottom: 1.1rem;
        }

        .pf-flash--err {
            background: var(--pf-danger-lt);
            border: 1px solid #fecaca;
            color: var(--pf-danger);
        }

        /* ── Section divider label ── */
        .pf-section-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--pf-gray-400);
            margin-bottom: .625rem;
            padding-bottom: .4rem;
            border-bottom: 1px solid var(--pf-gray-100);
        }
    </style>
@endpush

<div style="padding:.25rem 0 2rem">

    {{-- Breadcrumb --}}
    <div class="pf-bc">
        <a href="{{ route('price-offers.index') }}">Penawaran Harga</a>
        <span class="pf-bc__sep">›</span>
        <span>{{ $isEdit ? 'Edit: ' . $offer->name : 'Buat Paket Baru' }}</span>
    </div>

    <div class="pf-title">{{ $isEdit ? 'Edit Paket' : 'Buat Paket Baru' }}</div>

    @if (session('error'))
        <div class="pf-flash pf-flash--err">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form id="poForm" method="POST" action="{{ $action }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="pf-layout">

            {{-- ── LEFT ── --}}
            <div>

                {{-- Info Utama --}}
                <div class="pf-panel">
                    <div class="pf-panel__hd">
                        <div class="pf-panel__hd-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                                <path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z" />
                            </svg>
                        </div>
                        <h2>Informasi Paket</h2>
                    </div>
                    <div class="pf-panel__bd">
                        <div class="pf-field">
                            <label class="pf-label">Nama Paket <em>*</em></label>
                            <input type="text" name="name" class="pf-input"
                                placeholder="cth: Paket Hemat Sarapan A" value="{{ old('name', $offer->name ?? '') }}"
                                required>
                            @error('name')
                                <p class="pf-err">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pf-field">
                            <label class="pf-label">Deskripsi</label>
                            <textarea name="description" class="pf-textarea" placeholder="Penjelasan singkat tentang paket ini…">{{ old('description', $offer->description ?? '') }}</textarea>
                        </div>

                        <div class="pf-row pf-row-2">
                            <div class="pf-field">
                                <label class="pf-label">Kategori Paket</label>
                                <input type="text" name="category" class="pf-input" list="cat-opts"
                                    placeholder="cth: Sarapan" value="{{ old('category', $offer->category ?? '') }}">
                                <datalist id="cat-opts">
                                    <option value="Sarapan">
                                    <option value="Makan Siang">
                                    <option value="Makan Malam">
                                    <option value="Snack">
                                    <option value="Minuman">
                                </datalist>
                            </div>
                            <div class="pf-field">
                                <label class="pf-label">Badge Label</label>
                                <input type="text" name="badge" class="pf-input" list="badge-opts"
                                    placeholder="cth: Terlaris" value="{{ old('badge', $offer->badge ?? '') }}">
                                <datalist id="badge-opts">
                                    <option value="Terlaris">
                                    <option value="Baru">
                                    <option value="Promo">
                                    <option value="Limited">
                                    <option value="Best Value">
                                </datalist>
                            </div>
                        </div>

                        <div class="pf-row pf-row-3">
                            <div class="pf-field">
                                <label class="pf-label">Tersedia Dari</label>
                                <input type="time" name="available_from" class="pf-input"
                                    value="{{ old('available_from', $offer ? substr($offer->available_from ?? '', 0, 5) : '') }}">
                            </div>
                            <div class="pf-field">
                                <label class="pf-label">Tersedia Sampai</label>
                                <input type="time" name="available_until" class="pf-input"
                                    value="{{ old('available_until', $offer ? substr($offer->available_until ?? '', 0, 5) : '') }}">
                                <p class="pf-hint">Kosongkan = sepanjang hari</p>
                            </div>
                            <div class="pf-field">
                                <label class="pf-label">Sort Order</label>
                                <input type="number" name="sort_order" class="pf-input"
                                    value="{{ old('sort_order', $offer->sort_order ?? 0) }}" min="0">
                            </div>
                        </div>

                        <div class="pf-field">
                            <label class="pf-toggle">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $offer->is_active ?? true) ? 'checked' : '' }}>
                                <span class="pf-toggle__track"></span>
                                <span class="pf-toggle__txt">Tampilkan paket di QR Menu</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Items Builder --}}
                <div class="pf-panel">
                    <div class="pf-panel__hd">
                        <div class="pf-panel__hd-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <line x1="8" y1="6" x2="21" y2="6" />
                                <line x1="8" y1="12" x2="21" y2="12" />
                                <line x1="8" y1="18" x2="21" y2="18" />
                                <line x1="3" y1="6" x2="3.01" y2="6" />
                                <line x1="3" y1="12" x2="3.01" y2="12" />
                                <line x1="3" y1="18" x2="3.01" y2="18" />
                            </svg>
                        </div>
                        <h2>Isi Paket <span style="font-weight:400;color:var(--pf-gray-400);font-size:.78rem">(min. 1
                                item)</span></h2>
                    </div>
                    <div class="pf-panel__bd">
                        @error('items')
                            <p class="pf-err" style="margin-bottom:.875rem">{{ $message }}</p>
                        @enderror

                        <div class="pf-items" id="itemsList">
                            @foreach ($savedItems as $idx => $savedItem)
                                <div class="pf-item" data-index="{{ $idx }}">
                                    <div>
                                        <span class="pf-item__lbl">Menu</span>
                                        <select name="items[{{ $idx }}][menu_id]"
                                            class="pf-select pf-menu-sel" required onchange="recalc()">
                                            <option value="">— Pilih Menu —</option>
                                            @foreach ($menus as $catName => $catMenus)
                                                <optgroup label="{{ $catName }}">
                                                    @foreach ($catMenus as $menu)
                                                        <option value="{{ $menu->id }}"
                                                            data-price="{{ $menu->price }}"
                                                            {{ ($savedItem['menu_id'] ?? null) == $menu->id ? 'selected' : '' }}>
                                                            {{ $menu->name }} — Rp
                                                            {{ number_format($menu->price, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <span class="pf-item__lbl">Qty</span>
                                        <input type="number" name="items[{{ $idx }}][quantity]"
                                            class="pf-input pf-qty-inp" value="{{ $savedItem['quantity'] ?? 1 }}"
                                            min="1" required oninput="recalc()" style="text-align:center;">
                                    </div>
                                    <button type="button" class="pf-item__rm" onclick="removeItem(this)">
                                        <svg width="12" height="12" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn-add-item" onclick="addItem()">
                            <svg width="12" height="12" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Tambah Item Menu
                        </button>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="pf-footer">
                    <a href="{{ route('price-offers.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Buat Paket' }}
                    </button>
                </div>

            </div>{{-- /left --}}

            {{-- ── RIGHT ── --}}
            <div>

                {{-- Harga --}}
                <div class="pf-panel">
                    <div class="pf-panel__hd">
                        <div class="pf-panel__hd-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <h2>Harga Paket</h2>
                    </div>
                    <div class="pf-panel__bd">
                        <div class="pf-field">
                            <label class="pf-label">Harga Jual Paket (Rp) <em>*</em></label>
                            <input type="number" name="package_price" id="packagePrice" class="pf-input"
                                placeholder="0" min="0" step="500" required
                                value="{{ old('package_price', $offer->package_price ?? '') }}" oninput="recalc()">
                            <p class="pf-hint">Harga bundling yang ditampilkan ke customer</p>
                            @error('package_price')
                                <p class="pf-err">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pf-price-box" id="priceBox" style="display:none">
                            <div class="pf-price-row">
                                <span>Total Harga Satuan</span>
                                <span class="pf-price-row__val" id="origPriceVal">—</span>
                            </div>
                            <div class="pf-price-row pf-price-row--main">
                                <span>Harga Paket</span>
                                <span class="pf-price-row__val" id="pkgPriceVal">—</span>
                            </div>
                            <div class="pf-price-row pf-price-row--saving" id="savingRow" style="display:none">
                                <span>💰 Customer Hemat</span>
                                <span class="pf-price-row__val" id="savingVal">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Foto --}}
                <div class="pf-panel">
                    <div class="pf-panel__hd">
                        <div class="pf-panel__hd-icon">
                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <h2>Foto Paket</h2>
                    </div>
                    <div class="pf-panel__bd">
                        @if ($isEdit && $offer->image_url)
                            <div class="pf-preview" id="existingPreview">
                                <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}">
                                <button type="button" class="pf-preview__rm" id="removeExistingBtn">
                                    <svg width="11" height="11" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                                <input type="hidden" name="remove_image" id="removeImageFlag" value="0">
                            </div>
                        @endif

                        <label class="pf-upload" id="uploadZone" for="imgInput"
                            style="{{ $isEdit && $offer->image_url ? 'display:none;margin-top:.75rem' : '' }}">
                            <input type="file" name="image" id="imgInput" accept="image/*"
                                onchange="previewImg(this)">
                            <div class="pf-upload__icon">
                                <svg width="36" height="36" fill="none" stroke="currentColor"
                                    stroke-width="1.2" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                            </div>
                            <p class="pf-upload__text">Klik untuk upload foto</p>
                            <p class="pf-upload__sub">JPG, PNG, WebP · Maks. 2 MB</p>
                        </label>

                        <div class="pf-preview" id="newPreview" style="display:none;margin-top:.75rem">
                            <img id="newPreviewImg" src="" alt="Preview">
                            <button type="button" class="pf-preview__rm" onclick="clearImg()">
                                <svg width="11" height="11" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>

                        @error('image')
                            <p class="pf-err" style="margin-top:.5rem">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>{{-- /right --}}

        </div>
    </form>
</div>

<script>
    const MENU_GROUPS = @json($menuGroupsForJs);
    let itemIdx = {{ count($savedItems) }};

    function buildOptions(selectedId = null) {
        let html = '<option value="">— Pilih Menu —</option>';
        for (const [cat, items] of Object.entries(MENU_GROUPS)) {
            html += `<optgroup label="${esc(cat)}">`;
            for (const m of items) {
                const sel = selectedId == m.id ? ' selected' : '';
                html +=
                    `<option value="${m.id}" data-price="${m.price}"${sel}>${esc(m.name)} — Rp ${fmt(m.price)}</option>`;
            }
            html += '</optgroup>';
        }
        return html;
    }

    function esc(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function addItem() {
        const list = document.getElementById('itemsList');
        const idx = itemIdx++;
        const row = document.createElement('div');
        row.className = 'pf-item';
        row.dataset.index = idx;
        row.innerHTML = `
        <div>
            <span class="pf-item__lbl">Menu</span>
            <select name="items[${idx}][menu_id]" class="pf-select pf-menu-sel" required onchange="recalc()">
                ${buildOptions()}
            </select>
        </div>
        <div>
            <span class="pf-item__lbl">Qty</span>
            <input type="number" name="items[${idx}][quantity]"
                class="pf-input pf-qty-inp" value="1" min="1" required
                oninput="recalc()" style="text-align:center;">
        </div>
        <button type="button" class="pf-item__rm" onclick="removeItem(this)">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    `;
        list.appendChild(row);
        row.querySelector('select').focus();
        recalc();
    }

    function removeItem(btn) {
        btn.closest('.pf-item').remove();
        recalc();
    }

    function recalc() {
        let total = 0;
        document.querySelectorAll('.pf-item').forEach(row => {
            const sel = row.querySelector('.pf-menu-sel');
            const qty = parseInt(row.querySelector('.pf-qty-inp').value) || 0;
            if (sel && sel.value) {
                total += (parseFloat(sel.options[sel.selectedIndex].dataset.price) || 0) * qty;
            }
        });

        const pkg = parseFloat(document.getElementById('packagePrice').value) || 0;
        const box = document.getElementById('priceBox');

        if (total > 0 || pkg > 0) {
            box.style.display = 'block';
            document.getElementById('origPriceVal').textContent = 'Rp ' + fmt(total);
            document.getElementById('pkgPriceVal').textContent = 'Rp ' + fmt(pkg);
            const saving = total - pkg;
            const sRow = document.getElementById('savingRow');
            if (saving > 0 && total > 0) {
                sRow.style.display = 'flex';
                document.getElementById('savingVal').textContent =
                    `Rp ${fmt(saving)} (${((saving/total)*100).toFixed(1)}%)`;
            } else {
                sRow.style.display = 'none';
            }
        } else {
            box.style.display = 'none';
        }
    }

    function fmt(n) {
        return Math.round(n).toLocaleString('id-ID');
    }

    function previewImg(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('newPreviewImg').src = e.target.result;
            document.getElementById('newPreview').style.display = 'block';
            document.getElementById('uploadZone').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }

    function clearImg() {
        document.getElementById('imgInput').value = '';
        document.getElementById('newPreview').style.display = 'none';
        document.getElementById('uploadZone').style.display = 'block';
    }

    const rmExistingBtn = document.getElementById('removeExistingBtn');
    if (rmExistingBtn) {
        rmExistingBtn.addEventListener('click', () => {
            document.getElementById('removeImageFlag').value = '1';
            document.getElementById('existingPreview').style.display = 'none';
            document.getElementById('uploadZone').style.display = 'block';
        });
    }

    window.addEventListener('DOMContentLoaded', () => {
        if (document.querySelectorAll('.pf-item').length === 0) addItem();
        recalc();
    });
</script>
