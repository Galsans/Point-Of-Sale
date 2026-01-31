@extends('layouts.app')

@section('title', 'Option Management')

@section('content')
    <style>
        .optionGroup-tabs {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: none;
            /* Firefox */
        }

        .optionGroup-tabs::-webkit-scrollbar {
            display: none;
            /* Chrome */
        }

        .optionGroup-tab {
            border: none;
            background: #f3f4f6;
            color: #555;
            padding: 8px 18px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.25s ease;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .optionGroup-tab:hover {
            background: #e0e7ff;
            color: #1e40af;
            transform: translateY(-1px);
        }

        /* ACTIVE TAB */
        .optionGroup-tab.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
        }

        /* MOBILE FRIENDLY */
        @media (max-width: 576px) {
            .optionGroup-tab {
                font-size: 0.8rem;
                padding: 7px 14px;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-food"></i>
            </span>
            Option (Pilihan Menu)
        </h3>

        <a href="{{ route('options.create') }}" class="btn btn-gradient-primary btn-sm">
            + Tambah Option
        </a>
    </div>

    <ul class="nav optionGroup-tabs mb-4" id="categoryTabs">
        <li class="nav-item">
            <button class="optionGroup-tab active" data-option-group="">
                Semua
            </button>
        </li>

        @foreach ($optionGroups as $item)
            <li class="nav-item">
                <button class="optionGroup-tab" data-option-group="{{ $item->id }}">
                    {{ $item->name }}
                </button>
            </li>
        @endforeach
    </ul>


    <div class="row" id="table-wrapper">
        @include('admin.option._items')
    </div>

    {{-- LOADING --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    @include('admin.option.script')

@endsection
