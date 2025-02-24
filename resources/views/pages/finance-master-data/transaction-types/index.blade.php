@extends('layouts.template')
@section('page-title', 'Jenis Transaksi')
@section('breadcrumbs', 'Master Keuangan - Jenis Transaksi')
@section('content')
    <div x-data="transactionType()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.finance-master-data.transaction-types.form')
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        @can('Tambah Data Satuan')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-transaction-type">
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
                <div class="col-12 ">
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
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()" :disabled="Number(deletePermission) !== 1">
                                    </div>
                                </th>
                                <th class="min-w-125px">Nama Transaksi</th>
                                <th class="min-w-125px">Akun Debit</th>
                                <th class="min-w-125px">Akun Kredit</th>
                                <th class="min-w-125px">Actions</th>
                            </tr>
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
                            <template x-if="!isLoading && transactionTypes.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(transactionType, index) in transactionTypes?.data"
                                      :key="transactionType.id">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="transactionType.id"
                                                   :id="'checkbox-' + transactionType.id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td x-text="transactionType.name"></td>
                                    <td x-text="`${transactionType.debit_account.code} ${transactionType.debit_account.name}`"></td>
                                    <td x-text="`${transactionType.credit_account.code} ${transactionType.credit_account.name}`"></td>
                                    <template x-if="Number(editPermission) === 1">
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-transaction-type"
                                                    @click="edit(transactionType.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in transactionTypes.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function transactionType() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data Satuan') }}",
                editPermission: "{{ request()->user()->can('Edit Data Satuan') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Satuan') }}",
                transactionTypes: [],
                isLoading: true,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                form: document.getElementById('form-transaction-type'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-transaction-type')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    const resp = await axios.get('/finances-master-data/transaction-types/data');
                    this.transactionTypes = resp.data
                    this.isLoading = false;
                    await this.getAccounts();
                },
                add() {
                    this.editVal = '';
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/finances-master-data/transaction-types/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.transactionTypes = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.transactionTypes = resp.data
                    }
                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission) === 1) {
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
                    }
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
                async save(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/finances-master-data/transaction-types/', new FormData(this.form))
                        } else {
                            await axios.post(`/finances-master-data/transaction-types/update/${id}`, new FormData(this.form))
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
                    const resp = await axios.get(`/finances-master-data/transaction-types/${id}`);
                    this.editVal = resp.data;
                    await this.selectedDebitAccount();
                    await this.selectedCreditAccount();
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/finances-master-data/transaction-types/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
            }
        }
    </script>
@endpush
