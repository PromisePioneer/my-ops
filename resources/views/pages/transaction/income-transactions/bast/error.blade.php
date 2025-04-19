@extends('layouts.template')
@section('page-title', 'Invoice Already Generated')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-15">
            <a href="#" class="mb-10 pt-lg-10">
                <img alt="Logo" src="{{ asset('assets/media/logos/mayatama-logo-full.png')}}" class="h-40px mb-5" />
            </a>
            <div class="pt-lg-10 mb-10">
                <h1 class="fw-bolder fs-2qx text-gray-800 mb-10">Invoice telah dibuat</h1>

                <div class="fw-bold fs-3 text-muted mb-15">BAST Telah dibuat,
                    <br />Siilahkan lihat di menu invoice</div>
                <div class="text-center">
                    <a href="{{ url('/income-transactions/bast') }}" class="btn btn-lg btn-primary fw-bolder">Kembali</a>
                </div>
            </div>
            <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/sketchy-1/17.png"></div>
        </div>
        <div class="d-flex flex-center flex-column-auto p-10">
            <div class="d-flex align-items-center fw-bold fs-6">
                <a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>
                <a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
                <a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
            </div>
        </div>
    </div>
@endsection
