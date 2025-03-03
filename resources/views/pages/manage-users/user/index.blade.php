@extends('layouts.template')
@section('page-title', 'Data User')
@section('content')
    <div x-data="userData()">
        @include('pages.manage-users.user.modal.import')
        <div class="d-flex flex-column flex-xl-row">
            @canany([
                    'Filter Data Karyawan Berdasarkan Cabang',
                     'Filter Data Karyawan Berdasarkan Perusahaan',
                     'Filter Data Karyawan Berdasarkan Tahun',
                     'Filter Data Karyawan Berdasarkan Bulan',
                     'Filter Data Karyawan Berdasarkan Aktif Dan Tidak Aktif',
            ])
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                    <div class="card card-flush">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="mb-0">Data Karyawan</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Cabang')
                                        <select class="form-select form-select-solid main-branches-select2"
                                                name="branch_id" id="branch_id">
                                        </select>
                                    @endcan
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Jabatan')
                                        <select name="role_id" id="role_id"
                                                class="form-select form-select-solid roles-select2">
                                            <option></option>
                                        </select>
                                    @endcan
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Perusahaan')
                                        <select name="company_id" id="company_id"
                                                class="form-select form-select-solid companies-select2">
                                            <option></option>
                                        </select>
                                    @endcan
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Tahun')
                                        <input type="number" name="year" id="year"
                                               class="form-control form-control-solid"
                                               placeholder="Filter Berdasarkan Tahun">
                                    @endcan
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Bulan')
                                        <select class="form-select form-select-solid"
                                                name="month" id="month" data-control="select2"
                                                data-placeholder="Pilih Bulan">
                                            <option></option>
                                            <template x-for="month in months" :key="index">
                                                <option :value="month.number" x-text="month.name"></option>
                                            </template>
                                        </select>
                                    @endcan
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    @can('Filter Data Karyawan Berdasarkan Aktif Dan Tidak Aktif')
                                        <select class="form-select form-select-solid" name="active" id="active"
                                                data-control="select2"
                                                data-placeholder="Select an option" data-allow-clear="true">
                                            <option></option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="button" @click="filter()" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            @endcanany
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            @can('Tambah Data Karyawan')
                                <a href="{{ url('manage-users/users/create') }}"
                                   class="btn btn-light btn-active-primary btn-sm mx-1">
                                    <i class="bi bi-plus-circle-fill"></i> Tambah
                                </a>
                            @endcan
                            @can('Import Data Karyawan')
                                    <button class="btn btn-light btn-active-info btn-sm mx-1" data-bs-toggle="modal"
                                        data-bs-target="#modal-import">
                               <span class="svg-icon">
                                    <i class="bi bi-upload fs-5"></i>
                               </span>
                                    Import
                                </button>
                            @endcan
                                <button class="btn btn-light btn-active-info btn-sm" @click="reload()">
                               <span class="svg-icon">
                                     <i class="bi bi-arrow-clockwise"></i>
                               </span>
                                    Reload
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
																<path
                                                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
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
                                <table class="table align-middle table-bordered fs-6 "
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()"
                                                       :disabled="Number(deletePermission) !== 1">
                                            </div>
                                        </th>
                                        <th>Cabang</th>
                                        <th>Perusahaan</th>
                                        <th>NIK</th>
                                        <th>Karyawan</th>
                                        <th>Tanggal Masuk</th>
                                        <template
                                                x-if="Number(editPermission === 1) || Number(activationPermission) === 1">
                                            <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                                aria-label="Actions" style="width: 135.25px;">
                                                Actions
                                            </th>
                                        </template>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="7">
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
                                            <td colspan="7">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="user in users.data" :key="user.id">
                                        <tr :class="`${user.active === 1 ? '' : 'bg-light-danger'}`">
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="user.id"
                                                           :id="'checkbox-' + user.id"
                                                           :disabled="Number(deletePermission) !== 1"/>
                                                </div>
                                            </td>
                                            <td x-text="user.branch ?? 'Pusat'"></td>
                                            <td x-text="user.company"></td>
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
                                                    <a :href="Number(viewDetailPermission) === 1 ? `/manage-users/users/detail/${user.id}` : '#'"
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
                                                <template x-if="editPermission">
                                                    <a :href="`/manage-users/users/edit/${user.id}`"
                                                       class="btn btn-light btn-active-primary btn-sm">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                </template>
                                                <template x-if="Number(activationPermission) === 1">
                                                    <button
                                                            :class="`${user.active ? 'btn btn-light btn-active-danger btn-sm' : 'btn btn-light btn-active-success btn-sm'}`"
                                                            @click="changeActiveStatus(user.id)">
                                                        <i :class="`${user.active ? 'bi bi-x-circle-fill' : 'bi bi-check-circle'}`"></i>
                                                    </button>
                                                </template>
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
                                                        @click="paginate(pagination.url)"
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
                deletePermission: "{{ request()->user()->can('Hapus Data Karyawan') }}",
                viewDetailPermission: "{{ request()->user()->can('Lihat Detail Data Karyawan') }}",
                editPermission: "{{ request()->user()->can('Edit Data Karyawan')  }}",
                activationPermission: "{{ request()->user()->can('Aktifasi Data Karyawan') }}",
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
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getCompanies();
                    await this.getMainBranches();
                    await this.getUserData();
                    await this.getMonth();
                    await this.getRoles();
                },
                async reload() {
                    this.users = [];
                    await this.init()
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
                async getCompanies() {
                    $(".companies-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Perusahaan",
                        ajax: {
                            url: '/select2/companies-data',
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
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    try {
                        this.users = [];
                        this.isLoading = true;
                        const resp = await axios.get('/manage-users/users/filter', {
                            params: {
                                search: this.search,
                                month: document.getElementById('month')?.value,
                                year: document.getElementById('year')?.value,
                                branch_id: $(".main-branches-select2")?.val(),
                                company_id: $(".companies-select2")?.val(),
                                active: document.getElementById('active')?.value,
                                role_id: $('#role_id').val(),
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
                        params: {
                            search: this.search,
                            month: document.getElementById('month')?.value,
                            year: document.getElementById('year')?.value,
                            branch_id: $(".main-branches-select2")?.val(),
                            company_id: $(".companies-select2")?.val(),
                            active: document.getElementById('active')?.value,
                            role_id: $('#role_id').val(),
                        },
                        headers: {'Content-Type': 'application/json'}
                    });

                    this.users = resp.data
                },
                async paginate(url) {
                    try {
                        if (url) {
                            this.users = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    month: document.getElementById('month')?.value,
                                    year: document.getElementById('year')?.value,
                                    branch_id: $(".main-branches-select2")?.val(),
                                    company_id: $(".companies-select2")?.val(),
                                    active: document.getElementById('active')?.value,
                                    role_id: $('#role_id').val(),
                                }
                            });
                            this.users = resp.data
                        }
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
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
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },
                async getRoles() {
                    $(".roles-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Jabatan",
                        ajax: {
                            url: '/select2/roles-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
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
                            await showAlert('success', 'Data sukses diaktifkan');
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
