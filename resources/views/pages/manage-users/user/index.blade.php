@extends('layouts.template')
@section('page-title', 'Data Karyawan')
@section('breadcrumbs', 'Manajemen Karyawan - Data Karyawan - Karyawan Tidak Aktif')
@section('content')
    <div x-data="userData()">
        @include('pages.manage-users.user.modal.import')
        @include('pages.manage-users.user.drawer.filter')
        <div class="d-flex flex-column flex-xl-row">
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
                                @canany([
                                'Filter Data Karyawan Berdasarkan Cabang',
                                 'Filter Data Karyawan Berdasarkan Perusahaan',
                                 'Filter Data Karyawan Berdasarkan Tahun',
                                 'Filter Data Karyawan Berdasarkan Bulan',
                                 'Filter Data Karyawan Berdasarkan Aktif Dan Tidak Aktif',
                                ])
                                    <button id="kt_drawer_example_basic_button"
                                            class="btn btn-light btn-active-info btn-sm mx-1">
                                        <x-icons.filter/>
                                        Filter
                                </button>
                                @endcanany
                                @can('Lihat Menu Arsip Karyawan')
                                    <a href="{{ url('/manage-users/users/trashed') }}"
                                       class="btn btn-light btn-active-info btn-sm mx-1">
                                        <x-icons.archived/>
                                    Arsip
                                </a>
                                @endcan
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
                                <input type="hidden" :name="`id[]`"
                                       :value="selectedCheckBox.filter((val) => val !== 'on')">
                                <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                        x-show="selectedCheckBox.length > 0"
                                        x-transition x-cloak>
                                    <x-icons.trash/>
                                    Hapus
                                </button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-bordered fs-6"
                                   id="kt_roles_view_table">
                                <thead>
                                <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox"
                                                   @click="toggleAllCheckBox()"
                                                   :disabled="Number(deletePermission) !== 1">
                                        </div>
                                    </th>
                                    <th class="min-w-125px">NIK & Email</th>
                                    <th class="min-w-125px">Karyawan</th>
                                    <th class="min-w-125px">Tanggal Masuk</th>
                                    <th class="min-w-125px">Terakhir Login</th>
                                    <template
                                            x-if="Number(editPermission === 1) || Number(activationPermission) === 1">
                                        <th class="text-center min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions" style="width: 135.25px;">
                                            Actions
                                        </th>
                                    </template>
                                </tr>
                                </thead>
                                <template x-if="isLoading">
                                    <x-table.loading colspan="6"/>
                                </template>
                                <template x-if="!isLoading && users.data?.length === 0">
                                    <x-table.empty colspan="6"/>
                                </template>
                                <template x-for="user in users.data" :key="user.id">
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="user.id"
                                                       :id="'checkbox-' + user.id"
                                                       :disabled="Number(deletePermission) !== 1"/>
                                            </div>
                                        </td>
                                        <td>
                                            <p x-text="user.nik"></p>
                                            <p x-text="user.email"></p>
                                            <p>
                                                ID Absen : <span class="badge badge-light-info"
                                                                 x-text="user.absent_id"></span>
                                            </p>
                                        </td>
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="#">
                                                    <div class="symbol-label">
                                                        <a href="#" @click="openImage(user.profile_pic)">
                                                            <img :src="getImageURL(user.profile_pic ?? null)"
                                                                 alt="Image" class="w-100">
                                                        </a>
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
                                        <td class="text-center" x-text="user.join_date"></td>
                                        <td x-text="user.last_login"></td>
                                        <td class="text-center">
                                            <template x-if="editPermission">
                                                <a :href="`/manage-users/users/edit/${user.id}`"
                                                   class="btn btn-light btn-active-primary btn-sm">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </template>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
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
        @include('components.toast')
        @include('components.select2.script')
        @include('components.image.handle-image')
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
                    await select2('.companies-select2', 'Pilih Perusahaan', '/select2/companies-data');
                    await select2('.main-branches-select2', 'Pilih Cabang', '/select2/main-branches-data');
                    await select2('.roles-select2', 'Pilih Jabatan', '/select2/roles-data');
                    await this.getUserData();
                    await this.getMonth();
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
                            await showAlert('success', 'Data sukses diarsipkan');
                            this.selectedCheckBox = [];
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
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
            }
        }
    </script>
@endpush
