 @extends('layouts.app')
 @section('title', 'Buat Paket Baru')
 @section('content')
     @include('admin.priceOffer._form', ['isEdit' => false])
 @endsection
