@extends('layouts.template')
@section('page-title', 'Data Mesin Absensi')
@section('content')
    <div x-data="fpDevicesData()">
        <div class="d-flex flex-column flex-xl-row">
            @include('pages.adms.fp-devices.drawer.filter')
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush">
                    @include('pages.adms.fp-devices.form')
                    @include('pages.adms.fp-devices.query-attlog')
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end ">
                                @can('Tambah Menu Mesin Absen')
                                    <button type="button" class="btn btn-light-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-fp-device">
                                        <x-icons.add-item/>
                                        Tambah
                                    </button>
                                @endcan
                                @can('Filter Data Mesin Absen Berdasarkan Cabang')
                                    <button id="kt_drawer_example_basic_button"
                                            class="btn btn-light btn-light-info btn-sm mx-1">
                                        <x-icons.filter/>
                                        Filter
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="col-12 mb-4">
                                <form id="deleteForm" @submit.prevent="destroy()">
                                    <input type="hidden" :name="`id[]`"
                                           :value="selectedCheckBox.filter((val) => val !== 'on')">
                                    <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                            x-show="selectedCheckBox.length > 0"
                                            x-transition x-cloak>
                                        <x-icons.trash/>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-125px text-center">Cabang</th>
                                        <th class="min-w-125px text-center">IP Address</th>
                                        <th class="min-w-125px text-center">Realtime Status</th>
                                        <th class="min-w-125px text-center">Last Download</th>
                                        <th class="min-w-250px text-center">Actions</th>
                                    </thead>
                                    <template x-if="isLoading">
                                        <x-table.loading colspan="6"/>
                                    </template>
                                    <template x-if="!isLoading && devices.data?.length === 0">
                                        <x-table.empty colspan="6"/>
                                    </template>
                                    <template x-for="(device, index) in devices?.data" :key="device.id">
                                        <tbody class="fw-bold text-gray-600">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox"
                                                           :value="device.id"
                                                           :id="'checkbox-' + device.id"/>
                                                </div>
                                            </td>
                                            <td class="text-center"
                                                x-text="device.branch_name ?? 'Belum Diset'"></td>
                                            <td class="text-center" x-text="device.ip_address"></td>
                                            <td class="text-center">
                                        <span
                                            :class="device.online === 'Offline' ? 'badge bg-light-danger text-danger fs-7' : 'badge bg-light-success text-success fs-7'"
                                            x-text="device.online"></span>
                                            </td>
                                            <td class="text-center">
                                                <span x-text="formatDate(device.last_download_date)"></span>
                                                <span
                                                    :class="device.last_download_status === 'Pending' ? 'badge bg-warning text-white' : device.last_download_status === 'Sukses' ? 'badge bg-success text-white' ? device.last_download_status === 'Gagal' ? 'badge bg-danger text-white' : 'badge bg-success text-white' : '' : ''"
                                                    x-text="device.last_download_status"></span>
                                            </td>
                                            <td class="d-flex flex-column">
                                                <template x-if="Number(editPermission) === 1">
                                                    <button class="btn btn-light-primary btn-sm mb-4"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-fp-device"
                                                            @click="edit(device.id)">
                                                        <x-icons.edit/>
                                                        Ubah Data
                                                    </button>
                                                </template>
                                                <template x-if="Number(testConnectionPermission) === 1">
                                                    <button class="btn btn-light-info btn-sm mb-4"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Test Koneksi Mesin"
                                                            @click="testConnection(device.id)"
                                                    >
                                                        <x-icons.connect/>
                                                        Tes Koneksi
                                                    </button>
                                                </template>
                                                <template x-if="Number(queryDataPermission) === 1">
                                                    <button class="btn btn-light-danger btn-sm mb-4"
                                                            data-bs-placement="top" title="Tarik Data"
                                                            @click="edit(device.id)"
                                                            data-bs-target="#modal-query-attlog"
                                                            data-bs-toggle="modal"
                                                            :disabled="device.last_download_status === 'Pending'"
                                                    >
                                                        <x-icons.info/>
                                                        Ambil Data
                                                    </button>
                                                </template>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
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
        </div>
    </div>



    @include('components.toast')
    @include('components.select2.script')
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
                userList: [],
                form: document.getElementById('form-fp-device'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-fp-device')),
                deleteForm: document.getElementById('deleteForm'),
                formQueryAttLog: document.getElementById('form-query-attlog'),
                modalQueryAttLog: new bootstrap.Modal(document.getElementById('modal-query-attlog')),
                filterForm: document.getElementById('form-filter'),
                jobStatuses: null,
                async init() {
                    const resp = await axios.get('/adms/fp-devices/data');
                    this.devices = resp.data
                    this.startIndex = this.devices.from;
                    this.isLoading = false;
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data')
                },
                async paginationEndPoint(url) {
                    try {
                        if (url) {
                            this.devices = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    branch_id: $('#branch-id-filter').val()
                                }
                            });
                            this.devices = resp.data
                        }
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
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
                        this.devices = [];
                        this.isLoading = true;
                        const resp = await axios.get('/adms/fp-devices/search', {
                            params: {
                                search: this.search,
                                branch_id: $('#branch-id-filter').val()
                            },
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.devices = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
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
                    await selectedValue('selected-branch', `/select2/selected-branch/${this.editVal.branch_id}`);
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
                        await this.successResponse();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Koneksi Gagal');
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async filter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/fp-devices/filter', {
                            params: {
                                branch_id: $('#branch-id-filter').val()
                            },
                        })
                        this.devices = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async queryAttLog(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/fp-devices/attendance-log/${id}`, new FormData(this.formQueryAttLog));
                        await showAlert('success', 'Data Kehadiran telah di masukkan kedalam antrian dan akan berjalan di latar belakang.');
                        this.formQueryAttLog.reset();
                        this.modalQueryAttLog.hide();
                        await this.successResponse();
                    } catch (error) {
                        console.error(error);
                        await showAlert('error', 'Koneksi Gagal');
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getJobStatus(id) {
                    try {
                        const resp = await axios.get(`/adms/fp-devices/job-status/${id}`);
                        this.jobStatuses = resp.data;
                    } catch (error) {
                        const respError = error.response.data.message;
                        toastr.error(respError)
                    }
                },
                async successResponse() {
                    const resp = await axios.get(`${this.devices.path}?page=${this.devices.current_page}`, {
                        params: {
                            search: this.search,
                            branch_id: $('#branch-id-filter').val()
                        }
                    });
                    this.devices = resp.data;
                }
            }
        }
    </script>
@endpush
