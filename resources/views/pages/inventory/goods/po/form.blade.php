@extends('layouts.template')
@section('page-title', 'Inventory Controller - Tambah Daftar Barang')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>

    <div x-data="generatePO">
        @include('pages.operational-master-data.goods.modal.form')
        @include('pages.operational-master-data.supplier.modal.form')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-info btn-sm mb-6" href="{{ url('inventory/goods/po') }}">Kembali</a>
            </div>
            <div class="card-body py-3">
                @csrf
                <div class="card-body">
                    <form id="form" @submit.prevent="save()">
                        <div class="row mb-4">
                            <div class="col-md-6" x-model="placement">
                                <label class="col-form-label fw-bold fs-6">Penempatan Barang</label>
                                <select class="form-select form-select-solid" name="placement" id="placement">
                                    <option value="">Pilih Penempatan</option>
                                    <option value="Pusat">Pusat</option>
                                    <option value="Cabang">Cabang</option>
                                </select>
                            </div>
                            <div class="col-md-6" x-show="placement === 'Pusat'" x-transition x-cloak>
                                <label class="col-form-label fw-bold fs-6">Gudang</label>
                                <select name="warehouse_id" id="selected-warehouse"
                                        class="form-select form-select-solid warehouses-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6" x-show="placement === 'Cabang'" x-transition x-cloak>
                                <label class="col-form-label fw-bold fs-6">Cabang</label>
                                <select :name="`${placement === 'Cabang' ? 'branch_id' : ''}`" id="selected-branch"
                                        class="form-select form-select-solid branches-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Tanggal</label>
                                <input type="date" name="date"
                                       class="form-control form-control-lg form-control-solid date"
                                       placeholder="Tanggal" value="{{ $goodsPurchaseOrder->date ?? '' }}"/>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">No. PO</label>
                                <input type="text" name="po_number"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Nomor PO" value="{{ $goodsPurchaseOrder->po_number ?? '' }}"/>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">No. Invoice</label>
                                <input type="text" class="form-control form-control-solid" name="invoice_number"
                                       id="invoice_number"
                                       placeholder="No. Invoice"
                                       value="{{ $goodsPurchaseOrder->invoice_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="col-form-label required fw-bold fs-6">Nama Barang</label>
                                <select name="item_id" id="selected-goods"
                                        class="form-select form-select-solid goods-select2">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Harga Satuan</label>
                                <input type="number" class="form-control form-control-solid" name="unit_price"
                                       id="unit_price" placeholder="Harga Satuan"
                                       value="{{ $goodsPurchaseOrder->unit_price ?? '' }}">
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label fw-bold fs-6">Ongkos Kirim (Kalau ada)</label>
                                <input type="number" class="form-control form-control-solid" name="shipping_cost"
                                       id="shipping_cost"
                                       placeholder="Ongkos Kirim"
                                       value="{{ $goodsPurchaseOrder->shipping_cost ?? '' }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Supplier</label>
                                <select name="supplier_id" id="selected-supplier"
                                        class="form-select form-select-solid supplier-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Kuantitas</label>
                                <input type="number" class="form-control form-control-solid" name="qty"
                                       id="qty"
                                       placeholder="Kuantitas" value="{{ $goodsPurchaseOrder->qty ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="col-form-label required fw-bold fs-6">Resi Surat Jalan</label>
                                <input type="text"
                                       class="form-control form-control-lg form-control-solid"
                                       placeholder="Resi Surat Jalan" name="travel_letter_receipt"
                                       value="{{ $goodsPurchaseOrder->travel_letter_receipt ?? '' }}"/>
                            </div>
                        </div>

                        <div class="separator py-2"></div>

                        <div class="d-flex mt-4">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="0" name="ppn"
                                       id="flexCheckChecked"
                                    {{ isset($goodsPurchaseOrder) ? $goodsPurchaseOrder->ppn === 1 ? 'checked' : '' : '' }}
                                />
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

        function generatePO() {
            return {
                snPerPO: false,
                editVal: '',
                placement: null,
                id: "{{ $goodsPurchaseOrder->id ?? null }}",
                branchId: "{{ $goodsPurchaseOrder->branch_id ?? null }}",
                warehouseId: "{{ $goodsPurchaseOrder->warehouse_id ?? null }}",
                hasSNOnItem: false,
                needSN: false,
                buttonLoading: false,
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier')),
                supplierForm: document.getElementById('form-supplier'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                itemForm: document.getElementById('form-item'),
                form: document.getElementById('form'),
                async init() {
                    await this.getSupplierData();
                    await this.getBranchData();
                    await this.getItem();
                    await this.getUnitType();
                    await this.selectedSupplier();
                    await this.selectedGoods();
                    await this.getWarehouses();


                    if (this.branchId !== '') {
                        this.placement = 'Cabang';
                        document.getElementById("placement").value = "Cabang";
                        await this.selectedBranch();

                    }

                    if (this.warehouseId !== '') {
                        this.placement = 'Pusat';
                        document.getElementById("placement").value = "Pusat";
                        await this.selectedWarehouse();
                    }

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
                            url: '/inventory/goods/po/supplier/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                }, async selectedGoods() {
                    if (this.id === '') return
                    const selectedGoods = $('#selected-goods');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/inventory/goods/po/goods/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedGoods.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getWarehouses() {
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Gudang',
                        ajax: {
                            url: '/inventory/goods/po/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedWarehouse() {
                    if (this.id === '') return
                    const selectedWarehouse = $('#selected-warehouse');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/inventory/goods/po/warehouses/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedWarehouse.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
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
                        if (!this.id) {
                            await axios.post('/inventory/goods/po/store', new FormData(this.form))
                        } else {
                            await axios.post(`/inventory/goods/po/update/${this.id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = '/inventory/goods/po/';
                    } catch (error) {
                        console.log(error);
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/goods/po/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    if (this.id === '') return
                    const selectedBranch = $('#selected-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/inventory/goods/po/branch/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedSupplier() {
                    if (this.id === '') return
                    const selectedSupplier = $('#selected-supplier');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/inventory/goods/po/supplier/selected/${this.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedSupplier.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getItem() {
                    $(".goods-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item-create">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/inventory/goods/po/goods/data',
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
                            url: '/inventory/goods/po/unit-types/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
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
