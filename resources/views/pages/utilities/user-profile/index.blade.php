@extends('layouts.template')
@section('page-title', 'User Profile')
@section('content')

    <div x-data="usersData()">
        @include('pages.utilities.user-profile.partials.header')
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <div class="card-header cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bolder m-0">Detail Profil</h3>
                </div>
                <a href="{{ url('utility/user-profile/change-profile') }}"
                   class="btn btn-primary align-self-center btn-sm">Ubah
                    Password</a>
            </div>
            <div class="card-body p-9">
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Full Name</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ Auth::user()->name }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Email</label>

                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold text-gray-800 fs-6">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Cabang</label>
                    <div class="col-lg-8">
                        <span class="fw-bolder fs-6 text-gray-800">{{ Auth::user()->branch->name ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.utilities.user-profile.identity-information.index')
        @include('pages.utilities.user-profile.job-information.index')
    </div>

@endsection
@push('script')
    <script>
        function usersData() {
            return {
                identityInformation: {},
                jobInformation: {},
                userProfile: "{{ \Illuminate\Support\Facades\Auth::user()->profile_pic }}",
                async init() {
                    await this.getIdentityInformationData();
                    await this.getJobInformationData();
                },
                async getIdentityInformationData() {
                    const response = await axios.get('/utility/user-profile/identity-information/data');
                    this.identityInformation = response.data;
                },
                async getJobInformationData() {
                    const response = await axios.get('/utility/user-profile/job-information/data');
                    this.jobInformation = response.data;
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
