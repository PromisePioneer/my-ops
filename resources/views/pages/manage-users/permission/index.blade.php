@extends('layouts.template')
@section('page-title', 'Hak Akses')
@section('content')
    <div x-data="permissionsData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.manage-users.permission.form')
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
                        @can('Tambah Data Permission')
                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-permission">
                                <span class="svg-icon svg-icon-2">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </span>
                                Tambah
                            </button>
                        @endcan
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
                <div class="py-5">
                    <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()"
                                           :disabled="Number(deletePermission) !== 1">
                                </div>
                            </th>
                            <th class="w-50 text-center">Nama</th>
                            <th class="w-50 text-center">Actions</th>
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
                        <template x-if="!isLoading && permissions.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(permission,index) in permissions?.data" :key="permission.id">
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid"
                                         @click="selectCheckBox($event)">
                                        <input class="form-check-input" type="checkbox" :value="permission.id"
                                               :id="'checkbox-' + permission.id"
                                               :disabled="Number(deletePermission) !== 1"/>
                                    </div>
                                </td>
                                <td class=" text-center" x-text="permission.name"></td>
                                <td class="text-center">
                                    <template x-if="Number(editPermission) === 1">
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-permission" @click="edit(permission.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in permissions?.links">
                            <ul class="pagination">
                                <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                    <button
                                            class="page-link"
                                            @click="paginationEndPoint(pagination.url)"
                                            x-html="pagination.label"></button>
                                </li>
                            </ul>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script defer>
        function permissionsData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Permission') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Permission') }}",
                buttonLoading: false,
                permissions: null,
                isLoading: true,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-permission'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-permission')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    const permission = await axios.get('/manage-users/permissions/data');
                    this.permissions = permission.data
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/manage-users/permissions/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.permissions = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.permissions = resp.data
                    }
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
                async save(id) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/manage-users/permissions', new FormData(this.form))
                        } else {
                            await axios.post(`/manage-users/permissions/update/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data sukses disimpan')
                        this.form.reset();
                        this.modalForm.hide();
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/manage-users/permissions/destroy`, new FormData(this.formDelete));
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
