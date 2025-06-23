@extends('layouts.template')
@section('content')
    <div x-data="skData()">
        @include('pages.manage-users.sk.form')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            <button data-bs-toggle="modal"
                                    data-bs-target="#modal-sk" @click="add()"
                                    class="btn btn-light btn-active-primary btn-sm mx-1">
                                <i class="bi bi-plus-circle-fill"></i> Tambah
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
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                            Nomor SK
                                        </th>
                                        <th class="min-w-50px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">
                                            Karyawan
                                        </th>
                                        <th class="min-w-150px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="User: activate to sort column ascending">
                                            Jenis SK
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending">
                                            Tanggal
                                        </th>
                                        <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_roles_view_table"
                                            rowspan="1" colspan="1"
                                            aria-label="Joined Date: activate to sort column ascending">
                                            File SK
                                        </th>
                                        <th class="text-end min-w-100px sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Actions">
                                            Actions
                                        </th>
                                    </tr>
                                    </thead>
                                    <template x-if="isLoading">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="5">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && skData.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="sk in skData.data" :key="sk.id">
                                        <tbody class="fw-bold text-center">
                                        <tr>
                                            <td x-text="sk.sk_number"></td>
                                            <td>
                                                <a :href="`/manage-users/users/detail/${sk.user_id}`"
                                                   class="text-gray-800 text-hover-primary mb-1">
                                                    <span x-text="sk.user_name"></span>
                                                </a>
                                            </td>
                                            <td x-text="sk.sk_type"></td>
                                            <td x-text="sk.date"></td>
                                            <td>
                                                <a :href="`/manage-users/sk/export-pdf/${sk.id}`"
                                                   class="btn btn-danger btn-sm" target="_blank">
                                                    <i class="bi bi-file-pdf-fill"></i>
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <button data-bs-toggle="modal" data-bs-target="#modal-sk"
                                                        @click="edit(sk.id)"
                                                        class="btn btn-light btn-active-primary btn-sm mx-1">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in skData.links">
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
        </div>
        @include('components.toast')
        @include('components.select2.script')
    </div>
@endsection
@push('script')
    <script>
        $('.date').flatpickr();
        function skData() {
            return {
                isLoading: false,
                skData: [],
                startIndex: null,
                buttonLoading: false,
                search: '',
                editVal: '',
                modalForm: new bootstrap.Modal(document.getElementById('modal-sk')),
                form: document.getElementById('form-sk'),
                async init() {
                    await this.getSkData();
                    await select2('.users-select2', 'Pilih Karyawan', '/select2/users-data');
                    await select2('.roles-select2', 'Pilih Jabatan', '/select2/roles-data');
                    await select2('.main-branches-select2', 'Pilih Cabang', '/select2/main-branches-data');
                },
                async searchData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/manage-users/sk/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.skData = resp.data
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async add() {
                    this.form.reset();
                    $('#selected-user').val("");
                    $('#selected-role').val("");
                    $('#selected-branch').val("");
                },
                async paginationEndPoint(url) {
                    const resp = await axios.get(`${url}`);
                    this.startIndex = resp.data.from
                    this.skData = resp.data
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post(`/manage-users/sk/`, new FormData(this.form))
                        } else {
                            await axios.post(`/manage-users/sk/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.modalForm.hide();
                        await this.form.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/manage-users/sk/${id}`);
                    this.editVal = resp.data;
                    await selectedValue('selected-user', `/select2/selected-user/${this.editVal.user_id}`);
                    await selectedValue('selected-role', `/select2/selected-role/${this.editVal.new_role_id}`);
                    await selectedValue('selected-branch', `/select2/selected-branch/${this.editVal.new_branch_id}`);
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/sk/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        await this.modalEdit.hide();
                        await this.formEdit.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getSkData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/manage-users/sk/data');
                        this.skData = resp.data;
                        this.startIndex = this.skData.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
