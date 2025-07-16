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
        @include('pages.master.common.contacts.form')
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
    @include('components.select2.script')
    @include('components.input-mask')
    @include('components.image.handle-image')
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
                supplierId: "{{ $transaction->contact_id ?? '' }}",
                itemId: "{{ $transaction->item_id ?? '' }}",
                debitAccountId: "{{ $transaction->debit_account_id ?? '' }}",
                creditAccountId: "{{ $transaction->credit_account_id ?? '' }}",
                companyId: "{{ $transaction->company_id ?? '' }}",
                form: document.getElementById('form'),
                goodsForm: document.getElementById('form-item'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                formDelete: document.getElementById('form-delete'),
                formConfirm: document.getElementById('form-confirm'),
                supplierModal: new bootstrap.Modal(document.getElementById('contact-modal')),
                supplierForm: document.getElementById('contact-form'),
                contactType: null,
                qtyInMeter: false,
                async init() {
                    inputMask('unit_price', 'decimal');
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data');
                    await select2('.companies-select2', 'Pilih Perusahaan', '/select2/companies-data');
                    await select2('.suppliers-select2', 'Pilih Supplier', '/select2/suppliers-data', true, false, 'contact-modal');
                    await select2('.items-select2', 'Pilih Barang', '/select2/goods-data', true, false, 'modal-item');
                    await select2('.stock-accounts-select2', 'Pilih Akun Persediaan', '/select2/stock-accounts-data');
                    await select2('.kas-and-leverage-accounts-select2', 'Pilih Akun Kas / Utang', '/select2/kas-and-leverages-accounts-data');
                    await select2('.item-category-select2', 'Pilih Kategori', '/select2/item-categories-data');
                    await select2('.unit-types-select2', 'Pilih Satuan', '/select2/unit-types-data', true, true);
                    await select2('.asset-accounts-select2', 'Pilih Akun Aset', '/select2/asset-accounts-data');
                    await this.selectedSelect2Value();
                    this.supplierOnSelect();
                    this.itemOnSelect();
                },
                itemOnSelect() {
                    $('.items-select2').on('select2:select', (e) => {
                        this.qtyInMeter = e?.params?.data?.unit_type_name === 'Meter';
                    });
                },
                supplierOnSelect() {
                    $('.suppliers-select2').on('select2:select', (e) => {
                        this.PKP = e?.params?.data?.tax_type === 'PKP';
                    });
                },
                async selectedSelect2Value() {
                    if (this.transactionId === '') return;
                    await selectedValue('selected-branch', `/select2/selected-branch/${this.branchId}`);
                    await selectedValue('selected-item', `/select2/selected-item/${this.itemId}`);
                    await selectedValue('selected-debit-account', `/select2/selected-account/${this.debitAccountId}`);
                    await selectedValue('selected-credit-account', `/select2/selected-account/${this.creditAccountId}`);
                    await selectedValue('selected-supplier', `/select2/selected-contact/${this.supplierId}`);
                    await selectedValue('selected-company', `/select2/selected-company/${this.companyId}`);
                    const getSuppliers = await axios.get(`/select2/selected-supplier/${this.supplierId}`);
                    this.PKP = getSuppliers.data.tax_type === 'PKP';

                    const getItem = await axios.get(`/select2/selected-item/${this.itemId}`);

                    this.qtyInMeter = getItem.data.unit_type_name === 'Meter';
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
                async saveContact() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/common/contact', new FormData(this.supplierForm))
                            .then(async () => {
                                await showAlert('success', 'data berhasil disimpan');
                                await this.supplierForm.reset();
                                await this.supplierModal.hide();
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
            }
        }
    </script>
@endpush
