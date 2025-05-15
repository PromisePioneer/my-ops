@extends('layouts.template')
@section('page-title', 'Master Operasional - Barang - Arsip Barang')
@section('content')

    <div x-data="archivedItemCollections()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-250px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid item-category-select2"
                                            name="branch_id" id="item-category-id-filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
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
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                <a href="{{ url('master/operational/items') }}"
                                   class="btn btn-light-danger btn-sm">
                                    <x-icons.back/>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="col-12">
                            <form id="restore-form" @submit.prevent="restore()">
                                <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                                <button type="submit" class="btn btn-light-primary btn-sm mt-5"
                                        x-show="selectedCheckBox.length > 0"
                                        x-transition x-cloak>
                                    <x-icons.restore/>
                                    Pulihkan
                                </button>
                            </form>
                        </div>
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-125px">Nama</th>
                                        <th class="min-w-125px">Kategori</th>
                                        <th class="min-w-125px">Material</th>
                                        <th class="min-w-125px">Satuan</th>
                                    </thead>
                                    <template x-if="isLoading">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="5">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && itemCollections.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="5">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="item in itemCollections?.data" :key="item.id">
                                        <tbody class="fw-bold text-center">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <input class="form-check-input" type="checkbox" :value="item.id"
                                                           :id="'checkbox-' + item.id"/>
                                                </div>
                                            </td>
                                            <td>
                                                <span x-text="item.name"></span>
                                                <span
                                                    :class="item.type === 'ASET' ? 'badge badge top-100 start-0 badge-warning ms-2' : 'badge badge top-100 start-0 badge-danger ms-2'"
                                                    x-text="item.type"></span>
                                            </td>
                                            <td>
                                                <template x-if="item.asset_account_name === null">
                                                    <button class="btn btn-light-info btn-sm"
                                                            id="item_category_description_drawer"
                                                            @click="showItemCategoryDescription(item.category_id)"
                                                            x-text="item.category_name">
                                                    </button>
                                                    <span x-text="item.category_name"></span>
                                                </template>
                                                <template x-if="item.asset_account_name !== null">
                                                    <span
                                                        x-text="`${item.category_name} - ${item.asset_account_name}`"></span>
                                                </template>
                                            </td>
                                            <td x-text="item.material"></td>
                                            <td x-text="item.unit_type_name"></td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in itemCollections.links">
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
@endsection
@push('script')
    <script>
        function archivedItemCollections() {
            return {
                isLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                itemCollections: [],
                search: '',
                restoreForm: document.getElementById('restore-form'),
                async init() {
                    await this.getArchivedItems();
                    await this.getItemCategories();
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
                async getArchivedItems() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/items/archives/data');
                        this.itemCollections = resp.data
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/items/archives/search', {
                            params: {
                                search: this.search
                            }
                        });
                        this.itemCollections = resp.data
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    try {
                        const resp = await axios.get('/master/operational/items/archives/filter', {
                            params: {
                                category_id: $('#item-category-id-filter').val(),
                                search: this.search
                            }
                        });
                        this.itemCollections = resp.data
                    } catch (e) {
                        console.log(e);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.itemCollections = resp.data
                    }
                },
                async getItemCategories() {
                    $(".item-category-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Kategori',
                        ajax: {
                            url: '/select2/item-categories-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    })
                },
                async restore() {
                    showConfirmModal("Anda yakin?", "", "Ya, Pulihkan Data!", async () => {
                        try {
                            await axios.post(`/master/operational/items/archives/restore`, new FormData(this.restoreForm));
                            await showAlert('success', 'Data sukses dipulihkan');
                            this.selectedCheckBox = [];
                            await this.getArchivedItems();
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
