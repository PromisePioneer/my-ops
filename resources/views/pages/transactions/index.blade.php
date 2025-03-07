@extends('layouts.template')
@section('page-title', 'Transaksi')
@section('breadcrumbs', 'Transaksi')
@section('content')
    <div x-data="transactionData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-250px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column text-gray-600">
                            <div class="d-flex align-items-center py-2">
                                <select class="form-select form-select-solid main-branches-select2"
                                        name="branch_id" id="branch-id-filter">
                                </select>
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <input type="date" class="form-control form-control-solid date"
                                       name="start_date" id="start_date" placeholder="Tgl awal">
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <input type="date" class="form-control form-control-solid date" name="end_date"
                                       id="end_date" placeholder="Tgl akhir">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-4 text-end">
                        <button type="button" @click="filter()" class="btn btn-light btn-active-primary btn-sm">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush">
                    @include('pages.transactions.form')
                    <div class="card-header border-0 pt-6">
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
                                            data-bs-target="#modal-transactions">
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
                            <div class="col-12 mb-4">
                                <form id="deleteForm" @submit.prevent="destroy()">
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
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">

                                            </div>
                                        </th>
                                        <th class="min-w-125px text-center">Tanggal</th>
                                        <th class="min-w-125px text-center">Transaksi</th>
                                        <th class="min-w-250px text-center">Akun</th>
                                        <th class="min-w-250px text-center">Detail</th>
                                        <th class="min-w-125px text-center">Jumlah</th>
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
                                                    <input class="form-check-input" type="checkbox"
                                                           :value="transaction.id"
                                                           :id="'checkbox-' + transaction.id"
                                                           :disabled="transaction.status === 1 || Number(destroyPermission) !== 1"/>
                                                </div>
                                            </td>
                                            <td class="text-center"
                                                x-text="transaction.date"></td>
                                            <td class="text-center" x-text="transaction.transaction_number"></td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span x-text="transaction.transaction_type"></span>
                                                    <a :href="`/account-transactions/${transaction.debit_account_id}`"
                                                       class="btn btn-primary btn-sm py-2 mb-2"
                                                       x-text="`${transaction.debit}`"></a>
                                                    <a class="btn btn-danger btn-sm py-2 mb-2"
                                                       x-text="`${transaction.credit}`"></a>
                                                </div>
                                            </td>
                                            <td x-text="transaction.detail"></td>
                                            <td x-text="transaction.total_price"></td>
                                            <template x-if="transaction.status === 0 && Number(editPermission) === 1">
                                                <td class="d-flex flex-column">
                                                    <button class="btn btn-light-primary btn-sm mb-4"
                                                            @click="edit(transaction.id)"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-transactions">
                                                        <i class="bi bi-pencil"></i> Ubah Data
                                                    </button>
                                                    <button class="btn btn-light-info btn-sm mb-4"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            @click="confirm(transaction.id)"
                                                    >
                                                        <i class="bi bi-check-circle-fill"></i>
                                                        Konfirmasi
                                                    </button>
                                                </td>
                                            </template>
                                            <template x-if="transaction.status === 1">
                                                <td class="text-center">
                                                    <span class="badge bg-success">Terkonfirmasi</span>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
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
        </div>
        <div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        $('.date').flatpickr();

        function transactionData() {
            return {
                editPermission: "{{ request()->user()->can('Ubah Data Transaksi') }}",
                destroyPermission: "{{ request()->user()->can('Hapus Data Transaksi') }}",
                confirmPermission: "{{ request()->user()->can('Konfirmasi Data Transaksi') }}",
                transactions: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                userList: [],
                form: document.getElementById('form-transactions'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-transactions')),
                deleteForm: document.getElementById('deleteForm'),
                async init() {
                    await this.getTransactions();
                    await this.getMainBranches();
                    await this.getUnitTypeData();
                    await this.getAccounts();
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
                async edit(id) {
                    const resp = await axios.get(`/transactions/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch();
                    await this.selectedDebitAccount();
                    await this.selectedCreditAccount();
                    await this.selectedUnitType();
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/transactions/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
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
                async getAccounts() {
                    $(".accounts-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Akun",
                        ajax: {
                            url: '/select2/accounts-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async confirm(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/transactions/confirm/${id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async selectedBranch() {
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
                        url: `/select2/selected-account/${this.editVal.debit_account_id}`,
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
                async getUnitTypeData() {
                    $(".unit-types-select2").select2({
                        allowClear: true,
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
            }
        }
    </script>
@endpush
