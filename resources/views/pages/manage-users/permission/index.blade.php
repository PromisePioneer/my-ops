@extends('layouts.template')
@section('page-title', 'Data Permission')
@section('content')
    <div x-data="permissionsData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.manage-users.permission.modal.create')
            @include('pages.manage-users.permission.modal.edit')
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-create">
                            Tambah
                        </button>
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
                <div class="py-5">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">No</th>
                            <th class="min-w-125px">Nama</th>
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
                        <template x-if="!isLoading && permissions.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(permission,index) in permissions?.data" :key="permission.id">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td class="d-flex align-items-center" x-text="permission.name"></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit" @click="edit(permission.id)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" @click="destroy(permission.id)">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function permissionsData() {
            return {
                buttonLoading: false,
                permissions: null,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                async init() {
                    const permission = await axios.get('/manage-users/permissions/data');
                    this.permissions = permission.data
                    this.startIndex = this.permissions.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.permissions = await axios.get('/manage-users/permissions/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                },
                async nextPage() {
                    if (this.permissions.next_page_url) {
                        const resp = await axios.get(`${this.permissions.next_page_url}`);
                        this.startIndex = this.resp.from
                        this.permissions = resp.data
                    }
                },
                async previousPage() {
                    if (this.permissions.prev_page_url) {
                        const resp = await axios.get(`${this.permissions.prev_page_url}`);
                        this.startIndex = this.resp.from
                        this.permissions = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/manage-users/permissions', new FormData(this.formCreate))
                        await showAlert('success', 'Data sukses disimpan')
                        this.formCreate.reset();
                        this.formCreate.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/manage-users/permissions/show/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/manage-users/permissions/update/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/manage-users/permissions/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
