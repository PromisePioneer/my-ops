@extends('layouts.template')
@section('page-title', 'Data Kabel FO')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
    @endpush

    @include('pages.operational.fo-cables.header')
    <div x-data="foCablesData()">
        @include('pages.operational.fo-cables.modal.import')
        @include('pages.operational.fo-cables.modal.export')
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
                            <a href="{{ url('/operational/fo-cables/create') }}" type="button"
                               class="btn btn-light-primary btn-sm mr-4">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Tambah
                            </a>
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
                                <th class="text-center">Segmen</th>
                                <th class="text-center">Klasifikasi</th>
                                <th class="text-center">Letak Kabel</th>
                                <th class="text-center">Jalur Kabel</th>
                                <th class="text-center">Jumlah Core</th>
                                <th class="text-center">Titik Awal</th>
                                <th class="text-center">Titik Akhir</th>
                                <th class="text-center">Panjang Kabel</th>
                                <th class="text-center">Tanggal Cut Off</th>
                                <th class="text-center">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="11">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && cables.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="11">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="cable in cables?.data" :key="cable.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="cable.id"
                                                   :id="'checkbox-' + cable.id"/>
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="cable.segment"></td>
                                    <td class="text-center" x-text="cable.classification"></td>
                                    <td class="text-center" x-text="cable.cable_placement"></td>
                                    <td class="text-center" x-text="cable.cable_address"></td>
                                    <td class="text-center" x-text="cable.total_core"></td>
                                    <td class="text-center" x-text="cable.coordinates_start_at"></td>
                                    <td class="text-center" x-text="cable.coordinates_end_at"></td>
                                    <td class="text-center" x-text="cable.length"></td>
                                    <td class="text-center" x-text="cable.cut_off_date"></td>
                                    <td class="text-center">
                                        <a :href="`/operational/fo-cables/${cable.id}`"
                                           class="btn btn-light-primary btn-sm">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in cables.links">
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

        function foCablesData() {
            return {
                search: '',
                editVal: '',
                isLoading: false,
                cables: [],
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                formDelete: document.getElementById('form-delete'),
                modalExport: new bootstrap.Modal(document.getElementById('modal-export')),
                formExport: document.getElementById('form-export'),
                map: null,
                startingPointLat: null,
                startingPointLong: null,
                endingPointLat: null,
                endingPointLong: null,
                polyline: null,
                async init() {
                    await this.getFoCablesData();
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
                        await axios.post('/operational/fo-cables/import', new FormData(this.formImport))
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
                },
                async exportData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational/fo-cables/export', new FormData(this.formExport))
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
                async getFoCablesData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/operational/fo-cables/data');
                        this.cables = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.cables = resp.data
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
                        await axios.post('/operational/fo-cables', new FormData(this.formCreate))
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
                    this.map = null;
                    this.map = L.map('map-edit').setView([1.6704852, 101.4394371], 16);

                    const resp = await axios.get(`/operational/fo-cables/${id}`);
                    this.editVal = resp.data;

                    this.mapTileLayer(this.map);

                    $('#modal-edit').on('shown.bs.modal', () => {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 10);
                    });
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/operational/fo-cables/update/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.map.remove();
                        this.map = null;
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
                            await axios.post(`/operational/fo-cables/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async selectedBranchData() {
                    const selectedBranch = $('#selectedBranch');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/master/fo-cables/branch/selected/${id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedBranch.append(option).trigger('change');

                        selectedBranch.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                }
            }
        }
    </script>
@endpush