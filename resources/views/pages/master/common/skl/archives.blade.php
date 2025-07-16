@extends('layouts.template')
@section('page-title', 'Arsip Syarat Ketentuan Layanan')
@section('breadcrumbs', 'Master Umum - Syarat Ketentuan Layanan - Arsip')
@section('content')
    <div x-data="sklArchivedData()">
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
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox.filter((val) => val !== 'on' )">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <x-icons.trash/>
                            Hapus Permanen
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="2"/>
                            </template>
                            <template x-if="!isLoading && skl.data?.length === 0">
                                <x-table.empty colspan="2"/>
                            </template>
                            <template x-for="service in skl?.data" :key="service.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="service.id"
                                                   :id="'checkbox-' + service.id"
                                            />
                                        </div>
                                    </td>
                                    <td x-text="service.name"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('master/common/skl') }}" class="btn btn-light-danger btn-sm">
                            <x-icons.back/>
                            Kembali
                        </a>
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in skl.links">
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
    <script>
        function sklArchivedData() {
            return {
                skl: [],
                isLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                restoreForm: document.getElementById('restore-form'),
                forceDeleteForm: document.getElementById('force-delete-form'),
                async init() {
                    await this.getSklData();
                },
                async getSklData() {
                    this.isLoading = false;
                    try {
                        const resp = await axios.get('/master/common/skl/archives/data');
                        this.skl = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/master/common/skl/archives/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.skl = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    this.isLoading = true;
                    try {
                        if (url) {
                            const resp = await axios.get(`${url}`);
                            this.skl = resp.data
                        }
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
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
                    showConfirmModal("Anda yakin?", "Data akan dipulihkan kembali.", "Ya, Pulihkan!", async () => {
                        try {
                            await axios.post(`/master/common/skl/archives/restore`, new FormData(this.restoreForm));
                            await showAlert('success', 'Data berhasil dipulihkan');
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
                            await axios.post(`/master/common/skl/archives/force-delete`, new FormData(this.forceDeleteForm));
                            await showAlert('success', 'Data berhasil dihapus secara permanen');
                            await this.init();
                            this.selectedCheckBox = [];
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
