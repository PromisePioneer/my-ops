@extends('layouts.template')
@section('page-title', 'Departemen')
@section('breadcrumbs', 'Master Umum - Departemen')
@section('content')
    <div x-data="departmentsData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.common.departments.form')
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
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            @can('Tambah Data Departemen')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-department">
                                <x-icons.add-item/>
                                Tambah
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="col-12 mb-4">
                        <form id="form-delete" @submit.prevent="destroy()">
                            <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                            <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                    x-show="selectedCheckBox.length > 0"
                                    x-transition x-cloak>
                                <x-icons.trash/>
                                Hapus
                            </button>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()"
                                               :disabled="Number(deletePermission) !== 1">
                                    </div>
                                </th>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && departments.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="department in departments?.data" :key="department.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="department.id"
                                                   :id="'checkbox-' + department.id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td x-text="department.code"></td>
                                    <td x-text="department.name"></td>
                                    <td>
                                        <template x-if="Number(editPermission) === 1">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-department" @click="edit(department.id)">
                                                <x-icons.edit/>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in departments.links">
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
@endsection
@push('script')
    <script>
        function departmentsData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Departemen') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Permission') }}",
                isLoading: false,
                buttonLoading: false,
                departments: [],
                search: '',
                editVal: '',
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                modalForm: new bootstrap.Modal(document.getElementById('modal-department')),
                form: document.getElementById('form-department'),
                deleteForm: document.getElementById('form-delete'),
                async init() {
                    await this.getDepartmentData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/common/department/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.departments = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }

                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission) === 1) {
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
                    }
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
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.departments = resp.data
                    }
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/common/department/', new FormData(this.form));
                        } else {
                            await axios.post(`/master/common/department/${id}`, new FormData(this.form));
                        }
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalForm.hide();
                        this.form.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/common/department/${id}`);
                    this.editVal = resp.data;
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/department/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getDepartmentData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/master/common/department/data');
                        this.departments = resp.data;
                        this.startIndex = this.departments.from;
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
