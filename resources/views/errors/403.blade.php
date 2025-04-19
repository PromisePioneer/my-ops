@extends('layouts.template')
@section('content')

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-center flex-column-fluid">
            <div class="d-flex flex-column flex-center text-center p-10">
                <div class="card card-flush w-lg-650px py-5">
                    <div class="card-body py-15 py-lg-20">
                        <h1 class="fw-bolder fs-2hx text-gray-900 mb-4">Oops!</h1>
                        <div class="fw-semibold fs-6 mb-7">{{ $exception->getMessage() }}</div>

                        <div class="mb-3">
                            <img src="{{ asset('assets/media/auth/403-error.png')}}"
                                 class="mw-100 mh-300px theme-light-show" alt=""/>
                            <img src="{{ asset('assets/media/auth/403-error-dark.png')}}"
                                 class="mw-100 mh-300px theme-dark-show" alt=""/>
                        </div>

                        <div class="mb-0">
                            <a href="{{ url('/home') }}" class="btn btn-sm btn-primary">Return Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection