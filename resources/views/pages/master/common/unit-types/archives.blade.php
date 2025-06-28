@extends('layouts.template')
@section('page-title', 'Arsip Satuan')
@section('breadcrumbs', 'Master Umum - Satuan - Arsip')
@section('content')
    <div x-data="unitTypeArchivedData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
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
                <div class="col-12 ">
                    <form id="restore-form" @submit.prevent="restore()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox.filter((val) => val !== 'on')">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <x-icons.restore/>
                            Pulihkan
                        </button>
                    </form>
                    <form id="force-delete-form" @submit.prevent="forceDelete()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox.filter((val) => val !== 'on')">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <x-icons.trash/>
                            Hapus
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px text-center">Nama Satuan</th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="2"/>
                            </template>
                            <template x-if="!isLoading && unitTypes.data?.length === 0">
                                <x-table.empty colspan="2"/>
                            </template>
                            <template x-for="(unitType, index) in unitTypes?.data" :key="unitType.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="unitType.id"
                                                   :id="'checkbox-' + unitType.id"
                                            />
                                        </div>
                                    </td>
                                    <td class="text-center" x-text="unitType.name"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url('/master/common/unit-types') }}" class="btn btn-light-danger btn-sm">
                            <x-icons.back/>
                            Kembali
                        </a>

                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in unitTypes.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function unitTypeArchivedData() {
            return {
                unitTypes: [],
                isLoading: true,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                restoreForm: document.getElementById('restore-form'),
                forceDeleteForm: document.getElementById('force-delete-form'),
                async init() {
                    const unitTypes = await axios.get('/master/common/unit-types/archives/data');
                    this.unitTypes = unitTypes.data
                    this.startIndex = this.unitTypes.from;
                    this.isLoading = false;
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/master/common/unit-types/archives/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.unitTypes = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.unitTypes = resp.data
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
                async restore() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/unit-types/archives/restore`,
                                new FormData(this.restoreForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async forceDelete() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/common/unit-types/archives/force-delete`,
                                new FormData(this.forceDeleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            await showAlert('error', error.response.data.message);
                        }
                    });
                },
            }
        }
    </script>
@endpush
