@extends('layouts.template')
@section('page-title', 'Data Cabang')
@section('content')
    <div x-data="attendanceInformationData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.attendance-machine-info.modal.create')
            @include('pages.master.attendance-machine-info.modal.edit')
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
                            <button type="button" @click="add()" class="btn btn-primary btn-sm" data-bs-toggle="modal"
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
                                    No
                                </th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Versi</th>
                                <th class="min-w-125px">IP Address</th>
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
                            <template x-if="!isLoading && machineList.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(machine, index) in machineList?.data" :key="machine.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="machine.branch.name"></td>
                                    <td x-text="machine.version"></td>
                                    <td>
                                        <a :href="`/master/attendance-machine-info/detail/${machine.id}`"
                                           x-text="machine.ip_address"></a>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(machine.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                @click="destroy(machine.id)">
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
        function attendanceInformationData() {
            return {
                machineList: [],
                isLoading: false,
                buttonLoading: false,
                startIndex: null,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formEdit: document.getElementById('form-edit'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                async init() {
                    this.isLoading = true;
                    await this.getAttendanceMachineData();
                    this.isLoading = false;
                },
                async searchData() {
                    try {
                        this.machineList = await axios.get('/master/attendance-machine-info/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.machineList.next_page_url) {
                        const resp = await axios.get(`${this.machineList.next_page_url}`);
                        this.startIndex = this.machineList.from
                        this.machineList = resp.data
                    }
                },
                async previousPage() {
                    if (this.machineList.prev_page_url) {
                        const resp = await axios.get(`${this.machineList.prev_page_url}`);
                        this.startIndex = this.machineList.from
                        this.machineList = resp.data
                    }
                },
                async add() {
                    await this.getBranchData();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/attendance-machine-info', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/master/attendance-machine-info/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch();
                    await this.getBranchData();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/attendance-machine-info/${id}`, new FormData(this.formEdit))
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/branch/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getAttendanceMachineData() {
                    const resp = await axios.get('/master/attendance-machine-info/data');
                    this.machineList = resp.data;
                    this.startIndex = this.machineList.from;
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        ajax: {
                            url: '/master/attendance-machine-info/branch/data',
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
                        url: `/master/attendance-machine-info/selected/branch/data/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
            }
        }
    </script>
@endpush
