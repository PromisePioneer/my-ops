@extends('layouts.template')
@section('page-title', 'Tambah Aset')
@section('breadcrumbs', 'Master Keuangan - Aset - Tambah Aset')
@section('content')
    @push('styles')
        <style>
            .select2-dropdown {
                z-index: 1 !important;
            }
        </style>
    @endpush
    <div x-data="generateAsset()">
        @include('pages.master.operational.items.form')
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6" href="{{ url('master/accounting/assets/') }}">
                    <x-icons.back/>
                    Kembali
                </a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save(assetId)">
                    <div class="card-body">
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label for="company_id" class="required form-label">Perusahaan</label>
                                <select name="company_id" class="form-select form-select-solid companies-select2"
                                        id="selected-company">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label for="name" class="required form-label">Cabang</label>
                                <select name="branch_id" class="form-select form-select-solid branches-select2"
                                        id="selected-branch">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6" @change="changeItemCondition()">
                                <label for="name" class="required form-label">Kondisi Barang</label>
                                <select class="form-select form-select-solid" x-model="itemCondition">
                                    <option value="Terpakai">Terpakai</option>
                                    <option value="Digudang">Digudang</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label for="name" class="required form-label">Nama Barang</label>
                                <select name="item_id" id="selected-item"
                                        class="form-select form-select-solid asset-items-select2">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="required form-label">Kode Aset</label>
                                <input type="text" class="form-control form-control-solid" name="code" id="code"
                                       placeholder="Kode Aset" value="{{ $asset->code ?? '' }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="required form-label">Tanggal Perolehan</label>
                                <br>
                                <input type="date" id="date" name="date"
                                       class="form-control form-control-solid date"
                                       placeholder=" Tanggal Perolehan" value="{{ $asset->date ?? '' }}"/>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="required form-label">Harga / Unit</label>
                                <input type="text" id="price" name="price"
                                       class="form-control form-control-solid"
                                       placeholder="Harga per unit" :value="parseFloat(price) ?? ''"/>
                            </div>
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
    </div>
    @include('components.select2.script')
@endsection
@push('script')
    <script>
        function generateAsset() {
            $('.date').flatpickr();
            return {
                isAset: null,
                itemMustHaveCode: false,
                editVal: '',
                hasSNOnItem: false,
                assetId: "{{ $asset->id ?? '' }}",
                branchId: "{{ $asset->branch_id ?? '' }}",
                itemId: "{{ $asset->item_id ?? '' }}",
                price: "{{ $asset->price ?? '' }}",
                isLandAsset: false,
                nonBuildingGroup: null,
                itemCondition: false,
                isVehicleAsset: false,
                tangibleAsset: null,
                buildingType: null,
                buttonLoading: false,
                attachmentImgSrc: '',
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                itemForm: document.getElementById('form-item'),
                form: document.getElementById('form'),
                async init() {
                    this.inputMask('price');
                    await select2('.companies-select2', 'Pilih Perusahaan', '/select2/companies-data');
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data');
                    await select2('.asset-items-select2', 'Pilih Barang', '/select2/asset-items-data', true, false, 'modal-item');
                    await select2('.unit-types-select2', 'Pilih Satuan', '/select2/unit-types-data', true, true);
                    await select2('.asset-accounts-select2', 'Pilih Akun', '/select2/asset-accounts-data');
                    await select2('.item-category-select2', 'Pilih Kategori', '/select2/item-categories-data');
                    await select2('.stock-accounts-select2', 'Pilih Akun ', '/select2/stock-accounts-data');
                    await this.selectedItemCollection();
                    await this.getStockAccounts();
                    await this.selectedBranch();
                    await this.selectedItemCollection();
                    this.inputMask('unit_price')
                    this.inputMask('price_per_unit')
                },

                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/accounting/assets', new FormData(this.form))
                        } else {
                            await axios.post(`/master/accounting/assets/update/${id}`, new FormData(this.form))
                        }

                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        window.location.href = '/master/accounting/assets';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async selectedBranch() {
                    if (this.branchId === '') return;
                    const selectedBranch = $('#selected-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${this.branchId}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
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
                async getItemCategories() {
                    $(".item-category-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kategori Barang",
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
                async getAssetItemCollections() {
                    $(".asset-items-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Barang",
                        escapeMarkup: markup => (markup),
                        language: {
                            noResults: () => {
                                return `Data Tidak Ditemukan.. <a href=/'#' data-bs-toggle="modal" data-bs-target="#modal-item">Tambahkan terlebih dahulu</a>`;
                            }
                        },
                        ajax: {
                            url: '/select2/asset-items-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
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
                            cache: true
                        }
                    });
                },
                async saveItem() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/items', new FormData(this.itemForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.itemForm.reset();
                        this.itemModal.hide();
                        this.modalForm.show();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getAssetAccounts() {
                    $(".asset-accounts-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Akun',
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
                inputMask(id) {
                    Inputmask("decimal", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: false,
                        allowMinus: false
                    }).mask(`#${id}`);
                },
                async changeItemCondition() {
                    if (this.itemCondition === 'Digudang') {
                        showConfirmModal("Anda yakin?", "Lanjut ke halaman saldo awal persediaan ?", "Ya, Konfirmasi!", async () => {
                            try {
                                window.open('/master/accounting/initial-inventory-balances/create');
                            } catch (error) {
                                console.error(error);
                                await showAlert('error', 'Terjadi kesalahan');
                            }
                        });

                        await this.getBranches();
                    }
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
