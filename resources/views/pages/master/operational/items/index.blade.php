@extends('layouts.template')
@section('page-title', 'Master Operasional - Barang')
@section('content')
    <div x-data="itemData()">
        @include('pages.master.operational.items.form')
        @include('pages.master.operational.item-categories.description-drawer')
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
                            @can('Tambah Data Daftar Barang')
                                <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                    <button data-bs-target="#modal-item" data-bs-toggle="modal"
                                            class="btn btn-light-primary btn-sm" @click="add()">
                                        <x-icons.add-item/>
                                        Tambah
                                    </button>
                                    <a href="{{ url('master/operational/items/archives') }}"
                                       class="btn btn-light-dark btn-sm">
                                        <x-icons.archived/>
                                        Arsip Barang
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="col-12">
                            <form id="form-delete" @submit.prevent="destroy()">
                                <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                                <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                        x-show="selectedCheckBox.length > 0 && Number(deletePermission) ===  1"
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
                                        <th class="min-w-125px" colspan="2">Informasi</th>
                                        <th class="min-w-125px">Satuan</th>
                                        <th class="min-w-125px">Actions</th>
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
                                    <template x-if="!isLoading && items.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="item in items?.data" :key="item.id">
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
                                            <td colspan="2 ">
                                                <p class="m-0"
                                                   x-text="`Kelompok Harta : ${item.tangible_asset ?? '-'}`"></p>
                                                <a class="mb-4" href="#"
                                                   id="item_category_description_drawer"
                                                   @click="showItemCategoryDescription(item.category_id)"
                                                   x-text="`Kategori : ${item.category_name ?? '-'}`"></a>
                                                <p x-text="`${item.asset_account_name ? 'Akun Aset : ' + item.asset_account_name : '-' ?? '-'}`"></p>
                                            </td>
                                            <td x-text="item.unit_type_name"></td>
                                            <td>
                                                <button data-bs-target="#modal-item" data-bs-toggle="modal"
                                                        class="btn btn-light-primary btn-sm"
                                                        @click="edit(item.id)">
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
                                <template x-for="pagination in items.links">
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
    @include('components.select2.script')
    @include('components.toast')
@endsection
@push('script')
    <script type="text/javascript">
        function itemData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Daftar Barang') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Daftar Barang') }}",
                items: [],
                isAset: null,
                itemMustHaveCode: false,
                hasSNOnItem: false,
                isLandAsset: false,
                nonBuildingGroup: null,
                isVehicleAsset: false,
                tangibleAsset: null,
                buildingType: null,
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                itemCategoryDescription: '',
                modal: new bootstrap.Modal(document.getElementById('modal-item')),
                form: document.getElementById('form-item'),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getItemCategories();
                    await this.getItems();
                    await select2('.unit-types-select2', 'Pilih Satuan', '/select2/unit-types-data', true, true);
                    await select2('#item-category-id-filter', 'Pilih Kategori', '/select2/item-categories-data', true, false);
                    await select2('.item-category-select2', 'Pilih Kategori', '/select2/item-categories-data', true, false);
                    await select2('.asset-accounts-select2', 'Pilih Akun Aset', '/select2/asset-accounts-data', true, false);
                },
                add() {
                    this.editVal = '';
                    this.form.reset();
                    this.itemMustHaveCode = false;
                    $('#selected-category').val('').trigger('change');
                    $('#selected-unit-type').val('').trigger('change');
                },
                async getItems() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/items/data');
                        this.items = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    try {
                        const resp = await axios.get('/master/operational/items/filter', {
                            params: {
                                category_id: $('#item-category-id-filter').val(),
                                search: this.search
                            }
                        });
                        this.items = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {

                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/operational/items/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.items = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.items = resp.data
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
                async saveItem(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/operational/items', new FormData(this.form))
                                .then(async () => {
                                    await this.successResponse();
                                })
                        } else {
                            await axios.post(`/master/operational/items/update/${id}`, new FormData(this.form))
                                .then(async () => {
                                    await this.successResponse();
                                })
                        }
                    } catch (error) {
                        const respError = error.response?.data.errors;
                        if (respError) {
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getItemCategories() {
                    $(".item-category-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Kategori',
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item-category">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/item-categories-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedAssetAccount() {
                    if (this.editVal.asset_account_id === null) return;
                    const selectedAssetAccount = $('#selected-asset-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.editVal.asset_account_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedAssetAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getAssetAccounts() {
                    $(".asset-accounts-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Akun Aset',
                        ajax: {
                            url: '/select2/asset-accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedUnitType() {
                    const selectedUnitType = $('#selected-unit-type');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-unit-type/${this.editVal.unit_type_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedUnitType.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/unit-types/', new FormData(this.unitTypeForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.unitTypeForm.reset();
                        this.unitTypeModal.hide();
                        this.modalForm.show();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedItemCategory() {
                    if (this.editVal.category_id === null) return;
                    const selectedItemCategory = $('#selected-item-category');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-item-category/${this.editVal.category_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedItemCategory.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async saveItemCategories() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/item-categories', new FormData(this.itemCategoryForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.itemCategoryForm.reset();
                        this.itemCategoryModal.hide();
                        this.modalForm.show();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getUnitTypes() {
                    $(".unit-types-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Satuan',
                        escapeMarkup: markup => (markup),
                        tags: true,
                        ajax: {
                            url: '/select2/unit-types-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async edit(id) {
                    const resp = await axios.get(`/master/operational/items/${id}`);
                    this.editVal = resp.data;


                    this.itemMustHaveCode = this.editVal.must_have_code === 1;
                    this.tangibleAsset = this.editVal.tangible_assets_type;
                    this.isLandAsset = this.editVal.is_land === 1;
                    this.isAset = this.editVal.type;
                    this.isVehicleAsset = this.editVal.is_vehicle === 1;

                    $('#selected-asset-account').val('').trigger('change');


                    await this.selectedItemCategory();
                    await this.selectedUnitType();
                    await this.selectedAssetAccount();

                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/operational/items/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            this.selectedCheckBox = [];
                            const resp = await axios.get(`${this.items.path}?page=${this.items.current_page}`);
                            this.items = resp.data
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    KTDrawer.getInstance(document.querySelector('#item-drawer-action')).hide();
                    const resp = await axios.get(`${this.items.path}?page=${this.items.current_page}`);
                    this.items = resp.data
                },
                async showItemCategoryDescription(id) {
                    try {
                        const resp = await axios.get(`/master/operational/item-categories/${id}`);
                        this.itemCategoryDescription = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                }
            }
        }
    </script>
@endpush
