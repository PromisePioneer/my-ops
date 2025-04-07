@extends('layouts.template')
@section('page-title', 'Transaksi')
@section('breadcrumbs', 'Transaksi')
@section('content')
    @push('styles')
        <style>
            .modal-body {
                max-height: calc(100vh - 200px);
                overflow-y: auto;
            }
        </style>
    @endpush
    <div x-data="transactionData()">
        @include('pages.master.operational.items.form')
        @include('pages.transactions.form')
        @include('pages.transactions.confirm-modal')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 ">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14"
                               placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        @can('Tambah Data Transaksi')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-transactions"
                                    @click="add()">
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
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="d-flex align-items-center">
                        <div>
                            <form id="form-delete" @submit.prevent="destroy()" class="me-2">
                                <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                                <button type="submit" class="btn btn-light-danger btn-sm mt-5"
                                        x-show="selectedCheckBox.length > 0 && hasLockedTransactions() && !hasUnlockedTransactions()"
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
                        <div>
                            <template x-if="Number(finalApprovePermission) === 1">
                                <button type="button" class="btn btn-light-info btn-sm mt-5"
                                        x-show="selectedCheckBox.length > 0 && hasUnlockedTransactions() && !hasLockedTransactions()"
                                        x-transition x-cloak data-bs-target="#modal-confirm"
                                        data-bs-toggle="modal">
                                    <i class="ki-duotone ki-double-check">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Setujui Transaksi
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-bordered fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                            </th>
                            <th class="min-w-125px text-center">Informasi Transaksi</th>
                            <th class="min-w-125px text-center">Akun</th>
                            <th class="min-w-125px text-center">Detail</th>
                            <th class="min-w-125px text-center">Bukti Transaksi</th>
                            <th class="min-w-125px text-center">Status Konfirmasi</th>
                            <template
                                x-if="Number(editPermission) === 1 || Number(confirmPermission) === 1">
                                <th class="min-w-250px text-center">Actions</th>
                            </template>
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
                        <template x-if="!isLoading && transactions.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(transaction, index) in transactions?.data" :key="transaction.id">
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid"
                                         @click="selectCheckBox($event)">
                                        <template x-if="transaction.status !== 'Diterima'">
                                            <input class="form-check-input" type="checkbox"
                                                   :value="transaction.id"
                                                   :id="'checkbox-' + transaction.id"
                                                   :disabled="Number(destroyPermission) !== 1"/>
                                        </template>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-column">
                                        <span x-text="`[${transaction.branch_name}]`"></span>
                                        <span x-text="`Transaksi ${transaction.type}`"></span>
                                        <span x-text="`Tgl ${transaction.date}`"></span>
                                        <span x-text="`No ${transaction.transaction_number}`"></span>
                                        <hr>
                                        <span x-text="`Barang : ${transaction.item_name}`"></span>
                                        <span x-text="`Total Harga : ${transaction.total_price}`"></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span x-text="transaction.transaction_type"></span>
                                        <a href="#"
                                           class="badge bg-primary text-white mb-2"
                                           x-text="`${transaction.debit}`"></a>
                                        <a href="#" class="badge bg-danger text-white mb-2"
                                           x-text="`${transaction.credit}`"></a>
                                    </div>
                                </td>
                                <td class="text-center" x-text="transaction.detail"></td>
                                <td class="text-center">
                                    <a href="#">
                                        <div class="symbol-label">
                                            <a href="#" @click="openImageList(transaction.attachment)">
                                                <img :src="getImageURL(transaction.attachment ?? null)"
                                                     alt="Image" class="w-100">
                                            </a>
                                        </div>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <p class="fs-7">Status Transaksi : <span
                                                :class="transaction.locked_status === 1 ? 'text-info' : 'text-danger'"
                                                x-text="transaction.locked_status === 1 ? `Terkunci (${transaction.created_by})` : 'Belum Dikunci'"></span>
                                        </p>
                                        <p class="fs-7">Dibuat Oleh : <span
                                                x-text="`${transaction.created_by}`"></span>
                                        </p>

                                        <template x-if="transaction.locked_status === 1">
                                            <p class="fs-7">Status Konfirmasi : <span
                                                    :class="transaction.status === 'Diproses'
                                                        ? 'text-warning'
                                                        : transaction.status === 'Diterima' ? 'text-info'
                                                        : 'text-danger'"
                                                    x-text="transaction.status"></span>

                                            </p>
                                        </template>
                                    </div>
                                </td>
                                <template x-if="Number(editPermission) === 1 && transaction.locked_status === 0">
                                    <td>
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center">
                                            <template x-if="transaction.locked_status === 0">
                                                <button class="btn btn-light-primary btn-sm mb-4"
                                                        @click="edit(transaction.id)"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal-transactions">
                                                    <i class="bi bi-pencil"></i> Ubah Data
                                                </button>
                                            </template>
                                            <template x-if="transaction.locked_status === 0">
                                                <button class="btn btn-light-info btn-sm mb-4"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        @click="lockTransaction(transaction.id)"
                                                        title="Kunci Transaksi">
                                                    <i class="bi bi-lock"></i>
                                                    Kunci Transaksi
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-danger">Note : Akun berwarna biru debet,merah kredit</span>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in transactions.links">
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
    <div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>


        Inputmask("decimal", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            autoGroup: true,
            rightAlign: false,
            allowMinus: false
        }).mask("#unit_price");

        $('.date').flatpickr();

        document.addEventListener('focusin', (e) => {
            if (e.target.closest(".flatpickr-calendar") !== null) {
                e.stopImmediatePropagation();
            }
        });


        const transactionModal = new bootstrap.Modal(document.getElementById('modal-transactions'));
        const itemModal = document.getElementById('modal-item');

        function transactionData() {
            return {
                editPermission: "{{ request()->user()->can('Ubah Data Transaksi') }}",
                destroyPermission: "{{ request()->user()->can('Hapus Data Transaksi') }}",
                confirmPermission: "{{ request()->user()->can('Konfirmasi Data Transaksi') }}",
                finalApprovePermission: "{{ request()->user()->can('Final Approve Data Transaksi') }}",
                transactions: [],
                isLoading: true,
                transactionType: null,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: null,
                userList: [],
                imgsrc: [],
                attachments: [],
                selectedConfirmationStatus: null,
                form: document.getElementById('form-transactions'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-transactions')),
                goodsForm: document.getElementById('form-item'),
                itemModal: new bootstrap.Modal(document.getElementById('modal-item')),
                formDelete: document.getElementById('form-delete'),
                formConfirm: document.getElementById('form-confirm'),
                modalConfirm: new bootstrap.Modal(document.getElementById('modal-confirm')),
                async init() {
                    await this.getTransactions();
                    await this.getMainBranches();
                    await this.getUnitTypes();
                    await this.getKasAndLeverageAccounts();
                    await this.getStockAccounts();
                    await this.getItemCollections();
                    await this.itemCategories();
                },
                add() {
                    this.form.reset();
                    this.editVal = null;
                    this.selectedBranch();
                    this.modalForm.show();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`, {
                            params: {
                                search: this.search,
                                branch_id: $('#branch-id-filter').val(),
                                start_date: $('#start-date-filter').val(),
                                end_date: $('#end-date-filter').val(),
                            }
                        });
                        this.transactions = resp.data
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
                previewFile() {
                    let files = this.$refs.myFile.files;
                    if (!files.length) return;

                    Array.from(files).forEach(file => {
                        if (!file.type.startsWith('image/')) return;

                        let reader = new FileReader();
                        reader.onload = e => {
                            this.imgsrc = [];
                            this.imgsrc.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });
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
                async filter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/transactions/filter', {
                            params: {
                                search: this.search,
                                branch_id: $('#branch-id-filter').val(),
                                start_date: $('#start-date-filter').val(),
                                end_date: $('#end-date-filter').val(),
                            }
                        });
                        this.transactions = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/transactions/search', {
                            params: {
                                search: this.search,
                                branch_id: $('#branch-id-filter').val(),
                                start_date: $('#start-date-filter').val(),
                                end_date: $('#end-date-filter').val(),
                            },
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.transactions = resp.data;
                    } catch (error) {
                        console.log(error);
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
                        this.modalForm.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                openImage() {
                    const lightbox = new FsLightbox();
                    lightbox.props.sources = [this.imgsrc[0]];
                    lightbox.open();
                },
                openImageList(imagePath) {
                    const lightbox = new FsLightbox();
                    console.log(lightbox);
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('')  }}" + placeholders;
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "<?php echo e(Storage::url('')); ?>" + imagePath;
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
                async edit(id) {
                    const resp = await axios.get(`/transactions/${id}`);
                    this.editVal = resp.data;
                    this.imgsrc.push(this.editVal.attachment);
                    this.transactionType = this.editVal.type;
                    await this.selectedItem();
                    await this.selectedBranch();
                    await this.selectedDebitAccount();
                    await this.selectedCreditAccount();
                },
                async destroy() {
                    console.log(this.selectedCheckBox);
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/transactions/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getMainBranches() {
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
                    });
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
                async getKasAndLeverageAccounts() {
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
                async lockTransaction(id) {
                    showConfirmModal("Anda yakin?", "Data yang dikunci tidak akan bisa diubah maupun dihapus, jika ingin menghapus atau mengubah silahkan hubungi stakeholder terkait.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/transactions/lock-transaction/${id}`);
                            await showAlert('success', 'Data sukses Dikunci');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async selectedBranch() {
                    if (!this.editVal?.branch_id) return;
                    const selectedBranch = $('#selected-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${this.editVal.branch_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedDebitAccount() {
                    const selectedDebitAccount = $('#selected-debit-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.editVal.debit_account_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedDebitAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedCreditAccount() {
                    const selectedCreditAccount = $('#selected-credit-account');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-account/${this.editVal.credit_account_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedCreditAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
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
                async getTransactions() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/transactions/data');
                        this.transactions = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
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
                async selectedItem() {
                    if (this.editVal.type !== 'Barang') return;
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
                    });
                },
                async confirm() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/transactions/final-status`, new FormData(this.formConfirm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formConfirm.reset();
                        this.modalConfirm.hide();
                        await this.getTransactions();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                hasLockedTransactions() {
                    if (this.selectedCheckBox.length === 0) return false;
                    const selectedIds = this.selectedCheckBox;
                    return this.transactions.data.some(transaction =>
                        selectedIds.includes(transaction.id.toString()) && transaction.locked_status === 0
                    );
                },
                hasUnlockedTransactions() {
                    if (this.selectedCheckBox.length === 0) return false;
                    const selectedIds = this.selectedCheckBox;
                    return this.transactions.data.some(transaction =>
                        selectedIds.includes(transaction.id.toString()) && transaction.locked_status === 1
                    );
                },
            }
        }
    </script>
@endpush
