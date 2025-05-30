@extends('layouts.template')
@section('page-title', 'Form Transaksi')
@section('breadcrumbs', 'Transaksi - Tambah Transaksi')
@section('content')
    @push('styles')
        <style>
            .select2-dropdown {
                z-index: 1 !important;
            }
        </style>
    @endpush
    <div x-data="generateTransactions()">
        @include('pages.master.operational.items.form')
        @include('pages.master.operational.supplier.form')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6" href="{{ url('/transactions') }}">
                    <x-icons.back/>
                    Kembali
                </a>
            </div>
            <form id="form" @submit.prevent="save(transactionId)">
                <div class="card-body">

                    <div class="row mb-4">
                        @if(empty($transaction))
                            <div class="col-lg-6">
                                <label class="form-label required">Tipe Transaksi</label>
                                <select name="type" id="type" class="form-select form-select-solid"
                                        x-model="transactionType">
                                    <option>--- Pilih ---</option>
                                    <option value="Barang">Barang</option>
                                    <option value="Beban">Beban</option>
                                    <option value="Hutang">Hutang</option>
                                    <option value="Piutang">Piutang</option>
                                </select>
                            </div>
                        @else
                            <div class="col-lg-6">
                                <input type="text" class="form-control form-control-solid" :value="transactionType">
                            </div>
                        @endif
                    </div>


                    <div x-show="transactionType === 'Barang'" x-transition x-cloak>
                        @include('pages.transactions.form-types.item')
                    </div>
                </div>

                <div class="separator py-2"></div>

                <div class="float-end d-flex py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
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
@endsection
@push('script')
    <script>
        function generateTransactions() {
            $('.date').flatpickr();
            return {
                buttonLoading: false,
                isAset: null,
                itemMustHaveCode: false,
                hasSNOnItem: false,
                isLandAsset: false,
                nonBuildingGroup: null,
                isVehicleAsset: false,
                tangibleAsset: null,
                buildingType: null,
                PKP: false,
                attachmentImgSrc: [],
                taxInvoiceImgSrc: [],
                attachments: [],
                userList: [],
                editVal: '',
                transactionId: "{{ $transaction?->id ?? '' }}",
                transactionType: "{{ $transaction?->type ?? '' }}",
                unitPrice: "{{ $transaction->unit_price ?? '' }}",
                branchId: "{{ $transaction->branch_id ?? '' }}",
                supplierId: "{{ $transaction->supplier_id ?? '' }}",
                itemId: "{{ $transaction->item_id ?? '' }}",
                debitAccountId: "{{ $transaction->debit_account_id ?? '' }}",
                creditAccountId: "{{ $transaction->credit_account_id ?? '' }}",
                form: document.getElementById('form'),
                goodsForm: document.getElementById('form-item'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                formDelete: document.getElementById('form-delete'),
                formConfirm: document.getElementById('form-confirm'),
                supplierModal: new bootstrap.Modal(document.getElementById('modal-supplier')),
                supplierForm: document.getElementById('form-supplier'),
                async init() {
                    this.inputMask('unit_price');
                    await this.getBranches();
                    await this.getSuppliers();
                    await this.getItemCollections();
                    await this.getStockAccounts();
                    await this.getCashAndLeverageAccounts();
                    await this.getItemCategories();
                    await this.getUnitTypes();
                    await this.getAssetAccounts();
                    await this.selectedSupplier();
                    await this.selectedBranch();
                    await this.selectedDebitAccount();
                    await this.selectedCreditAccount();
                    await this.selectedItemCollection();
                },
                async getBranches() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    if (!this.branchId) return;
                    const selectedBranch = $('#selected-branch');
                    const response = await axios.get(`/select2/selected-branch/${this.branchId}`);
                    const option = new Option(response.data.name, response.data.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response.data}
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
                            cache: false
                        }
                    });
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
                previewTaxInvoiceFile() {
                    let files = this.$refs.taxInvoiceFile.files;
                    if (!files.length) return;

                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;

                        let reader = new FileReader();
                        reader.onload = e => {
                            this.taxInvoiceImgSrc = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                },
                async selectedDebitAccount() {
                    if (!this.debitAccountId) return;
                    const selectedDebitAccount = $('#selected-debit-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.debitAccountId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedDebitAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async saveSupplier() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/suppliers', new FormData(this.supplierForm))
                            .then(async () => {
                                await showAlert('success', 'data berhasil disimpan');
                                await this.supplierForm.reset();
                            })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/transactions', new FormData(this.form))
                        } else {
                            await axios.post(`/transactions/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        window.location.href = '/transactions';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedCreditAccount() {
                    if (this.creditAccountId === '') return;
                    const selectedCreditAccount = $('#selected-credit-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.creditAccountId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedCreditAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
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
                openTaxInvoiceImage() {
                    const lightbox = new FsLightbox();
                    const storage = "{{ Storage::url('')  }}"
                    if (this.editVal) {
                        lightbox.props.sources = [storage + this.taxInvoiceImgSrc[0]];
                    }
                    lightbox.props.sources = [this.taxInvoiceImgSrc];
                    lightbox.open();
                },
                openImageList(imagePath) {
                    console.log(imagePath);
                    const lightbox = new FsLightbox();
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('')  }}" + placeholders;
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "{{  Storage::url('') }}" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                async selectedMainBranches() {
                    if (!this.editVal?.branch_id) return;
                    const selectedMainBranch = $('#selected-main-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${this.editVal.branch.parent_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedMainBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedItemCollection() {

                    if (this.itemId === '') return;
                    const selectedItem = $('#selected-item');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-item/${this.itemId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedItem.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
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
                async getItemCategories() {
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
                            cache: false
                        }
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
                            cache: false
                        }
                    });
                },
                async selectedSupplier() {
                    if (this.transactionId === '') return;
                    const selectedSupplier = $('#selected-supplier');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-supplier/${this.supplierId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedSupplier.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });

                    const resp = await axios.get(`/select2/selected-supplier/${this.supplierId}`);
                    this.PKP = resp.data.tax_type === 'PKP';
                },
                async getStockAccounts() {
                    $(".stock-accounts-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Akun",
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
                async getCashAndLeverageAccounts() {
                    $(".kas-and-leverage-accounts-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Akun",
                        ajax: {
                            url: '/select2/kas-and-leverages-accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
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
                async getSuppliers() {
                    const self = this;
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
                    }).on('select2:select', async function (e) {
                        if (e.params?.data?.id) {
                            const resp = await axios.get(`/select2/selected-supplier/${e.params.data.id}`);
                            self.PKP = resp.data.tax_type === 'PKP';
                        }
                    });
                },
                inputMask(id) {
                    Inputmask("decimal", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: false,
                        allowMinus: false
                    }).mask(`#${id}`);
                }
            }
        }
    </script>
@endpush
