@extends('layouts.template')
@section('page-title', 'Dashboard')
@section('breadcrumbs', 'Home')
@section('content')
    <div class="card-body p-10">
        <div class="card ">
            <div class="card-px text-center py-20 my-10">
                <h2 class="fs-2x fw-bold mb-10">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-gray-500 fs-4 fw-semibold mb-10"></p>
            </div>
            <div class="text-center px-4">
                <img class="mw-100 mh-300px" alt="" src="{{ asset('assets/media/illustrations/sketchy-1/2.png') }}"/>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
