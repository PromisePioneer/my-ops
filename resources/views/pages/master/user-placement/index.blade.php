@extends('layouts.template')
@section('page-title', 'Data Penempatan Karyawan')
@section('content')
    <div x-data="branchesData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.user-placement.modal.create')
            @include('pages.master.user-placement.modal.edit')
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
                        <button type="button" class="btn btn-light-primary btn-sm me-3" data-bs-toggle="modal"
                                data-bs-target="#modal-import">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                            Import
                        </button>
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
                <div class="col-12 ">
                    <form id="deleteForm" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-danger btn-sm mt-5" x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="bi bi-trash"></i>
                            Hapus
                        </button>
                    </form>

                </div>
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
                            <template x-if="!isLoading && branches.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(placement, index) in placements?.data" :key="placement.id">
                                <tr>
                                    <td x-text="startIndex + index++">

                                    </td>
                                    <td x-text="placement.code"></td>
                                    <td x-text="placement.name">
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(placement.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="destroy(placement.id)">
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
    <script defer>
        function branchesData() {
            return {
                placements: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    const placements = await axios.get('/master/user-placement/data');
                    this.placements = placements.data
                    this.startIndex = this.placements.from;
                    this.isLoading = false;
                },
                async searchData() {
                    try {
                        this.placements = await axios.get('/master/user-placement/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.placements.next_page_url) {
                        const resp = await axios.get(`${this.placements.next_page_url}`);
                        this.startIndex = this.placements.from
                        this.placements = resp.data
                    }
                },
                async previousPage() {
                    if (this.placements.prev_page_url) {
                        const resp = await axios.get(`${this.placements.prev_page_url}`);
                        this.startIndex = this.placements.from
                        this.placements = resp.data
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
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/user-placement', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/user-placement/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/user-placement/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalEdit.hide();
                        this.formEdit.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/master/user-placement/${id}`);
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
