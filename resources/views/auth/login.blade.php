@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column flex-root" x-data="userLogin">
        <div
            class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url({{ asset('assets/media/illustrations/sketchy-1/14.png')}})">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <a href="#" class="mb-12">
                    <img alt="Logo" src="{{ asset('assets/media/logos/mayatama-logo-full.png')}}" class="h-70px"/>
                </a>
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <form id="form" @submit.prevent="login()">
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">NIK (Nomor Induk Karyawan) atau
                                Email</label>
                            <input id="email" type="text" class="form-control form-control-solid" name="email"
                                   value="{{ old('email') }}"
                                   required autofocus>
                            @error('nip')
                            <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                            @enderror
                        </div>
                        <div class="fv-row mb-2">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                            </div>

                            <div class="position-relative mb-3">
                                <input
                                    class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                                    type="password" name="password" id="password" autocomplete="off"/>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <span @click="clickToSeePassword()" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                      data-kt-password-meter-control="visibility">
                    <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span
                            class="path3"></span><span class="path4"></span></i>
                    <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span
                            class="path3"></span></i>
            </span>
                            </div>
                        </div>
                        <div class="fv-row mb-10 float-end">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault"
                                       @click="clickToSeePassword()"/>
                                <label class="form-check-label fw-bold" for="flexCheckDefault">
                                    Lihat Password
                                </label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button :disabled="buttonLoading" type="submit" id="kt_sign_in_submit"
                                    class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label"
                                      x-text="buttonLoading ? 'Loading...' : 'Lanjutkan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function userLogin() {
            return {
                buttonLoading: false,
                form: document.getElementById('form'),
                async login() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/login', new FormData(this.form));
                        await showAlert('success', 'Login Berhasil')
                        window.location.href = '/home';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                clickToSeePassword() {
                    const x = document.getElementById("password");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                }
            }
        }
    </script>
@endpush
