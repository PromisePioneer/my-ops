@extends('layouts.template')
@section('content')
    <div class="d-flex flex-column flex-xl-row" x-data="userDetailInformation()">
        @include('pages.manage-users.user.partials.employee-data.identity-information.modal.create')
        @include('pages.manage-users.user.partials.employee-data.job-information.modal.create')
        @include('pages.manage-users.user.partials.employee-data.family-information.modal.create')
        @include('pages.manage-users.user.partials.employee-data.health-information.modal.create')
        @include('pages.manage-users.user.partials.education-and-experiences.education.modal.create')
        @include('pages.manage-users.user.partials.education-and-experiences.education-certificates.modal.create')
        @include('pages.manage-users.user.partials.education-and-experiences.education-certificates.modal.edit')
        @include('pages.manage-users.user.partials.education-and-experiences.job-experience.modal.create')
        @include('pages.manage-users.user.partials.education-and-experiences.job-experience.modal.edit')
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
                                   <i class="bi bi-chevron-up"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="separator"></div>
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <div class="fw-bolder mt-5">Penempatan</div>
                            <div class="text-gray-600">{{ $user->placement }}</div>
                            @if($user->placement !== 'Pusat')
                                <div class="fw-bolder mt-5">Cabang</div>
                                <div class="text-gray-600">{{ $user->branch?->name }}</div>
                            @endif
                            <div class="fw-bolder mt-5">Department</div>
                            <div class="text-gray-600">{{ $role?->department[0]?->name ?? '-' }}</div>
                            <div class="fw-bolder mt-5">NIK</div>
                            <div class="text-gray-600">{{ $user->nip }}</div>
                            <div class="fw-bolder mt-5">Email</div>
                            <div class="text-gray-600">
                                <a href="#" class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-lg-row-fluid ms-lg-15">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                       href="#employee_data">Data Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                       href="#education_and_experiences">Pengalaman & Pendidikan</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="employee_data" role="tabpanel">
                    @include('pages.manage-users.user.partials.employee-data.employee-data-tab-content')
                </div>
                <div class="tab-pane fade" id="education_and_experiences" role="tabpanel">
                    @include('pages.manage-users.user.partials.education-and-experiences.education-and-experience-tab-content')
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
                                <th class="min-w-125px">Status</th>
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
                            <template x-for="absent in attendance?.data" :key="index">
                                <tr>
                                    <td x-text="absent.timestamp"></td>
                                    <template x-if="absent.status1 === 0">
                                        <td>
                                            <span class="badge bg-success">
                                                Check in
                                            </span>
                                        </td>
                                    </template>
                                    <template x-if="absent.status1 === 1">
                                        <td>
                                            <span class="badge bg-danger">
                                                Check out
                                            </span>
                                        </td>
                                    </template>
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
                education: {},
                educationCertificates: [],
                jobExperiences: [],
                familyInformation: [],
                bpjsKesStatus: false,
                bpjsKetStatus: false,
                attendance: [],
                healthInformation: [],
                marriedStatus: null,
                contractStatus: null,
                marriedData: [{name: "K/1"}, {name: "K/2"}, {name: "K/3"}],
                noMarriedData: [{name: "TK/1"}, {name: "TK/2"}, {name: "TK/3"}],
                educationCertificateVal: '',
                jobExperiencesVal: '',
                childNameData: [],
                diseaseData: [],
                identityInformationForm: document.getElementById('form-identity-information-update'),
                identityInformationModal: new bootstrap.Modal(document.getElementById('identity-information-update-modal')),
                jobInformationForm: document.getElementById('form-job-information-update'),
                jobInformationModal: new bootstrap.Modal(document.getElementById('job-information-update-modal')),
                educationForm: document.getElementById('form-education-update'),
                educationModal: new bootstrap.Modal(document.getElementById('education-update-modal')),
                educationCertificateModalCreate: new bootstrap.Modal(document.getElementById('education-certificate-create-modal')),
                educationCertificateFormCreate: document.getElementById('education-certificate-create-form'),
                educationCertificateFormEdit: document.getElementById('education-certificate-edit-form'),
                educationCertificateModalEdit: new bootstrap.Modal(document.getElementById('education-certificate-edit-modal')),
                jobExperienceModalCreate: new bootstrap.Modal(document.getElementById('create-job-experience-modal')),
                jobExperienceFormCreate: document.getElementById('create-job-experience-form'),
                jobExperienceModalEdit: new bootstrap.Modal(document.getElementById('edit-job-experience-modal')),
                jobExperienceFormEdit: document.getElementById('edit-job-experience-form'),
                familyInformationModal: new bootstrap.Modal(document.getElementById('family-information-update-modal')),
                familyInformationForm: document.getElementById('form-family-information-update'),
                healthInformationModal: new bootstrap.Modal(document.getElementById('health-information-update-modal')),
                healthInformationForm: document.getElementById('form-health-information-update'),
                async init() {
                    await this.getIdentityInformation();
                    await this.getJobInformation();
                    await this.getAbsentData();
                    await this.getEducation();
                    await this.getEducationCertificate();
                    await this.getJobExperiences();
                    await this.getFamilyInformation();
                    await this.selectedChildData();
                    await this.getHealthInformation();
                    await this.selectedDiseaseData();
                },
                async add() {
                    await this.getDepartmentData();

                    if (Object.keys(this.jobInformation).length > 0) {
                        await this.selectedDepartment();
                    }
                },
                async identityInformationUpdate() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/identity-information/${this.userId}`, new FormData(this.identityInformationForm));
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
                        await axios.post(`/manage-users/job-information/${this.userId}`, new FormData(this.jobInformationForm));
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
                async educationUpdate() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/educations/${this.userId}`, new FormData(this.educationForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.educationModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async educationCertificateStore() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/education-certificates/store/${this.userId}`, new FormData(this.educationCertificateFormCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.educationCertificateModalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async educationCertificatesEdit(id) {
                    const resp = await axios.get(`/manage-users/education-certificates/edit/${id}`)
                    this.educationCertificateVal = resp.data
                },
                async educationCertificatesUpdate(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/education-certificates/update/${id}`, new FormData(this.educationCertificateFormEdit));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.educationCertificateModalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedChildData() {
                    this.childNameData = [];
                    this.familyInformation.child?.map((child, i) => {
                        this.childNameData.push({
                            name: child.child
                        })
                    })
                },
                async selectedDiseaseData() {
                    this.diseaseData = [];
                    if (this.healthInformation.disease) {
                        this.healthInformation.disease.map((name) => {
                            this.diseaseData.push({
                                name: name
                            })
                        })
                    }
                },
                async educationCertificatesDestroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/education-certificates/destroy/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async jobExperienceStore() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/job-experiences/store/${this.userId}`, new FormData(this.jobExperienceFormCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.jobExperienceModalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                addChildNameData() {
                    this.childNameData.push({
                        name: '',
                    })
                },
                removeChildData(index) {
                    if (this.childNameData.length > 1) {
                        this.childNameData.splice(index, 1);
                    }
                },
                addDiseaseData() {
                    this.diseaseData.push({
                        name: '',
                    });
                },
                removeDiseaseData(index) {
                    if (this.diseaseData.length > 1) {
                        this.diseaseData.splice(index, 1);
                    }
                },
                async jobExperienceEdit(id) {
                    const resp = await axios.get(`/manage-users/job-experiences/edit/${id}`);
                    this.jobExperiencesVal = resp.data;
                },
                async jobExperienceUpdate(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/job-experiences/update/${id}`, new FormData(this.jobExperienceFormEdit));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.jobExperienceModalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async familyInformationUpdate(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/family-informations/${this.userId}`, new FormData(this.familyInformationForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.familyInformationModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async healthInformationUpdate(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/health-informations/${this.userId}`, new FormData(this.healthInformationForm));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.healthInformationModal.hide();
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
                    const resp = await axios.get(`/manage-users/identity-information/${this.userId}`);
                    this.identityInformation = resp.data;

                    this.marriedStatus = this.identityInformation.marital_status === 'menikah' ? 'menikah' : 'tidak menikah';
                },
                async getJobInformation() {
                    const resp = await axios.get(`/manage-users/job-information/${this.userId}`);
                    this.jobInformation = resp.data;

                    this.bpjsKesStatus = this.jobInformation.no_kis ? 'ya' : 'tidak';
                    this.bpjsKetStatus = this.jobInformation.no_kpj ? 'ya' : 'tidak';
                },
                async getEducation() {
                    const resp = await axios.get(`/manage-users/educations/${this.userId}`);
                    this.education = resp.data;
                },
                async getEducationCertificate() {
                    const resp = await axios.get(`/manage-users/education-certificates/${this.userId}`);
                    this.educationCertificates = resp.data;
                },
                async getJobExperiences() {
                    const resp = await axios.get(`/manage-users/job-experiences/${this.userId}`);
                    this.jobExperiences = resp.data;
                },
                async getAbsentData() {
                    const resp = await axios.get(`/manage-users/users/absent/data/${this.userId}`);
                    this.attendance = resp.data;
                },
                async getFamilyInformation() {
                    const resp = await axios.get(`/manage-users/family-informations/${this.userId}`);
                    this.familyInformation = resp.data;
                },
                async getHealthInformation() {
                    const resp = await axios.get(`/manage-users/health-informations/${this.userId}`);
                    this.healthInformation = resp.data;
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
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/placeholders/ktp.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },

            }
        }
    </script>
@endpush
