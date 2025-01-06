@extends('layouts.template')
@section('page-title', 'Inventory Controller - Tambah Daftar Barang')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>

    <div x-data="generateListOfItem">
        @include('pages.operational-master-data.items.modal.create')
        @include('pages.operational-master-data.supplier.modal.create')
        @include('pages.general-master-data.unit-types.modal.create')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('inventory/list-of-items/po') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                @csrf
                <div class="card-body">
                    <form id="form" @submit.prevent="save()">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label fw-bold fs-6">Cabang</label>
                                <select name="branch_id" id="branch_id"
                                        class="form-select form-select-solid branch-select2">
                                    <option></option>
                                </select>
                                <p class="text-danger mt-2">Kosongkan jika barang untuk stok gudang</p>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal</label>
                                <input type="date" name="date"
                                       class="form-control form-control-lg form-control-solid date"
                                       placeholder="Tanggal"/>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">No. Invoice</label>
                                <input type="text" class="form-control form-control-solid" name="invoice_number"
                                       id="invoice_number"
                                       placeholder="No. Invoice">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">No. PO</label>
                                <input type="text" class="form-control form-control-solid" name="po_number"
                                       id="po_number" placeholder="No. PO">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Nama Barang</label>
                                <select name="item_id" id="item_id"
                                        class="form-select form-select-solid items-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Harga Satuan</label>
                                <input type="number" class="form-control form-control-solid" name="unit_price"
                                       id="unit_price" placeholder="Harga Satuan">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label fw-bold fs-6">Ongkos Kirim (Kalau ada)</label>
                                <input type="number" class="form-control form-control-solid" name="shipping_cost"
                                       id="shipping_cost"
                                       placeholder="Ongkos Kirim">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Supplier</label>
                                <select name="supplier_id" id="supplier_id"
                                        class="form-select form-select-solid supplier-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Kuantitas</label>
                                <input type="number" class="form-control form-control-solid" name="qty"
                                       id="qty"
                                       placeholder="Kuantitas">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Satuan</label>
                                <select name="unit_type_id" id="unit_type_id"
                                        class="form-select form-select-solid unit-type-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Resi Surat Jalan</label>
                                <input type="text"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Resi Surat Jalan" name="travel_letter_receipt"/>
                            </div>
                        </div>

                        <div class="separator py-2"></div>

                        <div class="d-flex mt-4">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="0" name="ppn"
                                       id="flexCheckChecked"/>
                                <label class="form-check-label fw-bold" for="flexCheckChecked">
                                    Tambahkan PPN
                                </label>
                            </div>
                        </div>

                        <div class="float-end d-flex py-6 px-9">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset
                            </button>
                            <button type="submit" class="btn btn-sm btn-light-primary"
                                    :disabled="buttonLoading">
                                <i class="ki-duotone ki-click fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                </i>
                                <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function generateListOfItem() {
            return {
                buttonLoading: false,
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier-create')),
                supplierForm: document.getElementById('form-supplier-create'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item-create')),
                itemForm: document.getElementById('form-item-create'),
                unitTypeModal: new bootstrap.Modal(document.getElementById('modal-unit-type-create')),
                unitTypeForm: document.getElementById('unit-types-store'),
                form: document.getElementById('form'),
                async init() {
                    await this.getSupplierData();
                    await this.getBranchData();
                    await this.getItem();
                    await this.getUnitType();
                    await this.getItemCategories();
                },
                async getSupplierData() {
                    $(".supplier-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Supplier",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-supplier-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/list-of-items/po/supplier/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getItemCategories() {
                    $(".item-categories-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Kategori',
                        ajax: {
                            url: '/operational-master-data/items/item-categories/data',
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
                        await axios.post('/operational-master-data/suppliers', new FormData(this.supplierForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.supplierForm.reset();
                        this.supplierModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/inventory/list-of-items/po/store', new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = '/inventory/list-of-items/po/';
                    } catch (error) {
                        console.log(error);
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/list-of-items/po/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getItem() {
                    $(".items-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/list-of-items/po/items/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                getUnitType() {
                    $(".unit-type-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Satuan",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-unit-type-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/list-of-items/po/unit-types/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveUnitTypes() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/general-master-data/unit-types/', new FormData(this.unitTypeForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.unitTypeForm.reset();
                        this.unitTypeModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveItem() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/operational-master-data/items', new FormData(this.itemForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.itemForm.reset();
                        this.itemModal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
