@extends('layouts.template')
@section('page-title', 'Menu Management')
@section('content')
    <div x-data="departmentsData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.department.modal.create')
            @include('pages.master.department.modal.edit')
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
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                Tambah
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
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Kode</th>
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
                            <template x-if="!isLoading && menus.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(menu, index) in menus?.data" :key="department.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="menu.parent_id"></td>
                                    <td x-text="menu.name"></td>
                                    <td x-text="menu.icon"></td>
                                    <td x-text="menu.serial_number"></td>
                                    saas
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(department.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                                @click="destroy(department.id)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function departmentsData() {
            return {
                isLoading: false,
                buttonLoading: false,
                menus: [],
                startIndex: null,
                search: '',
                editVal: '',
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formEdit: document.getElementById('form-edit'),
                async init() {
                    await this.getMenuData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        this.departments = await axios.get('/master/department/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }

                },
                async nextPage() {
                    if (this.departments.next_page_url) {
                        const resp = await axios.get(`${this.departments.next_page_url}`);
                        this.startIndex = this.resp.from
                        this.departments = resp.data
                    }
                },
                async previousPage() {
                    if (this.departments.prev_page_url) {
                        const resp = await axios.get(`${this.departments.prev_page_url}`);
                        this.startIndex = this.resp.from
                        this.departments = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/department/', new FormData(this.formCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalCreate.hide();
                        this.formCreate.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/department/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/department/${id}`, new FormData(this.formEdit));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.modalEdit.hide();
                        this.formEdit.reset();
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
                            await axios.delete(`/master/department/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getMenuData() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/setting/menu/data');
                        this.menus = resp.data;
                        this.startIndex = this.menus.from;
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
