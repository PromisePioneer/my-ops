@extends('layouts.template')
@section('page-title', 'Data Mesin Absensi')
@section('content')
    <div x-data="fpDevicesData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.fp-devices.modal.create')
            @include('pages.adms.fp-devices.modal.edit')
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
            </div>
            <div class="card-body py-3">
                <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-create">
                            Tambah
                        </button>
                    </div>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Nama Mesin</th>
                                <th class="min-w-125px">Serial Number</th>
                                <th class="min-w-125px">Terakhir Handshake</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && devices.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(device, index) in devices?.data" :key="device.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="device.branch?.name ?? 'Belum Diset'"></td>
                                    <td x-text="device.name"></td>
                                    <td x-text="device.serial_number"></td>
                                    <td x-text="device.online"></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(device.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="destroy(device.id)">
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
        function fpDevicesData() {
            return {
                devices: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                formEdit: document.getElementById('form-edit'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    const resp = await axios.get('/adms/fp-devices/data');
                    this.devices = resp.data
                    this.startIndex = this.devices.from;
                    this.isLoading = false;
                    await this.getBranchData();
                },
                async searchData() {
                    try {
                        this.devices = await axios.get('/adms/fp-devices/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.devices.next_page_url) {
                        const resp = await axios.get(`${this.devices.next_page_url}`);
                        this.devices = resp.data
                        this.startIndex = this.devices.from
                    }
                },
                async previousPage() {
                    if (this.devices.prev_page_url) {
                        const resp = await axios.get(`${this.devices.prev_page_url}`);
                        this.devices = resp.data
                        this.startIndex = this.devices.from
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/adms/fp-devices/', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/adms/fp-devices/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/fp-devices/${id}`, new FormData(this.formEdit))
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
                            await axios.delete(`/adms/fp-devices/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        ajax: {
                            url: '/adms/fp-devices/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selected-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/adms/fp-devices/branch/selected/${this.editVal.id}`,
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
