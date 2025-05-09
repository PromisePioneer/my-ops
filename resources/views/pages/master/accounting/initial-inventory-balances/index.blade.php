@extends('layouts.template')
@section('page-title', 'Saldo Awal Persediaan')
@section('breadcrumbs', 'Master Keuangan - Saldo Awal Persediaan')
@section('content')
    <div x-data="initialInventoryBalance()">
        @include('pages.master.operational.items.form')
        @include('pages.master.accounting.initial-inventory-balances.form')
        @include('pages.master.operational.supplier.form')
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
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            @can('Tambah Data Aset')
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-initial-inventory-balance">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <form id="form-confirm" @submit.prevent="confirm()" class="me-3">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="ki-duotone ki-check-square">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Konfirmasi
                        </button>
                    </form>

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
                        <table class="table align-middle fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                </th>
                                <th class="min-w-125px text-center">Informasi Persediaan</th>
                                <th class="min-w-125px text-center">Akun</th>
                                <th class="min-w-125px text-center">Detail</th>
                                <th class="min-w-125px text-center">Bukti Transaksi</th>
                                <th class="min-w-125px text-center">Status Konfirmasi</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && initialInventoryBalances.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="inventory in initialInventoryBalances?.data" :key="inventory.id">
                                <tr class="text-center">
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox"
                                                   :value="inventory.id"
                                                   :id="'checkbox-' + inventory.id"/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column text-center">
                                            <span x-text="`${inventory.branch_name}`"></span>
                                            <span x-text="`Tgl ${inventory.date}`"></span>
                                            <hr>
                                            <span class="text-decoration-underline"
                                                  x-text="`${inventory.item_name} ${inventory.qty} ${inventory.unit_type} `"></span>
                                            <span x-text="`Total Harga : ${inventory.total_price}`"></span>
                                        </div>
                                    </td>
                                    <td x-text="inventory.stock_account"></td>
                                    <td x-text="inventory.detail"></td>
                                    <td x-text="inventory.detail"></td>
                                    <td x-text="inventory.detail"></td>
                                    <td>
                                        <template x-if="inventory.status == 0">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-initial-inventory-balance"
                                                    @click="edit(inventory.id)">
                                                <i class="ki-duotone ki-pencil">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in initialInventoryBalances.links">
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
        @include('components.toast')
    </div>
@endsection
@push('script')
    <script>

        const modal = new bootstrap.Modal(document.getElementById('modal-initial-inventory-balance'));
        const itemModal = document.getElementById('modal-item');
        const supplierModal = document.getElementById('modal-supplier')


        itemModal.addEventListener('hidden.bs.modal', e => {
            modal.show();
        });

        supplierModal.addEventListener('hidden.bs.modal', e => {
            modal.show();
        });


        Inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            allowMinus: false
        }).mask("#unit_price");


        $('.date').flatpickr();


        function initialInventoryBalance() {
            return {
                isLoading: false,
                buttonLoading: false,
                toggleAllCheckBox: false,
                selectedCheckBox: [],
                initialInventoryBalances: [],
                branchVal: false,
                itemMustHaveCode: false,
                editVal: '',
                search: '',
                isAset: false,
                PKP: false,
                modal: new bootstrap.Modal(document.getElementById('modal-initial-inventory-balance')),
                form: document.getElementById('form-initial-inventory-balance'),
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier')),
                supplierForm: document.getElementById('form-supplier'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                itemForm: document.getElementById('form-item'),
                deleteForm: document.getElementById('form-delete'),
                confirmForm: document.getElementById('form-confirm'),
                async init() {
                    await this.getInitialInventoryBalances();
                    await this.getMainBranches();
                    await this.getSuppliers();
                    await this.getItemCollections();
                    await this.itemCategories();
                    await this.getUnitTypes();
                    await this.getStockAccounts();
                }, selectCheckBox(event) {
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
                async getInitialInventoryBalances() {
                    this.isLoading = false;
                    try {
                        const resp = await axios.get('/master/accounting/initial-inventory-balances/data');
                        this.initialInventoryBalances = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getMainBranches() {
                    const self = this;
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (e) {
                        self.branchVal = true;
                        const selectedMainBranchId = e?.params?.data?.id ?? self.editVal.branch_id;
                        $('.sub-branches-select2').select2({
                            allowClear: true,
                            placeholder: "Pilih Sub Cabang",
                            ajax: {
                                url: `/select2/sub-branches-data/${selectedMainBranchId}`,
                                dataType: "json",
                                type: "GET",
                                data: params => ({search: params.term}),
                                processResults: data => ({results: data}),
                                cache: true
                            }
                        });
                    });
                },
                async getSuppliers() {
                    $(".suppliers-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Supplier",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-supplier">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/suppliers-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    })
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/accounting/initial-inventory-balances/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                            this.uncheckAfterSuccessfulEvent();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/accounting/initial-inventory-balances/confirm`, new FormData(this.confirmForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                            this.uncheckAfterSuccessfulEvent();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async saveItem() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('master/operational/items', new FormData(this.goodsForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.goodsForm.reset();
                        this.itemModal.hide();
                        this.modalForm.show();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async itemCategories() {
                    const self = this;
                    $(".item-category-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kategori Barang",
                        tags: true,
                        ajax: {
                            url: '/select2/item-categories-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    }).on('change', () => {
                        const data = $(".item-category-select2 option:selected").text();
                        self.isAset = data === 'ASET';
                    });
                },
                async getUnitTypes() {
                    $(".unit-types-select2").select2({
                        allowClear: true,
                        tags: true,
                        placeholder: "Pilih Satuan",
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
                async getItemCollections() {
                    $(".items-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/goods-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/accounting/initial-inventory-balances', new FormData(this.form))
                        } else {
                            await axios.post(`/master/accounting/initial-inventory-balances/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        await this.modal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    try {
                        const resp = await axios.get(`/master/accounting/initial-inventory-balances/${id}`);
                        this.editVal = resp.data;
                        await this.selectedSupplier();
                        await this.selectedMainBranches();
                        await this.selectedSubBranch();
                        await this.selectedItem();
                        await this.selectedAccount();
                    } catch (e) {
                        console.log(e)
                    }
                },
                async selectedItem() {
                    const selectedItem = $('#selected-item');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-item/${this.editVal.item_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedItem.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedAccount() {
                    const selectedAccount = $('#selected-stock-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.editVal.stock_account_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedSupplier() {
                    const selectedSupplier = $('#selected-supplier');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-supplier/${this.editVal.supplier_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedSupplier.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedMainBranches() {
                    const self = this;
                    const selectedMainBranch = $('#selected-main-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${self.editVal.branch.parent_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedMainBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {data: response}
                    });
                },

                async selectedSubBranch() {
                    const selectedSubBranch = $('#selected-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${this.editVal.branch_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedSubBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {data: response}
                    })
                },
                async getStockAccounts() {
                    $(".stock-accounts-select2").select2({
                        allowClear: true,
                        tags: true,
                        placeholder: "Pilih Akun Persediaan",
                        ajax: {
                            url: '/select2/stock-accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveSupplier() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/suppliers', new FormData(this.supplierForm))
                            .then(async () => {
                                await showAlert('success', 'data berhasil disimpan');
                                await this.supplierForm.reset();
                                this.supplierModal.hide();
                            })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
