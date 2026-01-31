@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span>
            Dashboard
        </h3>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <h4>Total Sales</h4>
                    <h2>Rp {{ number_format($totalSales ?? 0) }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
