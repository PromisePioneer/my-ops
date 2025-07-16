@extends('layouts.template')
@section('page-title', 'Master Operasional - Kategori Barang')
@section('content')
    <div x-data="itemCategoriesData()">
        @include('pages.master.operational.item-categories.description-drawer')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.operational.item-categories.form')
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
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-item-category" @click="add()">
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
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Barang Aset</th>
                                <th class="min-w-125px">Barang Jual</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="3">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && itemCategories.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="3">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="category in itemCategories?.data" :key="category.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="category.id"
                                                   :id="'checkbox-' + category.id"/>
                                        </div>
                                    </td>
                                    <td x-text="category.name"></td>
                                    <td>
                                        <ul>

                                            <template x-for="assetItem in category.asset_items" :key="assetItem.id">
                                                <li x-text="assetItem.name"></li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>

                                            <template x-for="sellItems in category.sell_items" :key="sellItems.id">
                                                <li x-text="sellItems.name"></li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-item-category" @click="edit(category.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button id="item_category_description_drawer" class="btn btn-light-info btn-sm">
                                            <i class="ki-duotone ki-information fs-2" @click="edit(category.id)">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in itemCategories.links">
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
@endsection
@push('script')
    <script defer>
        function itemCategoriesData() {
            return {
                itemCategories: [],
                isLoading: true,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                itemCategoryDescription: '',
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-item-category'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-item-category')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getItemCategories();
                },

                async searchData() {
                    try {
                        const resp = await axios.get('/master/operational/item-categories/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.itemCategories = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async add() {
                    this.form.reset();
                    this.editVal = '';
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.itemCategories = resp.data
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
                async saveItemCategories(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/operational/item-categories', new FormData(this.form))
                                .then(async () => {
                                    await this.successResponse();
                                });
                        } else {
                            await axios.post(`/master/operational/item-categories/${id}`, new FormData(this.form)).then(async () => {
                                await this.successResponse();
                            });
                        }
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/operational/item-categories/${id}`);
                    this.editVal = resp.data;
                    this.itemCategoryDescription = resp.data;
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/operational/item-categories/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                            const resp = await axios.get(`${this.itemCategories.path}?page=${this.itemCategories.current_page}`);
                            this.itemCategories = resp.data
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getItemCategories() {
                    const itemCategories = await axios.get('/master/operational/item-categories/data');
                    this.itemCategories = itemCategories.data
                    this.isLoading = false;
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    this.modalForm.hide();
                    const resp = await axios.get(`${this.itemCategories.path}?page=${this.itemCategories.current_page}`);
                    this.itemCategories = resp.data
                },
                async show(id) {
                    this.isLoading = true;
                    try {

                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
