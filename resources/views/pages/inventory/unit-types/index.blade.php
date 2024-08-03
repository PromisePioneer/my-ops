@extends('layouts.template')
@section('page-title', 'Data Cabang')
@section('content')
    <div x-data="unitTypesData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.inventory.unit-types.modal.create')
            @include('pages.inventory.unit-types.modal.edit')
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
                                    data-bs-target="#modal-unit-type-create">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th class="min-w-125px">Nama Satuan</th>
                                <th class="min-w-125px">Actions</th>
                            </tr>
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
                            <template x-if="!isLoading && unitTypes.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(unitType, index) in unitTypes?.data" :key="unitType.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="unitType.name"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-unit-type-edit" @click="edit(unitType.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="destroy(unitType.id)">
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
        function unitTypesData() {
            return {
                unitTypes: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                formCreate: document.getElementById('unit-types-store'),
                formEdit: document.getElementById('unit-types-update'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-unit-type-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-unit-type-edit')),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    const unitTypes = await axios.get('/inventory/unit-types/data');
                    this.unitTypes = unitTypes.data
                    this.startIndex = this.unitTypes.from;
                    this.isLoading = false;
                },
                async searchData() {
                    try {
                        this.unitTypes = await axios.get('/inventory/unit-types/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.unitTypes.next_page_url) {
                        const resp = await axios.get(`${this.unitTypes.next_page_url}`);
                        this.startIndex = this.unitTypes.from
                        this.unitTypes = resp.data
                    }
                },
                async previousPage() {
                    if (this.unitTypes.prev_page_url) {
                        const resp = await axios.get(`${this.unitTypes.prev_page_url}`);
                        this.startIndex = this.unitTypes.from
                        this.unitTypes = resp.data
                    }
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/inventory/unit-types/', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/inventory/unit-types/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/unit-types/${id}`, new FormData(this.formEdit))
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
                            await axios.delete(`/inventory/unit-types/${id}`);
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
