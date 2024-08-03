@extends('layouts.template')
@section('page-title', 'Manajemen Penggajian Karyawan')
@section('content')
    <div x-data="payrollData()">
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <a href="{{ url('manage-users/payroll/create') }}" class="btn btn-primary btn-sm">
                                Tambah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped"
                               id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-125px">Nama Karyawan</th>
                                <th class="min-w-125px">Periode</th>
                                <th class="min-w-125px">Gaji Pokok</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && payroll.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(pay, index) in payroll?.data" :key="index">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="pay.user.name"></td>
                                    <td x-text="pay.salary_date"></td>
                                    <td x-text="pay.user.job_information?.fixed_salary ?? '-'"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(branch.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a :href="`/manage-users/payroll/export-pdf/${pay.id}`"
                                           class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function payrollData() {
            return {
                payroll: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                search: '',
                async init() {
                    const payroll = await axios.get('/manage-users/payroll/data');
                    this.payroll = payroll.data
                    this.startIndex = this.payroll.from;
                    this.isLoading = false;
                },
                async searchData() {
                    try {
                        this.payroll = await axios.get('/manage-users/payroll/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.payroll.next_page_url) {
                        const resp = await axios.get(`${this.payroll.next_page_url}`);
                        this.startIndex = this.payroll.from
                        this.payroll = resp.data
                    }
                },
                async previousPage() {
                    if (this.payroll.prev_page_url) {
                        const resp = await axios.get(`${this.payroll.prev_page_url}`);
                        this.startIndex = this.payroll.from
                        this.payroll = resp.data
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/payroll/${id}`);
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
