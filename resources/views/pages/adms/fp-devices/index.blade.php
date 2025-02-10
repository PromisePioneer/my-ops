@extends('layouts.template')
@section('page-title', 'Data Mesin Absensi')
@section('content')
    <div x-data="fpDevicesData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.adms.fp-devices.form')
            @include('pages.adms.fp-devices.query-attlog')
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
                        @can('Tambah Menu Mesin Absen')
                        <button type="button" class="btn btn-light-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-fp-device">
                            <i class="ki-duotone ki-message-add fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i> Tambah
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="py-5">
                    <div class="col-12 ">
                        <form id="deleteForm" @submit.prevent="destroy()">
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
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px text-center">Cabang</th>
                                <th class="min-w-125px text-center">Nama Mesin</th>
                                <th class="min-w-125px text-center">IP Address</th>
                                <th class="min-w-125px text-center">Serial Number</th>
                                <th class="min-w-125px text-center">Terakhir Handshake</th>
                                <th class="min-w-125px text-center">Actions</th>
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
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="device.id"
                                                   :id="'checkbox-' + device.id"/>
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="device.branch?.name ?? 'Belum Diset'"></td>
                                    <td class="text-center" x-text="device.name"></td>
                                    <td class="text-center" x-text="device.ip_address"></td>
                                    <td class="text-center" x-text="device.serial_number"></td>
                                    <td class="text-center" x-text="device.online ?? '-'"></td>
                                    <td>
                                        <template x-if="Number(editPermission) === 1">
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-fp-device" @click="edit(device.id)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        </template>
                                        <template x-if="Number(testConnectionPermission) === 1">
                                            <button class="btn btn-light-info btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Test Koneksi Mesin"
                                                @click="testConnection(device.id)"
                                        >
                                                <i class="bi bi-ethernet"></i>
                                        </button>
                                        </template>

                                        <template x-if="Number(queryDataPermission) === 1">
                                        <button class="btn btn-light-danger btn-sm"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Tarik Data"
                                                @click="showDeviceInfo(device.id)"
                                        >
                                            <i class="bi bi-cloud-arrow-down-fill"></i>
                                        </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in devices.links">
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
        $('.date').flatpickr();

        function fpDevicesData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Menu Mesin Absen') }}",
                editPermission: "{{ request()->user()->can('Edit Menu Mesin Absen') }}",
                testConnectionPermission: "{{ request()->user()->can('Tes Koneksi Mesin Absen') }}",
                deletePermission: "{{ request()->user()->can('Hapus Menu Mesin Absen') }}",
                queryDataPermission: "{{ request()->user()->can('Tarik Data Mesin Absen') }}",
                devices: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-fp-device'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-fp-device')),
                deleteForm: document.getElementById('deleteForm'),
                formQueryAttLog: document.getElementById('form-query-attlog'),
                modalQueryAttLog: new bootstrap.Modal(document.getElementById('modal-query-attlog')),
                async init() {
                    const resp = await axios.get('/adms/fp-devices/data');
                    this.devices = resp.data
                    this.startIndex = this.devices.from;
                    this.isLoading = false;
                    await this.getBranchData();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.devices = resp.data
                    }
                },
                async showDeviceInfo(id) {
                    const resp = await axios.get(`/adms/fp-devices/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch();
                    this.modalQueryAttLog.show();
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
                async searchData() {
                    try {
                        const resp = await axios.get('/adms/fp-devices/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.devices = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/adms/fp-devices/', new FormData(this.form))
                        } else {
                            await axios.post(`/adms/fp-devices/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        this.modalForm.hide();
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/adms/fp-devices/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async testConnection(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/fp-devices/test-connection/${id}`);
                        await showAlert('success', 'Koneksi ke mesin sukses');
                        await this.init();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Koneksi Gagal');
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async queryAttLog(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/fp-devices/attendance-log/${id}`, new FormData(this.formQueryAttLog));
                        await showAlert('success', 'Data Kehadiran telah di masukkan kedalam antrian dan akan berjalan di latar belakang.', 10000);
                        this.formQueryAttLog.reset();
                        this.modalQueryAttLog.hide();
                        await this.init();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Koneksi Gagal');
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
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
