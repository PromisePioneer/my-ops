@extends('layouts.template')
@section('page-title', 'Data JC Area')
@section('content')
    <div x-data="jointClosureArea()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.operational-master-data.joint-closures-code.modal.create')
            @include('pages.operational-master-data.joint-closures-code.modal.edit')
            @include('pages.operational-master-data.joint-closures-code.modal.import')
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

                            <button type="button" class="btn btn-light-success btn-sm me-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-import">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                                Import
                            </button>

                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        </div>
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
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Kode</th>
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
                            <template x-if="!isLoading && jcArea.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="area in jcArea?.data" :key="area.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="area.id"
                                                   :id="'checkbox-' + area.id"/>
                                        </div>
                                    </td>
                                    <td x-text="area.branch.name"></td>
                                    <td x-text="area.code"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(area.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in jcArea.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function jointClosureArea() {
            return {
                jcArea: [],
                isLoading: true,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.jointClosureData();
                    await this.getBranchData();
                },
                async jointClosureData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational-master-data/joint-closures-code/data');
                        this.jcArea = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        this.jcArea = await axios.get('/operational-master-data/branch/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.jcArea = resp.data
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
                        await axios.post('/operational-master-data/joint-closures-code/', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/operational-master-data/joint-closures-code/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch()
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/operational-master-data/joint-closures-code/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selectedBranch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational-master-data/joint-closures-code/branch/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/operational-master-data/joint-closures-code/${id}`, new FormData(this.formEdit));
                        await showAlert('success', 'Data berhasil disimpan');
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/operational-master-data/joint-closures-code/destroy`, new FormData(this.formDelete));
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
