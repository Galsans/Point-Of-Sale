{{-- resources/views/admin/price-offers/show.blade.php --}}
@extends('layouts.app')

@section('title', $priceOffer->name)

@push('styles')
    <style>
        :root {
            --ps-accent: #7c3aed;
            --ps-accent-lt: #ede9fe;
            --ps-success: #16a34a;
            --ps-success-lt: #dcfce7;
            --ps-danger: #dc2626;
            --ps-danger-lt: #fee2e2;
            --ps-warn: #d97706;
            --ps-warn-lt: #fef3c7;
            --ps-gray-50: #f9fafb;
            --ps-gray-100: #f3f4f6;
            --ps-gray-200: #e5e7eb;
            --ps-gray-300: #d1d5db;
            --ps-gray-400: #9ca3af;
            --ps-gray-500: #6b7280;
            --ps-gray-600: #4b5563;
            --ps-gray-700: #374151;
            --ps-gray-800: #1f2937;
            --ps-white: #ffffff;
            --ps-r: 10px;
            --ps-r-sm: 7px;
            --ps-shadow-sm: 0 1px 3px rgba(0, 0, 0, .07), 0 1px 2px rgba(0, 0, 0, .04);
            --ps-shadow: 0 4px 16px rgba(0, 0, 0, .08), 0 1px 4px rgba(0, 0, 0, .04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* breadcrumb */
        .ps-bc {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .78rem;
            color: var(--ps-gray-400);
            margin-bottom: 1.25rem;
        }

        .ps-bc a {
            color: var(--ps-gray-500);
            text-decoration: none;
        }

        .ps-bc a:hover {
            color: var(--ps-accent);
        }

        /* header */
        .ps-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .ps-head__name {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--ps-gray-800);
            letter-spacing: -.025em;
            margin-bottom: .4rem;
        }

        .ps-head__pills {
            display: flex;
            gap: .4rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .pill {
            font-size: .65rem;
            font-weight: 600;
            padding: .22rem .65rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .pill::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .pill--accent {
            background: var(--ps-accent);
            color: #fff;
        }

        .pill--accent::before {
            display: none;
        }

        .pill--green {
            background: var(--ps-success-lt);
            color: var(--ps-success);
        }

        .pill--gray {
            background: var(--ps-gray-100);
            color: var(--ps-gray-500);
        }

        .pill--purple {
            background: var(--ps-accent-lt);
            color: var(--ps-accent);
        }

        /* action buttons */
        .ps-actions {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .btn-solid {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--ps-accent);
            color: #fff;
            font-weight: 600;
            font-size: .8rem;
            padding: .6rem 1.1rem;
            border-radius: var(--ps-r-sm);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(124, 58, 237, .25);
            transition: background .15s, transform .12s;
        }

        .btn-solid:hover {
            background: #6d28d9;
            transform: translateY(-1px);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--ps-white);
            border: 1px solid var(--ps-gray-200);
            color: var(--ps-gray-600);
            font-size: .8rem;
            padding: .6rem 1.1rem;
            border-radius: var(--ps-r-sm);
            text-decoration: none;
            cursor: pointer;
            box-shadow: var(--ps-shadow-sm);
            transition: all .15s;
        }

        .btn-outline:hover {
            border-color: var(--ps-gray-400);
            color: var(--ps-gray-800);
        }

        .btn-outline--danger:hover {
            border-color: var(--ps-danger);
            color: var(--ps-danger);
            background: var(--ps-danger-lt);
        }

        /* layout */
        .ps-layout {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 1.25rem;
            align-items: start;
        }

        @media(max-width:900px) {
            .ps-layout {
                grid-template-columns: 1fr;
            }
        }

        /* panel */
        .ps-panel {
            background: var(--ps-white);
            border: 1px solid var(--ps-gray-200);
            border-radius: var(--ps-r);
            box-shadow: var(--ps-shadow-sm);
            overflow: hidden;
            margin-bottom: 1.1rem;
        }

        .ps-panel:last-child {
            margin-bottom: 0;
        }

        .ps-panel__hd {
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--ps-gray-100);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--ps-gray-400);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ps-panel__bd {
            padding: 1.25rem;
        }

        /* pricing hero card */
        .ps-pricing {
            background: linear-gradient(135deg, var(--ps-accent) 0%, #5b21b6 100%);
            border-radius: var(--ps-r);
            padding: 1.5rem;
            margin-bottom: 1.1rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(124, 58, 237, .3);
        }

        .ps-pricing::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .ps-pricing__label {
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .1em;
            opacity: .75;
            margin-bottom: .4rem;
        }

        .ps-pricing__main {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1;
            margin-bottom: .25rem;
        }

        .ps-pricing__orig {
            font-size: .85rem;
            opacity: .65;
            text-decoration: line-through;
        }

        .ps-saving-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .75rem;
            border-radius: 20px;
            margin-top: .875rem;
        }

        /* items table */
        .ps-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ps-table th {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--ps-gray-400);
            padding: .6rem 1rem;
            text-align: left;
            background: var(--ps-gray-50);
            border-bottom: 1px solid var(--ps-gray-100);
        }

        .ps-table td {
            padding: .8rem 1rem;
            border-bottom: 1px solid var(--ps-gray-50);
            font-size: .825rem;
            color: var(--ps-gray-700);
            vertical-align: middle;
        }

        .ps-table tr:last-child td {
            border-bottom: none;
        }

        .ps-table tr:hover td {
            background: var(--ps-gray-50);
        }

        .td-num {
            color: var(--ps-gray-400);
            font-size: .75rem;
            width: 36px;
        }

        .td-qty span {
            background: var(--ps-accent-lt);
            color: var(--ps-accent);
            font-weight: 700;
            font-size: .72rem;
            padding: .18rem .55rem;
            border-radius: 6px;
        }

        .td-price {
            color: var(--ps-accent);
            font-weight: 600;
            text-align: right;
            white-space: nowrap;
        }

        .td-sub {
            font-weight: 700;
            color: var(--ps-gray-800);
            text-align: right;
            white-space: nowrap;
        }

        .ps-table tfoot td {
            padding: .8rem 1rem;
            background: var(--ps-gray-50);
            border-top: 2px solid var(--ps-gray-200);
            font-weight: 700;
        }

        /* info list */
        .ps-info {
            display: flex;
            flex-direction: column;
        }

        .ps-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap.5rem;
            padding: .6rem 0;
            border-bottom: 1px solid var(--ps-gray-50);
            font-size: .8rem;
        }

        .ps-info-row:last-child {
            border-bottom: none;
        }

        .ps-info-row__k {
            color: var(--ps-gray-400);
            flex-shrink: 0;
        }

        .ps-info-row__v {
            color: var(--ps-gray-700);
            font-weight: 500;
            text-align: right;
        }

        /* image */
        .ps-img {
            width: 100%;
            border-radius: var(--ps-r-sm);
            object-fit: cover;
            max-height: 200px;
            display: block;
        }

        .ps-img-empty {
            background: var(--ps-gray-100);
            border-radius: var(--ps-r-sm);
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ps-gray-300);
        }

        /* desc */
        .ps-desc {
            font-size: .825rem;
            color: var(--ps-gray-500);
            line-height: 1.7;
        }

        /* time badge */
        .ps-time {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--ps-accent-lt);
            color: var(--ps-accent);
            font-size: .75rem;
            font-weight: 600;
            padding: .3rem .75rem;
            border-radius: 20px;
        }

        @media(max-width:768px) {
            .ps-head {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div style="padding:.25rem 0 2rem">

        {{-- Breadcrumb --}}
        <div class="ps-bc">
            <a href="{{ route('price-offers.index') }}">Penawaran Harga</a>
            <span style="color:var(--ps-gray-300)">›</span>
            <span>{{ $priceOffer->name }}</span>
        </div>

        {{-- Header --}}
        <div class="ps-head">
            <div>
                <div class="ps-head__name">{{ $priceOffer->name }}</div>
                <div class="ps-head__pills">
                    @if ($priceOffer->badge)
                        <span class="pill pill--accent">{{ $priceOffer->badge }}</span>
                    @endif
                    <span class="pill {{ $priceOffer->is_active ? 'pill--green' : 'pill--gray' }}">
                        {{ $priceOffer->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    @if ($priceOffer->category)
                        <span class="pill pill--purple">{{ $priceOffer->category }}</span>
                    @endif
                </div>
            </div>
            <div class="ps-actions">
                <a href="{{ route('price-offers.edit', $priceOffer) }}" class="btn-solid">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('price-offers.index') }}" class="btn-outline">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Kembali
                </a>
                <form method="POST" action="{{ route('price-offers.destroy', $priceOffer) }}"
                    onsubmit="return confirm('Hapus paket ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-outline btn-outline--danger">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14H6L5 6" />
                            <path d="M10 11v6M14 11v6" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="ps-layout">

            {{-- ── LEFT ── --}}
            <div>

                {{-- Pricing hero --}}
                <div class="ps-pricing">
                    <div class="ps-pricing__label">Harga Paket Bundling</div>
                    <div class="ps-pricing__main">{{ $priceOffer->formatted_package_price }}</div>
                    @if ((float) $priceOffer->original_price > 0)
                        <div class="ps-pricing__orig">Normal: {{ $priceOffer->formatted_original_price }}</div>
                    @endif
                    @if ($priceOffer->saving_amount > 0)
                        <div class="ps-saving-chip">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                            </svg>
                            Hemat {{ $priceOffer->saving_percent }}% · Rp
                            {{ number_format($priceOffer->saving_amount, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                {{-- Items table --}}
                <div class="ps-panel">
                    <div class="ps-panel__hd">
                        <span>Isi Paket</span>
                        <span
                            style="background:var(--ps-accent-lt);color:var(--ps-accent);font-size:.68rem;font-weight:700;padding:.15rem .55rem;border-radius:20px;letter-spacing:0">
                            {{ $priceOffer->items->count() }} item
                        </span>
                    </div>
                    <div style="padding:0">
                        <table class="ps-table">
                            <thead>
                                <tr>
                                    <th class="td-num">#</th>
                                    <th>Menu</th>
                                    <th>Kategori</th>
                                    <th style="text-align:center">Qty</th>
                                    <th style="text-align:right">Harga Satuan</th>
                                    <th style="text-align:right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($priceOffer->items as $i => $item)
                                    <tr>
                                        <td class="td-num">{{ $i + 1 }}</td>
                                        <td style="font-weight:600;color:var(--ps-gray-800)">{{ $item->item_name }}</td>
                                        <td style="color:var(--ps-gray-400);font-size:.78rem">{{ $item->category ?? '—' }}
                                        </td>
                                        <td style="text-align:center"><span>{{ $item->quantity }}×</span></td>
                                        <td class="td-price">{{ $item->formatted_item_price }}</td>
                                        <td class="td-sub">{{ $item->formatted_subtotal }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="text-align:right;color:var(--ps-gray-500);font-size:.78rem">
                                        Total Harga Satuan
                                    </td>
                                    <td class="td-sub" style="color:var(--ps-accent);font-size:.925rem">
                                        Rp
                                        {{ number_format($priceOffer->items->sum(fn($i) => $i->subtotal), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Description --}}
                @if ($priceOffer->description)
                    <div class="ps-panel">
                        <div class="ps-panel__hd"><span>Deskripsi</span></div>
                        <div class="ps-panel__bd">
                            <p class="ps-desc">{{ $priceOffer->description }}</p>
                        </div>
                    </div>
                @endif

            </div>{{-- /left --}}

            {{-- ── RIGHT ── --}}
            <div>

                {{-- Foto --}}
                <div class="ps-panel">
                    <div class="ps-panel__hd"><span>Foto Paket</span></div>
                    <div class="ps-panel__bd">
                        @if ($priceOffer->image_url)
                            <img src="{{ $priceOffer->image_url }}" alt="{{ $priceOffer->name }}" class="ps-img">
                        @else
                            <div class="ps-img-empty">
                                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info --}}
                <div class="ps-panel">
                    <div class="ps-panel__hd"><span>Detail Info</span></div>
                    <div class="ps-panel__bd">
                        <div class="ps-info">
                            <div class="ps-info-row">
                                <span class="ps-info-row__k">Status</span>
                                <span class="ps-info-row__v">
                                    <span class="pill {{ $priceOffer->is_active ? 'pill--green' : 'pill--gray' }}">
                                        {{ $priceOffer->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-row__k">Tersedia Sekarang</span>
                                <span class="ps-info-row__v">
                                    @if ($priceOffer->is_available_now)
                                        <span style="color:var(--ps-success);font-size:.8rem">✓ Ya</span>
                                    @else
                                        <span style="color:var(--ps-danger);font-size:.8rem">✗ Tidak</span>
                                    @endif
                                </span>
                            </div>
                            @if ($priceOffer->available_from && $priceOffer->available_until)
                                <div class="ps-info-row">
                                    <span class="ps-info-row__k">Jam Tersedia</span>
                                    <span class="ps-info-row__v">
                                        <span class="ps-time">
                                            <svg width="11" height="11" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            {{ substr($priceOffer->available_from, 0, 5) }} –
                                            {{ substr($priceOffer->available_until, 0, 5) }}
                                        </span>
                                    </span>
                                </div>
                            @endif
                            <div class="ps-info-row">
                                <span class="ps-info-row__k">Sort Order</span>
                                <span class="ps-info-row__v">{{ $priceOffer->sort_order }}</span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-row__k">Dibuat</span>
                                <span class="ps-info-row__v"
                                    style="font-size:.75rem">{{ $priceOffer->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-row__k">Diperbarui</span>
                                <span class="ps-info-row__v"
                                    style="font-size:.75rem">{{ $priceOffer->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /right --}}

        </div>
    </div>
@endsection
