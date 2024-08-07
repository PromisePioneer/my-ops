@extends('layouts.template')
@section('content')
    <div class="d-flex flex-column flex-xl-row" x-data="userDetailInformation()">
        @include('pages.manage-users.user.modal.detail.add-identity-information')
        @include('pages.manage-users.user.modal.detail.add-job-information')
        <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-center flex-column py-5">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            @if(isset($user->profile_pic) && $user->profile_pic)
                                <img src="{{ Storage::url($user->profile_pic) }}" alt="image"/>
                            @else
                                <img src="{{ asset('assets/media/dummy/dummy-picture.png') }}" alt="image"/>
                            @endif
                        </div>
                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3">{{ $user->name }}</a>
                        <div class="mb-9">
                            <div class="badge badge-lg badge-light-primary d-inline">
                                {{ collect($user->getRoleNames())->implode('')}}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
                             role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details
                            <span class="ms-2 rotate-180">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                         height="24" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="black">
                                        </path>
                                    </svg>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <div class="fw-bolder mt-5">Cabang</div>
                            <div class="text-gray-600">{{ $user->branch->name }}</div>
                            <div class="fw-bolder mt-5">NIK</div>
                            <div class="text-gray-600">{{ $user->nip }}</div>
                            <div class="fw-bolder mt-5">Email</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                            </div>
                            <div class="fw-bolder mt-5">Terakhir Login</div>
                            <div class="text-gray-600">{{ $user->last_login }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-lg-row-fluid ms-lg-15">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                       href="#identity_information">Informasi Identitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                       href="#job_information">Informasi Pekerjaan</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="identity_information" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header mt-6">
                            <div class="card-title flex-column">
                                <h2 class="mb-1">Informasi Identitas</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#identity-information-update-modal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-9 pt-4">
                            <div class="tab-content">
                                @include('pages.manage-users.user.partials.detail.identity-information-tab-content')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="job_information" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header mt-6">
                            <div class="card-title flex-column">
                                <h2 class="mb-1">Informasi Pekerjaan</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#job-information-update-modal" @click="add()">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-9 pt-4">
                            <div class="tab-content">
                                @include('pages.manage-users.user.partials.detail.job-information-tab-content')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header mt-6">
                        <div class="card-title flex-column">
                            <h2 class="mb-1">Absensi</h2>
                            <div class="fs-6 fw-bold text-muted">Pantau absensi karyawan</div>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Clock in</th>
                                <th class="min-w-125px">Clock Out</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && attendance.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="absent in attendance?.data" :key="absent.id">
                                <tr>
                                    <td x-text="absent.date"></td>
                                    <td x-text="absent.clock_in"></td>
                                    <td x-text="absent.clock_out"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function userDetailInformation() {
            return {
                isLoading: false,
                buttonLoading: false,
                userId: "{{ $user->id }}",
                jobInformation: {},
                identityInformation: {},
                bpjsKesStatus: false,
                bpjsKetStatus: false,
                attendance: [],
                marriedStatus: null,
                marriedData: [{name: "K/1"}, {name: "K/2"}, {name: "K/3"}],
                noMarriedData: [{name: "TK/1"}, {name: "TK/2"}, {name: "TK/3"}],
                identityInformationForm: document.getElementById('form-identity-information-update'),
                identityInformationModal: new bootstrap.Modal(document.getElementById('identity-information-update-modal')),
                jobInformationForm: document.getElementById('form-job-information-update'),
                jobInformationModal: new bootstrap.Modal(document.getElementById('job-information-update-modal')),
                async init() {
                    await this.getIdentityInformation();
                    await this.getJobInformation();
                    await this.getAbsentData();
                },
                async add() {
                    await this.getDepartmentData();
                    await this.getUserPlacementData();

                    if (Object.keys(this.jobInformation).length > 0) {
                        await this.selectedPlacement();
                        await this.selectedDepartment();
                    }
                },
                async identityInformationUpdate() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/users/identity-information/${this.userId}`, new FormData(this.identityInformationForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.identityInformationModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async jobInformationUpdate() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/users/job-information/${this.userId}`, new FormData(this.jobInformationForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.jobInformationModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getDepartmentData() {
                    $(".department-select2").select2({
                        ajax: {
                            url: '/manage-users/users/department/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getIdentityInformation() {
                    const resp = await axios.get(`/manage-users/users/identity-information/${this.userId}`);
                    this.identityInformation = resp.data;

                    this.marriedStatus = this.identityInformation.marital_status === 'menikah' ? 'menikah' : 'tidak menikah';
                },
                async getJobInformation() {
                    const resp = await axios.get(`/manage-users/users/job-information/${this.userId}`);
                    this.jobInformation = resp.data;

                    this.bpjsKesStatus = this.jobInformation.no_kis ? 'ya' : 'tidak';
                    this.bpjsKetStatus = this.jobInformation.no_kpj ? 'ya' : 'tidak';
                },
                async getAbsentData() {
                    const resp = await axios.get(`/manage-users/users/absent/data/${this.userId}`);
                    this.attendance = resp.data;
                },
                async getUserPlacementData() {
                    $(".user-placement-select2").select2({
                        ajax: {
                            url: '/manage-users/users/job-information/placement/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                selectedDepartment() {
                    const selectedDepartment = $('#selectedDepartment');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/manage-users/users/job-information/department/selected/${this.userId}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedDepartment.append(option).trigger('change');

                        selectedDepartment.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
                selectedPlacement() {
                    const selectedPlacement = $('#selectedPlacement');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/manage-users/users/job-information/placement/selected/${this.userId}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedPlacement.append(option).trigger('change');

                        selectedPlacement.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
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
