@extends('layouts.app')

@section('title', 'Order Management')

@section('content')

    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-receipt"></i>
            </span>
            Orders
        </h3>
    </div>

    {{-- ===================== --}}
    {{-- FILTER STATUS --}}
    {{-- ===================== --}}
    <div class="mb-4 d-flex flex-wrap gap-2">
        <div class="btn-group flex-wrap gap-2" role="group" aria-label="Filter Status" id="availableFilterGroup">
            <button type="button" class="btn btn-outline-primary btn-sm active mb-1 me-1" data-value="">Semua</button>
            <button type="button" class="btn btn-outline-warning btn-sm mb-1 me-1" data-value="pending">Pending</button>
            <button type="button" class="btn btn-outline-info btn-sm mb-1 me-1" data-value="paid">Paid</button>
            <button type="button" class="btn btn-outline-success btn-sm mb-1" data-value="completed">Completed</button>
            <button type="button" class="btn btn-outline-danger btn-sm mb-1" data-value="cancelled">Cancelled</button>
        </div>
    </div>

    {{-- 🔊 AUDIO NOTIFICATION --}}
    <audio id="orderSound" src="{{ asset('sounds/order.mp3') }}" preload="auto"></audio>
    <audio id="paymentSound" src="{{ asset('sounds/payment.mp3') }}" preload="auto"></audio>

    {{-- <button onclick="enableSound()" class="btn btn-sm btn-outline-secondary mb-3" id="enableSoundBtn">
        🔔 Aktifkan Notifikasi Suara
    </button> --}}

    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
        <button class="btn btn-sm btn-outline-secondary " id="enableSoundBtn">
            🔔 Aktifkan Notifikasi Suara
        </button>
        <span class="btn btn-sm btn-success d-flex align-items-center" id="reverbStatus">
            🟢 Real-time Active
        </span>
    </div>

    {{-- ===================== --}}
    {{-- ORDER LIST --}}
    {{-- ===================== --}}
    <div class="row" id="table-wrapper">
        @include('admin.order._items')
    </div>

    {{-- ===================== --}}
    {{-- LOADING --}}
    {{-- ===================== --}}
    <div class="text-center my-4 d-none" id="loading">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    {{-- SENTINEL --}}
    <div id="load-more-trigger" style="height: 1px;"></div>

    {{-- ===================== --}}
    {{-- MODAL UPDATE STATUS --}}
    {{-- ===================== --}}
    @include('admin.order.update')

    {{-- ===================== --}}
    {{-- MODAL ORDER DETAIL --}}
    @include('admin.order.show')

    {{-- ===================== --}}
    {{-- MODAL DELETE --}}
    {{-- ===================== --}}
    <div class="modal fade" id="deleteOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteOrderForm" method="POST" class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Order</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <p>Yakin ingin menghapus order:</p>
                    <h5 id="deleteOrderCode" class="text-danger"></h5>
                </div>

                <div class="modal-footer justify-content-center">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SCRIPT --}}
    {{-- ===================== --}}
    @include('admin.order.script')

@endsection
