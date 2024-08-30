@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')

    <div class="card mb-5 mb-xl-10" x-data="identityInformation">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">Informasi Identitas</h3>
            </div>
        </div>
        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">NIK</label>
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800" x-text="`${identityInformation.nik ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Tanggal Lahir</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.date_of_birth ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Tempat Lahir</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.place_of_birth ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Gender</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.gender ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Alamat</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.home_address ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Foto KTP</label>
                <div class="col-lg-8 fv-row">
                    <img :src="getImageURL(identityInformation?.ktp_attachment ?? null)"
                         @click="$dispatch('lightbox', `${getImageURL(identityInformation?.ktp_attachment) ?? null}`)"
                         alt="Foto Karyawan" class="w-100"/>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function identityInformation() {
            return {
                identityInformation: {},
                async init() {
                    const response = await axios.get('/utility/user-profile/identity-information/data');
                    this.identityInformation = response.data;
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        return "{{ asset('assets/media/placeholders/ktp.png') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
