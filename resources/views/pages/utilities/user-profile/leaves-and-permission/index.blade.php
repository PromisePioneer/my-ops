@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')

    <div x-data="leavesAndPermissionData()">
        @include('pages.utilities.user-profile.leaves-and-permission.modal.create')
        @include('pages.utilities.user-profile.leaves-and-permission.modal.edit')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <h3>Sisa Cuti : <span x-text="totalLeavesAllowance"></span></h3>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        </div>
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
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Status Cuti</th>
                                <th class="min-w-125px">Status Konfirmasi</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && leavesAndPermissions.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(leavesAndPermission, index) in leavesAndPermissions?.data"
                                      :key="leavesAndPermission.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="`${leavesAndPermission.start_date} - ${leavesAndPermission.end_date}`"></td>
                                    <td x-text="leavesAndPermission.leaves_status"></td>
                                    <td x-text="leavesAndPermission.confirmation_status"></td>
                                    <template x-if="leavesAndPermission.confirmation_status === 'Diterima'">
                                        <td>
                                            <i class="fas fa-check-double" style="color: #63E6BE;"></i>
                                        </td>
                                    </template>
                                    <template x-if="leavesAndPermission.confirmation_status === 'Ditolak'">
                                        <td>
                                            <i class="fas fa-times-circle" style="color: #cc0000;"></i>
                                        </td>
                                    </template>
                                    <template x-if="leavesAndPermission.confirmation_status === 'Diproses'">
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(leavesAndPermission.id)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-light-danger btn-sm"
                                                    @click="destroy(leavesAndPermission.id)">
                                                <i class="ki-duotone ki-trash-square fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in leavesAndPermissions.links">
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
        $('.date').flatpickr();

        function leavesAndPermissionData() {
            return {
                isLoading: false,
                buttonLoading: false,
                leavesAndPermissions: [],
                totalLeavesAllowance: null,
                startIndex: null,
                editVal: {},
                search: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                sickLetter: '',
                async init() {
                    this.isLoading = true;
                    await this.getLeavePermissionData();
                    this.isLoading = false;
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.branches = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/utility/user-profile/leaves-and-permission/', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/utility/user-profile/leaves-and-permission/${id}`)
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/utility/user-profile/leaves-and-permission/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/utility/user-profile/leaves-and-permission/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getLeavePermissionData() {
                    const resp = await axios.get('/utility/user-profile/leaves-and-permission/data');
                    this.leavesAndPermissions = resp.data.leaves;
                    this.totalLeavesAllowance = resp.data.totalLeavesAllowance
                    this.startIndex = this.leavesAndPermissions.from;
                }
            }
        }
    </script>
@endpush
