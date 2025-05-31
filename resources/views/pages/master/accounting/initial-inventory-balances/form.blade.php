@extends('layouts.template')
@section('page-title', 'Tambah Saldo Awal Persediaan')
@section('breadcrumbs', 'Master Accounting - Saldo Awal Persediaan - Tambah Saldo Awal Persediaan')
@section('content')
    @push('styles')
        <style>
            .select2-dropdown {
                z-index: 1 !important;
            }
        </style>
    @endpush
    <div x-data="generateInitialInventoryBalance()">
        @include('pages.master.operational.items.form')
        @include('pages.master.operational.supplier.form')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <a class="btn btn-light-danger btn-sm"
                               href="{{ url('master/accounting/initial-inventory-balances/') }}">
                                <x-icons.back/>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form id="form-initial-inventory-balance" class="m-0 p-0"
                  @submit.prevent="saveInitialInventoryBalance(initialInventoryBalanceId)"
                  enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row mb-10">
                        @if(empty(Auth::user()->branch_id))
                            <div class="col-md-4">
                                <label for="branch_id" class="required form-label">Cabang</label>
                                <x-select2.index
                                    class="form-select form-select-solid"
                                    name="branch_id"
                                    id="selected-branch"
                                    elementSelector="branches-select2"
                                >
                                    <option></option>
                                </x-select2.index>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label for="date" class="required form-label">Tanggal Pembelian</label>
                            <input type="date" id="date" name="date" class="form-control-solid form-control date"
                                   placeholder="Tanggal Pembelian"
                                   value="{{ $initialInventoryBalance->date ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="date" class="required form-label">Supplier</label>
                            <select name="supplier_id" id="selected-supplier"
                                    class="form-select form-select-solid suppliers-select2">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <label for="detail" class="required form-label">
                            Keterangan
                        </label>
                        <textarea class="form-control form-control-solid" name="detail" id="detail"
                                  placeholder="Ketarangan"
                                  data-kt-autosize="true">{{ isset($initialInventoryBalance) ? $initialInventoryBalance->detail : null }}
                        </textarea>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-4">
                            <label for="name" class="required form-label">
                                Nama Barang
                            </label>
                            <x-select2.index
                                class="form-select form-select-solid"
                                name="item_id"
                                id="selected-item"
                                elementSelector="items-select2"
                            >
                                <option></option>
                            </x-select2.index>
                        </div>

                        <div class="col-md-4">
                            <label for="name" class="required form-label">
                                Harga Satuan
                            </label>
                            <input type="text" class="form-control form-control-solid" name="unit_price"
                                   id="unit_price"
                                   :value="parseFloat(unitPrice) ?? ''"
                                   placeholder="Harga Satuan"/>
                        </div>
                        <div class="col-lg-4">
                            <label for="name" class="required form-label">Qty</label>
                            <input type="number" class="form-control form-control-solid" name="qty" id="qty"
                                   placeholder="Kuantitas" value="{{  $initialInventoryBalance->qty ?? '' }}">
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Dokumentasi</label>
                            <input type="file" class="form-control form-control-solid"
                                   @change="previewAttachmentFile()"
                                   accept=".png, .jpg, .jpeg" x-ref="attachmentFile" name="attachment"
                                   id="attachment">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Akun Persediaan</label>
                            <x-select2.index
                                class="form-select form-select-solid"
                                name="stock_account_id"
                                id="selected-stock-account"
                                elementSelector="stock-accounts-select2"
                            >
                                <option></option>
                            </x-select2.index>
                        </div>
                    </div>


                    <div class="row mb-4" x-show="attachmentImgSrc.length > 0" x-transition x-cloak>
                        <label
                            :class="`${attachmentImgSrc.length > 0 ? 'col-form-label required fw-bold fs-6' : 'd-none'}`">
                            Preview
                        </label>
                        <img :src="attachmentImgSrc"
                             :class="`${attachmentImgSrc.length > 0 ? 'img-thumbnail ' : ''}`"
                             @click="openAttachmentImage(attachmentImgSrc)">
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end p-4">
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
    @include('components.toast')
    @include('components.select2.script')
    @include('components.input-mask')
@endsection
@push('script')
    <script defer>
        function generateInitialInventoryBalance() {
            $('.date').flatpickr();
            return {
                buttonLoading: false,
                isAset: null,
                editVal: '',
                initialInventoryBalanceId: "{{ $initialInventoryBalance->id ?? '' }}",
                branchId: "{{ $initialInventoryBalance->branch_id ?? '' }}",
                supplierId: "{{ $initialInventoryBalance->supplier_id ?? '' }}",
                itemId: "{{ $initialInventoryBalance->item_id ?? '' }}",
                stockAccountId: "{{ $initialInventoryBalance->stock_account_id ?? '' }}",
                unitPrice: "{{ $initialInventoryBalance->unit_price ?? '' }}",
                itemMustHaveCode: false,
                hasSNOnItem: false,
                isLandAsset: false,
                nonBuildingGroup: null,
                isVehicleAsset: false,
                tangibleAsset: null,
                buildingType: null,
                PKP: false,
                attachmentImgSrc: '',
                form: document.getElementById('form-initial-inventory-balance'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                itemForm: document.getElementById('form-item'),
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier')),
                supplierForm: document.getElementById('form-supplier'),
                async init() {
                    inputMask('unit_price', 'decimal');
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data');
                    await select2('.suppliers-select2', 'Pilih Supplier', '/select2/suppliers-data');
                    await select2('.asset-accounts-select2', 'Pilih Akun Aset', '/select2/asset-accounts-data');
                    await select2('.stock-accounts-select2', 'Pilih Akun Persediaan', '/select2/stock-accounts-data');
                    await select2('.items-select2', 'Pilih Barang', '/select2/goods-data', true, false, 'modal-item');
                    await select2('.unit-types-select2', 'Pilih Satuan', '/select2/unit-types-data', true, true);
                    await select2('.item-category-select2', 'Pilih Kategori Barang', '/select2/item-categories-data');
                    await selectedValue('selected-branch', `/select2/selected-branch/${this.branchId}`);
                    await selectedValue('selected-supplier', `/select2/selected-supplier/${this.supplierId}`);
                    await selectedValue('selected-item', `/select2/selected-item/${this.itemId}`);
                    await selectedValue('selected-stock-account', `/select2/selected-account/${this.stockAccountId}`);
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
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveItem() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/items', new FormData(this.itemForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.itemForm.reset();
                        this.itemModal.hide();
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
                async saveInitialInventoryBalance(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (id === '') {
                            await axios.post('/master/accounting/initial-inventory-balances/store', new FormData(this.form))
                        } else {
                            await axios.post(`/master/accounting/initial-inventory-balances/update/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = '/master/accounting/initial-inventory-balances/';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        if (respError) {
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                previewAttachmentFile() {
                    let files = this.$refs.attachmentFile.files;
                    if (!files.length) return;

                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;

                        let reader = new FileReader();
                        reader.onload = e => {
                            this.attachmentImgSrc = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                },
                openAttachmentImage() {
                    const lightbox = new FsLightbox();
                    const storage = "{{ Storage::url('')  }}"
                    if (this.editVal) {
                        lightbox.props.sources = [storage + this.attachmentImgSrc[0]];
                    }
                    lightbox.props.sources = [this.attachmentImgSrc];
                    lightbox.open();
                },
            }
        }
    </script>
@endpush
