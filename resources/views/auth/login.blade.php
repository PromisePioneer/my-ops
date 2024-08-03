@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div
            class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url(assets/media/illustrations/sketchy-1/14.png)">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <a href="#" class="mb-12">
                    <img alt="Logo" src="{{ asset('assets/media/logos/mayatama-logo-full.png')}}" class="h-70px"/>
                </a>
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('login') }}"
                          method="post">
                        @csrf
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">NIK (Nomor Induk Karyawan)</label>
                            <input
                                class="form-control form-control-lg form-control-solid @error('nip') is-invalid @enderror"
                                type="text" name="nip" autocomplete="off"/>
                            @error('nip')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="fv-row mb-10">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                            </div>
                            <input
                                class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                                type="password" name="password" autocomplete="off"/>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label">Lanjutkan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
