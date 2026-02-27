{{-- edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit: ' . $priceOffer->name)
@section('content')
    @include('admin.priceOffer._form', [
        'isEdit' => true,
        'priceOffer' => $priceOffer,
    ])
@endsection
