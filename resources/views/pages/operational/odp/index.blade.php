@extends('layouts.template')
@section('page-title', 'Data ODP')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    @include('pages.operational.odp.header')
    <div x-data="odpData()">
        @include('pages.operational.odp.modal.create')
        @include('pages.operational.odp.modal.edit')
        @include('pages.operational.odp.modal.import')
        @include('pages.operational.odp.modal.export')


        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
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
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-light-danger btn-sm me-3" data-bs-toggle="modal"
                                    data-bs-target="#modal-export">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                                Export
                            </button>


                            <button type="button" class="btn btn-light-success btn-sm me-3" data-bs-toggle="modal"
                                    data-bs-target="#modal-import">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                                Import
                            </button>
                            <button type="button" class="btn btn-light-primary btn-sm mr-4" @click="add()"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Tambah
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
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Kapasitas Pelanggan</th>
                                <th class="text-center">Kapasitas Terpakai</th>
                                <th class="text-center">Kapasitas Tersisa</th>
                                <th class="text-center">Cut Off</th>
                                <th class="text-center">Actions</th>
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
                            <template x-if="!isLoading && odpList.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="odp in odpList?.data" :key="odp.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="odp.id"
                                                   :id="'checkbox-' + odp.id"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>Kode area</th>
                                                    <th>:</th>
                                                    <th x-text="odp.area.code"></th>
                                                </tr>
                                                <tr>
                                                    <th> Nama ODP</th>
                                                    <th>:</th>
                                                    <th x-text="odp.name"></th>
                                                </tr>
                                                <tr>
                                                    <th>Klasifikasi</th>
                                                    <th>:</th>
                                                    <th x-text="odp.classification"></th>
                                                </tr>
                                                <tr>
                                                    <th>Passive Splitter</th>
                                                    <th>:</th>
                                                    <th x-text="odp.passive_splitter"></th>
                                                </tr>
                                                <tr>
                                                    <th>Latitude</th>
                                                    <th>:</th>
                                                    <th x-text="odp.lat"></th>
                                                </tr>
                                                <tr>
                                                    <th>Longitude</th>
                                                    <th>:</th>
                                                    <th x-text="odp.long"></th>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="odp.max_capacity"></td>
                                    <td class="text-center" x-text="odp.used_capacity"></td>
                                    <td class="text-center" x-text="`${odp.max_capacity - odp.used_capacity}`"></td>
                                    <td class="text-center" x-text="odp.cut_off_date"></td>
                                    <td class="text-center">
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(odp.id)">
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
                        <template x-for="pagination in odpList.links">
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/tokml@0.4.0/tokml.min.js"></script>
    <script>
        $('.date').flatpickr();

        function odpData() {
            return {
                search: '',
                editVal: '',
                isLoading: false,
                odpList: [],
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                formDelete: document.getElementById('form-delete'),
                modalExport: new bootstrap.Modal(document.getElementById('modal-export')),
                formExport: document.getElementById('form-export'),
                map: null,
                async init() {
                    await this.getODPData();
                    await this.getAreaData();
                },
                add() {
                    this.map = null;
                    this.map = L.map('map-create').setView([1.6704852, 101.4394371], 16);
                    this.mapTileLayer(this.map);

                    $('#modal-create').on('shown.bs.modal', () => {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 10);
                    });
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/odp/import', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil diimport')
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }, async exportData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/odp/export', new FormData(this.formExport))
                        await showAlert('success', 'Data berhasil diimport')
                        this.formExport.reset();
                        this.modalExport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                mapTileLayer(map) {
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);
                },
                async getODPData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational/odp/data');
                        this.odpList = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async selectedArea() {
                    const selectedArea = $('#selectedArea');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/operational/odp/odp-area/selected/${this.editVal.id}`,
                    }).then(function (response) {
                        const option = new Option(response.code, response.id, true, true);
                        selectedArea.append(option).trigger('change');

                        selectedArea.trigger({
                            type: 'select2:select',
                            params: {
                                results: response
                            }
                        });
                    });
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.odpList = resp.data
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
                checkCoords() {
                    const lat = document.getElementById('lat').value;
                    const long = document.getElementById('long').value;

                    if ((this.map && lat && long) || (this.map && this.editVal.lat && this.editVal.long)) {
                        L.marker(L.latLng(this.editVal.lat ?? Number(lat), this.editVal.long ?? Number(long)), {
                            iconSize: [20, 20]
                        }).addTo(this.map).bindPopup(`<b>Lokasi ODP</b>`).openPopup();
                    } else {
                        console.error('Map or coordinates are undefined');
                    }
                },
                async getAreaData() {
                    $(".areas-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kode Area",
                        ajax: {
                            url: '/operational/odp/odp-area/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/odp', new FormData(this.formCreate))
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
                    const resp = await axios.get(`/operational/odp/${id}`);
                    this.editVal = resp.data
                    await this.selectedArea();
                    this.map = null
                    this.map = L.map('map-edit').setView([this.editVal.lat, this.editVal.long], 16);
                    this.mapTileLayer(this.map);

                    L.marker(L.latLng(this.editVal.lat, this.editVal.long), {
                        iconSize: [20, 20]
                    }).addTo(this.map).bindPopup(`<b>Lokasi ODP</b>`).openPopup();

                    $('#modal-edit').on('shown.bs.modal', () => {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 10);
                    });
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/operational/odp/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/operational/odp/destroy`, new FormData(this.formDelete));
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