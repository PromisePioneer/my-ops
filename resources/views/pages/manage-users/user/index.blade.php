@extends('layouts.template')
@section('page-title', 'Data User')
@section('content')

    <div x-data="userData()">
        @include('pages.manage-users.user.modal.import')
        <div class="col-lg-6">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header">
                    <h3 class="card-title">Filter Data</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4 mt-0">
                        <input type="number" min="1900" max="2099" step="1" name="year" id="year"
                               class="form-control form-control-solid" placeholder="Hi">
                    </div>
                    <div class="row mb-4">
                        <select name="month" id="month" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Select an option" data-allow-clear="true">
                            <option value="">Pilih Bulan</option>
                            <template x-for="(month, index) in months" :key="index">
                                <option :value="month.number" x-text="month.name"></option>
                            </template>
                        </select>
                    </div>
                    <div class="row mb-7">
                        <select name="branch_id" id="" class="form-select form-select-solid branch-select2">
                            <option value="">Pilih Cabang</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" @click="filter()" class="btn btn-primary btn-sm">Filter</button>
                    </div>
                </div>
            </div>
        </div>
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
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light-info me-3 btn-sm " data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-funnel-fill"></i>
                            </span>
                            Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" style="">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bolder">Filter</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-bold">Cabang:</label>
                                    <select name="" id=""
                                            class="form-select form-select-solid filter-branch-select2">
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary btn-sm me-3" data-bs-toggle="modal"
                                data-bs-target="#modal-import">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                            Import
                        </button>
                        <a href="{{ url('/manage-users/users/create') }}" class="btn btn-primary btn-sm">Tambah</a>
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5 table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                No
                            </th>
                            <th class="min-w-125px">Karyawan</th>
                            <th class="min-w-125px">Role</th>
                            <th class="min-w-125px">Tanggal Masuk</th>
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
                        <template x-if="!isLoading && users.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(user,index) in users?.data" :key="user.id">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        <a :href="`/manage-users/users/detail/${user.id}`"
                                           class="text-gray-800 text-hover-primary mb-1" x-text="user.name"></a>
                                        <span x-text="user.nip"></span>
                                    </div>
                                </td>
                                <td>
                                    <template x-for="role in user.roles">
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="badge bg-danger" x-text="role.name"></span>
                                            </li>
                                        </ul>
                                    </template>
                                </td>
                                <td x-text="user.join_date"></td>
                                <td>
                                    <a x-bind:href="`/manage-users/users/edit/${user.id}`"
                                       class="btn btn-sm btn-primary"><i
                                                class="bi bi-pencil"></i></a>
                                    <button class="btn btn-danger btn-sm" @click="destroy(user.id)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <template x-if="user.active === 1">
                                        <button class="btn btn-danger btn-sm" @click="changeActiveStatus(user.id)">
                                            <i class="bi bi-x-circle-fill"></i>
                                        </button>
                                    </template>
                                    <template x-if="user.active === 0">
                                        <button class="btn btn-success btn-sm" @click="changeActiveStatus(user.id)">
                                            <i class="bi bi-check-square-fill"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
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
        @include('components.toast')
    </div>
@endsection
@push('script')
    <script>
        function userData() {
            return {
                buttonLoading: false,
                year: [{}],
                users: [],
                role: [],
                months: [],
                isLoading: true,
                startIndex: null,
                search: '',
                formFilter: document.getElementById('formFilter'),
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                async init() {
                    await this.getUserData();
                    await this.filterByBranch();
                    this.isLoading = false;
                    await this.getMonth();
                    await this.getBranchData();
                },
                getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                async getUserData() {
                    const users = await axios.get('/manage-users/users/data');
                    this.users = users.data;
                    this.startIndex = this.users.from;
                },
                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".branch-select2")?.val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/users/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id
                            }
                        });
                        this.users = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.users = await axios.get('/manage-users/users/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                },
                async nextPage() {
                    if (this.users.next_page_url) {
                        const resp = await axios.get(`${this.users.next_page_url}`);
                        this.startIndex = resp.data.from
                        this.users = resp.data
                    }
                },
                async previousPage() {
                    if (this.users.prev_page_url) {
                        const resp = await axios.get(`${this.users.prev_page_url}`);
                        this.startIndex = resp.data.from
                        this.users = resp.data
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/users/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/manage-users/users/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        ajax: {
                            url: '/manage-users/users/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/manage-users/users/filter/branch/data/${selectedBranch.id}`);
                        self.users = response.data;
                    });
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/manage-users/users/import/', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil diimport')
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async changeActiveStatus(id) {
                    this.buttonLoading = true;
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/manage-users/users/change-status/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
