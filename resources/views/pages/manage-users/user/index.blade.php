@extends('layouts.template')
@section('page-title', 'Data User')
@section('content')
    <div x-data="userData()">
        @include('pages.manage-users.user.modal.import')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Data Karyawan</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid branch-select2"
                                            name="branch_id" id="branch_id">
                                    </select>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <select name="company_id" id="company_id"
                                            class="form-select form-select-solid companies-select2">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <input type="number" name="year" id="year" class="form-control form-control-solid"
                                           placeholder="Filter Berdasarkan Tahun">
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid"
                                            name="month" id="month" data-control="select2"
                                            data-placeholder="Pilih Bulan">
                                        <option></option>
                                        <template x-for="month in months" :key="index">
                                            <option :value="month.number" x-text="month.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid" name="active" id="active"
                                            data-control="select2"
                                            data-placeholder="Select an option" data-allow-clear="true">
                                        <option></option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            <a href="{{ url('manage-users/users/create') }}"
                               class="btn btn-light btn-active-primary btn-sm mx-1">
                                <i class="bi bi-plus-circle-fill"></i> Tambah
                            </a>
                            <button class="btn btn-light btn-active-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-import">
                               <span class="svg-icon">
                                    <i class="bi bi-upload fs-5"></i>
                               </span>
                                Import
                            </button>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viemanagewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                      fill="black"></path>
															</svg>
														</span>
                                <input type="text" class="form-control form-control-solid w-250px ps-15"
                                       x-model="search" @input.debounce="searchData()" placeholder="Cari...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="col-12 ">
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
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                            Cabang
                                        </th>
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending"
                                            style="width: 78.7969px;">
                                            NIK
                                        </th>
                                        <th class="min-w-150px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="User: activate to sort column ascending"
                                            style="width: 309.844px;">
                                            Karyawan
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending"
                                            style="width: 180.359px;">
                                            Tanggal Masuk
                                        </th>
                                        <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions" style="width: 135.25px;">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="5">
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
                                    <template x-for="user in users.data" :key="user.id">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="user.id"
                                                           :id="'checkbox-' + user.id"/>
                                                </div>
                                            </td>
                                            <td x-text="user.branch?.name ?? 'Pusat'"></td>
                                            <td x-text="user.nik"></td>
                                            <td class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <a href="#">
                                                        <div class="symbol-label">
                                                            <img :src="getImageURL(user.profile_pic ?? null)"
                                                                 @click="$dispatch('lightbox', `${getImageURL(user.profile_pic) ?? null}`)"
                                                                 alt="Foto Karyawan" class="w-100"/>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a :href="`/manage-users/users/detail/${user.id}`"
                                                       class="text-gray-800 text-hover-primary mb-1">
                                                        <span x-text="user.name"></span>
                                                    </a>
                                                    <span class="badge badge-light-info fw-bolder fs-8"
                                                          x-text="user.roles ?? ''">
                                                        </span>
                                                </div>
                                            </td>
                                            @include('pages.manage-users.user.modal.import')
                                            <td x-text="user.join_date"></td>
                                            <td class="text-end">
                                                <a :href="`/manage-users/users/edit/${user.id}`"
                                                   class="btn btn-light btn-active-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button :class="`${user.active ? 'btn btn-light btn-active-danger btn-sm' : 'btn btn-light btn-active-success btn-sm'}`"
                                                        @click="changeActiveStatus(user.id)">
                                                    <i :class="`${user.active ? 'bi bi-x-circle-fill' : 'bi bi-check-circle'}`"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-10">
                                <div class="col-sm-12  d-flex align-items-center justify-content-end">
                                    <template x-for="pagination in users.links">
                                        <ul class="pagination">
                                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                                <button
                                                        class="page-link"
                                                        @click="paginationEndPoint(pagination.url)"
                                                        x-html="pagination.label"></button>
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
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
                isLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                formFilter: document.getElementById('form-filter'),
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getCompany();
                    await this.getUserData();
                    await this.filterByBranch();
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
                async getCompany() {
                    $(".companies-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Perusahaan",
                        ajax: {
                            url: '/manage-users/users/companies/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                toggleAllCheckBox() {
                    this.selectAll = !this.selectAll;
                    this.singleChecked = false;
                    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    this.selectedCheckBox = [];
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = this.selectAll;
                        if (this.selectAll) {
                            this.selectedCheckBox.push(checkbox.value);
                        }
                    });
                    this.selectedCheckBox.shift();
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
                async getUserData() {
                    this.isLoading = true;
                    try {
                        const users = await axios.get('/manage-users/users/data');
                        this.users = users.data;
                        this.startIndex = this.users.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".branch-select2")?.val();
                    const company_id = $(".companies-select2")?.val();
                    const active = document.getElementById('active')?.value;
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/users/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id,
                                company_id: company_id,
                                active: active
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
                    const resp = await axios.get('/manage-users/users/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });

                    this.users = resp.data
                },
                async paginationEndPoint(url) {
                    const company_id = $(".companies-select2")?.val();

                    const resp = await axios.get(`${url}`, {
                        params: {
                            company_id: company_id
                        }
                    });
                    this.startIndex = resp.data.from
                    this.users = resp.data
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/manage-users/users/destroy`, new FormData(this.formDelete));
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
                    showConfirmModal("Anda yakin?", "Ganti Status Aktif?", "Ya, Ganti!", async () => {
                        try {
                            await axios.post(`/manage-users/users/change-status/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
