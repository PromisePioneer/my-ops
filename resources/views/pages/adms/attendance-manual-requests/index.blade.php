@extends('layouts.template')
@section('page-title', 'Pengajuan Absensi Manual')
@section('breadcrumbs', 'Data Absensi - Riwayat Absensi - Pengajuan Absensi Manual')
@section('content')
    <div x-data="attendanceManualRequestsData()">
        <div class="d-flex flex-column flex-xl-row">
            @include('pages.adms.attendance-manual-requests.confirm')
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                @can('Tambah Menu Mesin Absen')
                                    <a href="{{ url('/adms/attendances-summary/attendance-manual-requests/create') }}"
                                       class="btn btn-light-primary btn-sm">
                                        <i class="ki-duotone ki-message-add fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i> Tambah
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="col-12 pb-10">
                                <form id="form-delete" @submit.prevent="destroy()">
                                    <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                                    <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                            x-show="selectedCheckBox.length > 0"
                                            x-transition x-cloak>
                                        <i class="ki-duotone ki-trash-square fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2 text-center"></th>
                                        <th class="min-w-125px text-center">Nama</th>
                                        <th class="min-w-125px text-center">Tanggal</th>
                                        <th class="min-w-125px text-center">Alasan</th>
                                        <th class="min-w-125px text-center">Lampiran</th>
                                        <th class="min-w-125px text-center">Status</th>
                                        <th class="min-w-125px text-center">Actions</th>
                                    </thead>
                                    <tbody class="fw-bold">
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
                                    <template x-if="!isLoading && attendanceManualRequests.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(request, index) in attendanceManualRequests?.data"
                                              :key="request.id">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox"
                                                           :value="request.id"
                                                           :id="'checkbox-' + request.id"/>
                                                </div>
                                            </td>
                                            <td>
                                                <ol>
                                                    <template x-for="user in request.user_names">
                                                        <li class="mb-2" x-text="`(${user.nik}) ${user.name}`"></li>
                                                    </template>
                                                </ol>
                                            </td>
                                            <td x-text="request.date"></td>
                                            <td x-text="request.reason"></td>
                                            <td>
                                                <ol>
                                                    <template x-for="(attachment, index) in request.attachment"
                                                              :key="index">
                                                        <li>
                                                            <a href="#"
                                                               @click="$dispatch('lightbox', `${getImageURL(attachment.filename) ?? null}`)"
                                                               x-text="`Lampiran ${index + 1}`">
                                                            </a>
                                                        </li>
                                                    </template>
                                                </ol>
                                            </td>
                                            <td class="text-center">
                                                <template x-if="request.status === 'Awaiting Approval'">
                                                    <span class="badge bg-warning text-uppercase">
                                                        Awaiting Approval
                                                    </span>
                                                </template>
                                                <template x-if="request.status === 'Approved'">
                                                    <span class="badge bg-success text-uppercase">
                                                        Approved
                                                    </span>
                                                </template>
                                                <template x-if="request.status === 'Rejected'">
                                                    <span class="badge bg-danger text-white text-uppercase">
                                                        Rejected
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="">
                                                <div class="d-flex flex-column align-items-center">

                                                    <template x-if="request.status === 'Awaiting Approval'">
                                                        <a :href="`/adms/attendances-summary/attendance-manual-requests/${request.id}`"
                                                           class="btn btn-light-primary btn-sm mb-4">
                                                            <i class="bi bi-pencil"></i> Ubah Data
                                                        </a>
                                                    </template>
                                                    <button class="btn btn-light-info btn-sm mb-4"
                                                            :disabled="request.status === 'Rejected' || request.status === 'Approved'"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-attendance-manual-confirm"
                                                            @click="show(request.id)"
                                                    >
                                                        <i class="bi bi-ethernet"></i>
                                                        Konfirmasi
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ url('adms/attendances-summary/') }}"
                                   class="btn btn-light btn-light-danger btn-sm mx-1">
                                    <i class="bi bi-backspace"></i>
                                    Kembali
                                </a>
                                <ul class="pagination float-end mb-4 mt-4">
                                    <template x-for="pagination in attendanceManualRequests.links">
                                        <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                            <button class="page-link" @click="paginate(pagination.url)"
                                                    x-html="pagination.label">
                                            </button>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function attendanceManualRequestsData() {
            return {
                isLoading: false,
                buttonLoading: false,
                attendanceManualRequests: [],
                search: '',
                selectedCheckBox: [],
                attachments: [],
                attendanceManualVal: null,
                confirmationForm: document.getElementById('form-attendance-manual-confirm'),
                confirmationModal: new bootstrap.Modal(document.getElementById('modal-attendance-manual-confirm')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getAttendanceManualRequests();
                },
                async getAttendanceManualRequests() {
                    try {
                        this.isLoading = true;
                        const resp = await axios.get('/adms/attendances-summary/attendance-manual-requests/data')
                        this.attendanceManualRequests = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        this.isLoading = true;
                        this.attendanceManualVal = [];
                        const response = await axios.get('/adms/attendances-summary/attendance-manual-requests/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.attendanceManualRequests = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async show(id) {
                    const resp = await axios.get(`/adms/attendances-summary/attendance-manual-requests/show/${id}`);
                    this.attendanceManualVal = resp.data;
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.attendanceManualRequests = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`);
                            this.attendanceManualRequests = resp.data
                        }
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                selectCheckBox(event) {
                    const checkboxId = event.target.value;
                    if (event.target.checked) {
                        this.selectedCheckBox.push(checkboxId);
                    } else {
                        const index = this.selectedCheckBox.indexOf(checkboxId);
                        if (index !== -1) {
                            this.selectedCheckBox.splice(index, 1);
                        }
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        return "{{ asset('assets/media/placeholders/ktp.png') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async confirm(id) {
                    try {
                        this.buttonLoading = true;
                        await axios.post(`/adms/attendances-summary/attendance-manual-requests/confirm/${id}`, new FormData(this.confirmationForm));
                        await showAlert('success', 'Data berhasil dikonfirmasi');
                        this.confirmationForm.reset();
                        this.confirmationModal.hide();
                        this.init();
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/adms/attendances-summary/attendance-manual-requests/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
