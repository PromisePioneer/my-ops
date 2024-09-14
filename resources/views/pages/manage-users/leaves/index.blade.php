@extends('layouts.template')
@section('page-title', 'Data Cuti Karyawan')
@section('content')
    <div x-data="leavesData()">
        @include('pages.manage-users.leaves.modal.confirm')
        @include('pages.manage-users.leaves.modal.detail')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                         <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Alasan Cuti</th>
                                <th class="min-w-125px">Status Cuti</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
                                <th class="min-w-125px">Status Cuti</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && leaves.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(leave, index) in leaves?.data" :key="leave.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td>
                                        <a :href="`/manage-users/users/detail/${leave.user_id}`"
                                           x-text="leave.user_name"></a>
                                    </td>
                                    <td x-text="`${leave.start_date} - ${leave.end_date}`"></td>
                                    <td x-text="leave.reason"></td>
                                    <td x-text="leave.leaves_status"></td>
                                    <td>
                                        <template x-if="leave.confirmation_status === 'Diproses'">
                                            <span class="badge bg-warning">Diproses</span>
                                        </template>
                                        <template x-if="leave.confirmation_status === 'Diterima'">
                                            <span class="badge bg-success">Diterima</span>
                                        </template>
                                        <template x-if="leave.confirmation_status === 'Ditolak'">
                                            <span class="badge bg-danger">Ditolak</span>
                                        </template>
                                    </td>
                                    <template
                                            x-if="leave.confirmation_status === 'Diterima' || leave.confirmation_status === 'Ditolak'">
                                        <td>
                                            <button class="btn btn-info btn-sm" disabled>
                                                <i class="bi bi-gear-fill"></i>
                                            </button>
                                            <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-detail" @click="detail(leave.id)">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="leave.confirmation_status === 'Diproses'">
                                        <td>
                                            @can('Acc Cuti')
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-confirm"
                                                        @click="openConfirmModal(leave.id)"
                                                        :disabled="Number(currentLoginId) === Number(leave.user_id)">
                                                    <i class="bi bi-gear-fill"></i>
                                                </button>
                                            @endcan
                                            @can('Lihat Detail Cuti')
                                                <button class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-detail" @click="detail(leave.id)">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in leaves.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function leavesData() {
            return {
                buttonLoading: false,
                isLoading: false,
                leaves: [],
                search: '',
                currentLoginId: "{{ Auth::id() }}",
                id: '',
                detailValue: '',
                modalConfirm: new bootstrap.Modal(document.getElementById('modal-confirm')),
                formConfirm: document.getElementById('form-confirm'),
                modalDetail: new bootstrap.Modal(document.getElementById('modal-detail')),
                async init() {
                    this.isLoading = true;
                    await this.getLeavesData();
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/leaves/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.leaves = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.leaves = resp.data
                    }
                },
                async detail(id) {
                    const resp = await axios.get(`/manage-users/leaves/${id}`);
                    this.detailValue = resp.data;
                },
                openConfirmModal(id) {
                    this.id = id;
                },
                async confirm() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/leaves/${this.id}`, new FormData(this.formConfirm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formConfirm.reset();
                        this.modalConfirm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getLeavesData() {
                    const resp = await axios.get('/manage-users/leaves/data');
                    this.leaves = resp.data
                    this.startIndex = resp.data.from;
                }
            }
        }
    </script>
@endpush
