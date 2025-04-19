@extends('layouts.template')
@section('page-title', 'Kategori Akun')
@section('breadcrumbs', 'Master Keuangan - Kategori Akun')
@section('content')
    <div x-data="accountCategoriesData()">
        @include('pages.master.accounting.account-categories.form')
        @include('pages.master.accounting.account-categories.form-sub-category')
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        @can('Tambah Data Kategori Akun')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    @click="add()"
                                    data-bs-target="#modal-category">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        @endcan
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="separator my-10"></div>
            <div class="card-body py-3">
                <div class="col-12">
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
                                        <input class="form-check-input"
                                               type="checkbox" @click="toggleAllCheckBox()"
                                               :disabled="Number(deletePermission) !== 1"
                                        >
                                    </div>
                                </th>
                                <th class="min-w-125px text-center">Akun</th>
                                <th class="min-w-125px text-center">Aksi</th>
                            </tr>
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
                            <template x-if="!isLoading && accountCategories.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(category, index) in accountCategories.data"
                                      :key="category.account_category_id">
                                <tbody style="cursor:pointer" class="fw-bold">
                                <tr :id="category.account_category_id" @click="expand($event)">
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox"
                                                   :value="category.account_category_id"
                                                   :id="'checkbox-' + category.account_category_id"
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" x-text="category.account_category_name"></a>
                                    </td>
                                    <td>
                                        <template x-if="Number(editPermission) === 1">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-category"
                                                    @click="edit(category.account_category_id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </template>
                                        <template x-if="Number(editPermission) === 1">
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-sub-category"
                                                @click="edit(category.account_category_id)">
                                            <i class="ki-duotone ki-add-folder">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        </template>
                                    </td>
                                </tr>
                                <template x-for="subCategory in category.sub_categories">
                                    <tr :id="subCategory.sub_account_category_id" @click="expand($event)"
                                        x-transition:enter.duration.500ms
                                        x-transition:leave.duration.400ms>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox"
                                                       :value="subCategory.sub_account_category_id"
                                                       :id="'checkbox-' + subCategory.sub_account_category_id"
                                                />
                                            </div>
                                        </td>
                                        <td placement="center"
                                            x-text="subCategory.sub_account_category_name"></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-sub-category"
                                                    @click="edit(subCategory.sub_account_category_id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in accountCategories.links">
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
    <script>
        function accountCategoriesData() {
            return {
                createPermission: "{{  request()->user()->can('Tambah Data Kategori Akun') }}",
                editPermission: "{{ request()->user()->can('Edit Data Kategori Akun') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Kategori Akun') }}",
                accountCategories: [],
                buttonLoading: false,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                akunId: '',
                selectedRow: [],
                expandRows: false,
                modalCategory: new bootstrap.Modal(document.getElementById('modal-category')),
                formCategory: document.getElementById('form-category'),
                modalSubCategory: new bootstrap.Modal(document.getElementById('modal-sub-category')),
                formSubCategory: document.getElementById('form-sub-category'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getAccountCategories();
                },
                add() {
                    this.editVal = '';
                },
                toggleAllCheckBox() {
                    this.selectAll = true;
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
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/master/accounting/account-categories/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.accountCategories = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.accountCategories = resp.data
                    }
                },
                expand(event) {
                    const expandedData = event.target.parentNode.id;
                    this.selectedRow.push(Number(expandedData));
                    this.selectedRow = this.selectedRow.filter((val) => val === Number(expandedData))
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/account-categories', new FormData(this.formCategory))
                            .then(async () => {
                                await this.successResponse();
                            });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveChild(id) {
                    this.buttonLoading = true;
                    try {
                        if (!this.editVal.parent_id) {
                            await axios.post(`/master/accounting/account-categories/create-child/${id}`,
                                new FormData(this.formSubCategory)).then(async () => {
                                await this.subCategorySuccessResponse();
                            });
                        } else {
                            await axios.post(`/master/accounting/account-categories/update-child/${id}`,
                                new FormData(this.formSubCategory)).then(async () => {
                                await this.subCategorySuccessResponse();
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
                    const resp = await axios.get(`/master/accounting/account-categories/edit/${id}`);
                    this.editVal = resp.data;
                    console.log(this.editVal);
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {

                        await axios.post(`/master/accounting//update/${id}`,
                            new FormData(this.formEditChildren))
                        await showAlert('success', 'Data berhasil diubah')
                        this.formEditChildren.reset();
                        this.modalEditChildren.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },

                async updateParent(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/accounting/account-categories/update/${id}`,
                            new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil diubah')
                        this.formEdit.reset();
                        this.modalEdit.hide();
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
                            await axios.post(`/master/accounting/account-categories/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/accountCategories/import/', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAccountCategories() {
                    this.isLoading = true;
                    try {
                        const accountCategories = await axios.get('/master/accounting/account-categories/data');
                        this.accountCategories = accountCategories.data
                        this.startIndex = this.accountCategories.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.formCategory.reset();
                    this.modalCategory.hide();
                    const resp = await axios.get(`${this.accountCategories.path}?page=${this.accountCategories.current_page}`);
                    this.accountCategories = resp.data
                    this.editVal = '';
                },
                async subCategorySuccessResponse() {
                    await showAlert('success', 'Data berhasil disimpan');
                    const resp = await axios.get(`${this.accountCategories.path}?page=${this.accountCategories.current_page}`);
                    this.accountCategories = resp.data
                    this.formSubCategory.reset();
                    this.modalSubCategory.hide();
                    this.editVal = '';
                }
            }
        }
    </script>
@endpush
