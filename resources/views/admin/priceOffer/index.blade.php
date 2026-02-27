{{-- resources/views/admin/price-offers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Penawaran Harga')

@push('styles')
    <style>
        /* ── Token selaraskan dengan tema admin existing (light, purple accent) ── */
        :root {
            --po-accent: #7c3aed;
            /* purple utama dari sidebar */
            --po-accent-lt: #ede9fe;
            /* purple muda untuk bg badge/chip */
            --po-accent-md: #c4b5fd;
            --po-success: #16a34a;
            --po-success-lt: #dcfce7;
            --po-danger: #dc2626;
            --po-danger-lt: #fee2e2;
            --po-warn: #d97706;
            --po-warn-lt: #fef3c7;
            --po-gray-50: #f9fafb;
            --po-gray-100: #f3f4f6;
            --po-gray-200: #e5e7eb;
            --po-gray-300: #d1d5db;
            --po-gray-400: #9ca3af;
            --po-gray-500: #6b7280;
            --po-gray-600: #4b5563;
            --po-gray-700: #374151;
            --po-gray-800: #1f2937;
            --po-white: #ffffff;
            --po-radius: 10px;
            --po-radius-sm: 7px;
            --po-shadow-sm: 0 1px 3px rgba(0, 0, 0, .08), 0 1px 2px rgba(0, 0, 0, .04);
            --po-shadow: 0 4px 16px rgba(0, 0, 0, .08), 0 1px 4px rgba(0, 0, 0, .04);
            --po-shadow-md: 0 8px 24px rgba(0, 0, 0, .1), 0 2px 8px rgba(0, 0, 0, .06);
        }

        /* ── Wrapper ── */
        .po-page {
            padding: .25rem 0 2rem;
        }

        /* ── Page Header ── */
        .po-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .po-header__title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--po-gray-800);
            letter-spacing: -.025em;
            margin: 0 0 .2rem;
        }

        .po-header__sub {
            font-size: .8rem;
            color: var(--po-gray-400);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: var(--po-accent);
            color: #fff;
            font-size: .825rem;
            font-weight: 600;
            padding: .6rem 1.2rem;
            border-radius: var(--po-radius-sm);
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background .18s, box-shadow .18s, transform .12s;
            box-shadow: 0 2px 8px rgba(124, 58, 237, .3);
        }

        .btn-primary:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(124, 58, 237, .35);
        }

        /* ── Stats ── */
        .po-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .po-stat {
            background: var(--po-white);
            border: 1px solid var(--po-gray-200);
            border-radius: var(--po-radius);
            padding: 1.1rem 1.25rem;
            box-shadow: var(--po-shadow-sm);
            display: flex;
            align-items: center;
            gap: .875rem;
            transition: box-shadow .18s;
        }

        .po-stat:hover {
            box-shadow: var(--po-shadow);
        }

        .po-stat__icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .po-stat__icon--purple {
            background: var(--po-accent-lt);
            color: var(--po-accent);
        }

        .po-stat__icon--green {
            background: var(--po-success-lt);
            color: var(--po-success);
        }

        .po-stat__icon--gray {
            background: var(--po-gray-100);
            color: var(--po-gray-500);
        }

        .po-stat__icon--red {
            background: var(--po-danger-lt);
            color: var(--po-danger);
        }

        .po-stat__val {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--po-gray-800);
            line-height: 1;
        }

        .po-stat__label {
            font-size: .72rem;
            color: var(--po-gray-400);
            font-weight: 500;
            margin-top: .2rem;
        }

        /* ── Toolbar ── */
        .po-toolbar {
            display: flex;
            gap: .625rem;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
        }

        .po-search {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .po-search svg {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--po-gray-400);
            pointer-events: none;
        }

        .po-search input {
            width: 100%;
            border: 1px solid var(--po-gray-200);
            background: var(--po-white);
            color: var(--po-gray-700);
            padding: .6rem 1rem .6rem 2.4rem;
            border-radius: var(--po-radius-sm);
            font-size: .825rem;
            transition: border-color .18s, box-shadow .18s;
            box-shadow: var(--po-shadow-sm);
        }

        .po-search input::placeholder {
            color: var(--po-gray-400);
        }

        .po-search input:focus {
            outline: none;
            border-color: var(--po-accent);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, .12);
        }

        .po-select {
            border: 1px solid var(--po-gray-200);
            background: var(--po-white);
            color: var(--po-gray-600);
            padding: .6rem 2rem .6rem .875rem;
            border-radius: var(--po-radius-sm);
            font-size: .825rem;
            cursor: pointer;
            box-shadow: var(--po-shadow-sm);
            transition: border-color .18s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .75rem center;
        }

        .po-select:focus {
            outline: none;
            border-color: var(--po-accent);
        }

        .btn-filter {
            border: 1px solid var(--po-gray-200);
            background: var(--po-white);
            color: var(--po-gray-600);
            padding: .6rem 1rem;
            border-radius: var(--po-radius-sm);
            font-size: .8rem;
            cursor: pointer;
            box-shadow: var(--po-shadow-sm);
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            text-decoration: none;
        }

        .btn-filter:hover {
            border-color: var(--po-accent);
            color: var(--po-accent);
        }

        .btn-filter--reset:hover {
            border-color: var(--po-danger);
            color: var(--po-danger);
        }

        /* ── Card Grid ── */
        .po-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 1.1rem;
        }

        /* ── Card ── */
        .po-card {
            background: var(--po-white);
            border: 1px solid var(--po-gray-200);
            border-radius: var(--po-radius);
            overflow: hidden;
            box-shadow: var(--po-shadow-sm);
            transition: box-shadow .2s, transform .2s, border-color .2s;
            display: flex;
            flex-direction: column;
        }

        .po-card:hover {
            box-shadow: var(--po-shadow-md);
            transform: translateY(-2px);
            border-color: var(--po-gray-300);
        }

        .po-card.is-inactive {
            opacity: .65;
        }

        /* Thumbnail */
        .po-card__thumb {
            position: relative;
            height: 148px;
            background: var(--po-gray-100);
            overflow: hidden;
            flex-shrink: 0;
        }

        .po-card__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .3s;
        }

        .po-card:hover .po-card__thumb img {
            transform: scale(1.04);
        }

        .po-card__thumb-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--po-gray-300);
        }

        .po-card__badge {
            position: absolute;
            top: .625rem;
            left: .625rem;
            background: var(--po-accent);
            color: #fff;
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .22rem .6rem;
            border-radius: 20px;
        }

        /* Badge warn & success variants */
        .po-card__badge--warn {
            background: var(--po-warn);
        }

        .po-card__badge--success {
            background: var(--po-success);
        }

        .po-status-pill {
            position: absolute;
            top: .625rem;
            right: .625rem;
            font-size: .65rem;
            font-weight: 600;
            padding: .22rem .65rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .po-status-pill.on {
            background: var(--po-success-lt);
            color: var(--po-success);
        }

        .po-status-pill.off {
            background: var(--po-gray-100);
            color: var(--po-gray-500);
        }

        .po-status-pill::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Card Body */
        .po-card__body {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .po-card__name {
            font-size: .925rem;
            font-weight: 700;
            color: var(--po-gray-800);
            letter-spacing: -.015em;
            margin-bottom: .2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .po-card__meta {
            font-size: .72rem;
            color: var(--po-gray-400);
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-bottom: .875rem;
        }

        .po-card__meta span {
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        /* Pricing */
        .po-price-row {
            display: flex;
            align-items: baseline;
            gap: .5rem;
            margin-bottom: .25rem;
        }

        .po-price-main {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--po-accent);
            letter-spacing: -.02em;
        }

        .po-price-orig {
            font-size: .775rem;
            color: var(--po-gray-400);
            text-decoration: line-through;
        }

        .po-saving-chip {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            background: var(--po-success-lt);
            color: var(--po-success);
            font-size: .67rem;
            font-weight: 700;
            padding: .18rem .55rem;
            border-radius: 6px;
            margin-bottom: .75rem;
            width: fit-content;
        }

        /* Time badge */
        .po-time {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .7rem;
            color: var(--po-gray-400);
            margin-bottom: .75rem;
        }

        /* Item chips */
        .po-chips-wrap {
            margin-bottom: .875rem;
            flex: 1;
        }

        .po-chips-label {
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 600;
            color: var(--po-gray-400);
            margin-bottom: .35rem;
        }

        .po-chips {
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
        }

        .po-chip {
            background: var(--po-gray-100);
            border: 1px solid var(--po-gray-200);
            color: var(--po-gray-600);
            font-size: .68rem;
            padding: .18rem .55rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .po-chip__qty {
            background: var(--po-accent-lt);
            color: var(--po-accent);
            font-weight: 700;
            font-size: .6rem;
            padding: .1rem .35rem;
            border-radius: 4px;
        }

        .po-chip-more {
            background: transparent;
            border: 1px dashed var(--po-gray-300);
            color: var(--po-gray-400);
        }

        /* Card actions */
        .po-card__actions {
            display: flex;
            gap: .4rem;
            padding-top: .875rem;
            border-top: 1px solid var(--po-gray-100);
        }

        .btn-act {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            padding: .45rem .3rem;
            border-radius: var(--po-radius-sm);
            font-size: .74rem;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .btn-act--detail {
            background: var(--po-accent-lt);
            color: var(--po-accent);
            border-color: #ddd6fe;
        }

        .btn-act--detail:hover {
            background: #ddd6fe;
        }

        .btn-act--edit {
            background: var(--po-warn-lt);
            color: var(--po-warn);
            border-color: #fde68a;
        }

        .btn-act--edit:hover {
            background: #fde68a;
        }

        .btn-act--toggle {
            background: var(--po-gray-100);
            color: var(--po-gray-500);
            border-color: var(--po-gray-200);
            flex: none;
            padding: .45rem .6rem;
        }

        .btn-act--toggle:hover {
            background: var(--po-gray-200);
        }

        .btn-act--del {
            background: var(--po-danger-lt);
            color: var(--po-danger);
            border-color: #fecaca;
        }

        .btn-act--del:hover {
            background: #fecaca;
        }

        /* ── Flash Alert ── */
        .po-flash {
            display: flex;
            align-items: center;
            gap: .625rem;
            padding: .875rem 1rem;
            border-radius: var(--po-radius-sm);
            font-size: .825rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            animation: fadeDown .3s ease;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .po-flash--ok {
            background: var(--po-success-lt);
            border: 1px solid #bbf7d0;
            color: var(--po-success);
        }

        .po-flash--err {
            background: var(--po-danger-lt);
            border: 1px solid #fecaca;
            color: var(--po-danger);
        }

        /* ── Empty State ── */
        .po-empty {
            text-align: center;
            padding: 5rem 2rem;
        }

        .po-empty__icon {
            width: 64px;
            height: 64px;
            background: var(--po-gray-100);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            color: var(--po-gray-300);
        }

        .po-empty h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--po-gray-700);
            margin-bottom: .4rem;
        }

        .po-empty p {
            font-size: .825rem;
            color: var(--po-gray-400);
            margin-bottom: 1.25rem;
        }

        /* ── Toast ── */
        .po-toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: var(--po-gray-800);
            color: #fff;
            font-size: .8rem;
            font-weight: 500;
            padding: .75rem 1.25rem;
            border-radius: var(--po-radius-sm);
            box-shadow: var(--po-shadow-md);
            z-index: 9999;
            animation: fadeDown .25s ease;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .po-toast--ok .po-toast-dot {
            background: var(--po-success);
        }

        .po-toast--err .po-toast-dot {
            background: var(--po-danger);
        }

        .po-toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media(max-width:768px) {
            .po-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .po-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="po-page">

        {{-- Flash --}}
        @if (session('success'))
            <div class="po-flash po-flash--ok">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="po-flash po-flash--err">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="po-header">
            <div>
                <h1 class="po-header__title">Penawaran Harga</h1>
                <p class="po-header__sub">Kelola paket bundling &amp; penawaran spesial untuk QR Menu</p>
            </div>
            <a href="{{ route('price-offers.create') }}" class="btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Buat Paket Baru
            </a>
        </div>

        {{-- Stats --}}
        <div class="po-stats">
            <div class="po-stat">
                <div class="po-stat__icon po-stat__icon--purple">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                        <path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z" />
                    </svg>
                </div>
                <div>
                    <div class="po-stat__val">{{ $stats['total'] }}</div>
                    <div class="po-stat__label">Total Paket</div>
                </div>
            </div>
            <div class="po-stat">
                <div class="po-stat__icon po-stat__icon--green">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div>
                    <div class="po-stat__val" style="color:var(--po-success)">{{ $stats['active'] }}</div>
                    <div class="po-stat__label">Aktif</div>
                </div>
            </div>
            <div class="po-stat">
                <div class="po-stat__icon po-stat__icon--gray">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                </div>
                <div>
                    <div class="po-stat__val" style="color:var(--po-gray-500)">{{ $stats['inactive'] }}</div>
                    <div class="po-stat__label">Nonaktif</div>
                </div>
            </div>
            <div class="po-stat">
                <div class="po-stat__icon po-stat__icon--red">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14H6L5 6" />
                    </svg>
                </div>
                <div>
                    <div class="po-stat__val" style="color:var(--po-danger)">{{ $stats['trashed'] }}</div>
                    <div class="po-stat__label">Dihapus</div>
                </div>
            </div>
        </div>

        {{-- Toolbar --}}
        <form method="GET" action="{{ route('price-offers.index') }}">
            <div class="po-toolbar">
                <div class="po-search">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" name="search" placeholder="Cari nama, kategori, badge…"
                        value="{{ request('search') }}">
                </div>

                <select name="category" class="po-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') == $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="status" class="po-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') == 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Nonaktif</option>
                </select>

                <button type="submit" class="btn-filter">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    Filter
                </button>

                @if (request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('price-offers.index') }}" class="btn-filter btn-filter--reset">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Content --}}
        @if ($priceOffers->isEmpty())
            <div class="po-empty">
                <div class="po-empty__icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                        <path d="M16 3H8a2 2 0 0 0-2 2v2h12V5a2 2 0 0 0-2-2z" />
                    </svg>
                </div>
                <h3>Belum ada paket</h3>
                <p>Mulai buat paket bundling untuk ditampilkan di QR Menu</p>
                <a href="{{ route('price-offers.create') }}" class="btn-primary">Buat Paket Pertama</a>
            </div>
        @else
            <div class="po-grid">
                @foreach ($priceOffers as $offer)
                    <div class="po-card {{ !$offer->is_active ? 'is-inactive' : '' }}">

                        {{-- Thumbnail --}}
                        <div class="po-card__thumb">
                            @if ($offer->image_url)
                                <img src="{{ $offer->image_url }}" alt="{{ $offer->name }}" loading="lazy">
                            @else
                                <div class="po-card__thumb-empty">
                                    <svg width="36" height="36" fill="none" stroke="currentColor"
                                        stroke-width="1" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                </div>
                            @endif

                            @if ($offer->badge)
                                <span class="po-card__badge">{{ $offer->badge }}</span>
                            @endif

                            <span class="po-status-pill {{ $offer->is_active ? 'on' : 'off' }}">
                                {{ $offer->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="po-card__body">
                            <div class="po-card__name" title="{{ $offer->name }}">{{ $offer->name }}</div>
                            <div class="po-card__meta">
                                @if ($offer->category)
                                    <span>
                                        <svg width="10" height="10" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                        {{ $offer->category }}
                                    </span>
                                @endif
                                <span>
                                    <svg width="10" height="10" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <line x1="8" y1="6" x2="21" y2="6" />
                                        <line x1="8" y1="12" x2="21" y2="12" />
                                        <line x1="8" y1="18" x2="21" y2="18" />
                                        <line x1="3" y1="6" x2="3.01" y2="6" />
                                    </svg>
                                    {{ $offer->items_count ?? $offer->items->count() }} item
                                </span>
                            </div>

                            {{-- Harga --}}
                            <div class="po-price-row">
                                <span class="po-price-main">{{ $offer->formatted_package_price }}</span>
                                @if ((float) $offer->original_price > 0 && $offer->original_price != $offer->package_price)
                                    <span class="po-price-orig">{{ $offer->formatted_original_price }}</span>
                                @endif
                            </div>
                            @if ($offer->saving_amount > 0)
                                <div class="po-saving-chip">
                                    <svg width="9" height="9" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                    </svg>
                                    Hemat {{ $offer->saving_percent }}% · Rp
                                    {{ number_format($offer->saving_amount, 0, ',', '.') }}
                                </div>
                            @endif

                            {{-- Items --}}
                            @if ($offer->relationLoaded('items') && $offer->items->count())
                                <div class="po-chips-wrap">
                                    <div class="po-chips-label">Isi Paket</div>
                                    <div class="po-chips">
                                        @foreach ($offer->items->take(3) as $item)
                                            <span class="po-chip">
                                                <span class="po-chip__qty">{{ $item->quantity }}×</span>
                                                {{ $item->item_name }}
                                            </span>
                                        @endforeach
                                        @if ($offer->items->count() > 3)
                                            <span class="po-chip po-chip-more">+{{ $offer->items->count() - 3 }}
                                                lagi</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Jam tersedia --}}
                            @if ($offer->available_from && $offer->available_until)
                                <div class="po-time">
                                    <svg width="11" height="11" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    {{ substr($offer->available_from, 0, 5) }} –
                                    {{ substr($offer->available_until, 0, 5) }}
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="po-card__actions">
                                <a href="{{ route('price-offers.show', $offer) }}" class="btn-act btn-act--detail">
                                    <svg width="12" height="12" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    Detail
                                </a>
                                <a href="{{ route('price-offers.edit', $offer) }}" class="btn-act btn-act--edit">
                                    <svg width="12" height="12" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Edit
                                </a>
                                <button type="button" class="btn-act btn-act--toggle js-toggle"
                                    data-id="{{ $offer->id }}"
                                    title="{{ $offer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    @if ($offer->is_active)
                                        <svg width="13" height="13" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="1" y="5" width="22" height="14" rx="7" />
                                            <circle cx="16" cy="12" r="3" fill="currentColor" />
                                        </svg>
                                    @else
                                        <svg width="13" height="13" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="1" y="5" width="22" height="14" rx="7" />
                                            <circle cx="8" cy="12" r="3" fill="currentColor" />
                                        </svg>
                                    @endif
                                </button>
                                <form method="POST" action="{{ route('price-offers.destroy', $offer) }}"
                                    onsubmit="return confirm('Hapus paket «{{ addslashes($offer->name) }}»?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-act btn-act--del" style="width:100%">
                                        <svg width="12" height="12" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14H6L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($priceOffers->hasPages())
                <div style="margin-top:1.5rem">{{ $priceOffers->links() }}</div>
            @endif
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.js-toggle').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.dataset.id;
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                btn.disabled = true;
                try {
                    const res = await fetch(`/admin/price-offers/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Content-Type': 'application/json'
                        },
                    });
                    const data = await res.json();
                    if (data.success) {
                        const card = btn.closest('.po-card');
                        const pill = card.querySelector('.po-status-pill');
                        if (data.is_active) {
                            card.classList.remove('is-inactive');
                            pill.className = 'po-status-pill on';
                            pill.textContent = 'Aktif';
                        } else {
                            card.classList.add('is-inactive');
                            pill.className = 'po-status-pill off';
                            pill.textContent = 'Nonaktif';
                        }
                        showToast(data.message, data.is_active ? 'ok' : 'err');
                    }
                } finally {
                    btn.disabled = false;
                }
            });
        });

        function showToast(msg, type) {
            const t = document.createElement('div');
            t.className = `po-toast po-toast--${type}`;
            t.innerHTML = `<span class="po-toast-dot"></span>${msg}`;
            document.body.appendChild(t);
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transition = 'opacity .3s';
                setTimeout(() => t.remove(), 300);
            }, 2800);
        }
    </script>
@endpush
