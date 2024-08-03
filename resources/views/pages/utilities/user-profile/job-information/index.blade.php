@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')

    <div class="card mb-5 mb-xl-10" x-data="jobInformation">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">Informasi Identitas</h3>
            </div>
        </div>
        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Departement</label>
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800" x-text="`${jobInformation.Department ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Tanggal Bergabung</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.join_date ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Gaji Pokok</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.fixed_salary ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Status Kontrak</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.contract_status ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">No. Rekening</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.bank_account_number ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">BPJS</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.bpjs ?? '-'}`"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function jobInformation() {
            return {
                jobInformation: {},
                async init() {
                    const response = await axios.get('/utility/user-profile/job-information/data');
                    this.jobInformation = response.data;
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = '/assets/media/placeholders/ktp.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
