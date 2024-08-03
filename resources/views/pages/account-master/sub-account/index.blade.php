@extends('layouts.template')
@section('page-title', 'Sub Akun')
@section('content')
    <div x-data="subAccountData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.account-master.sub-account.modal.create')
            @include('pages.account-master.sub-account.modal.edit')
            @include('pages.account-master.sub-account.modal.import')
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
                        <button type="button" class="btn btn-light-primary btn-sm me-3" data-bs-toggle="modal"
                                data-bs-target="#modal-import">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                            Import
                        </button>
                        <button type="button" @click="add()" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal-create">
                            Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">No</th>
                            <th class="min-w-125px">Kode</th>
                            <th class="min-w-125px">Nama</th>
                            <th class="min-w-125px">Debit</th>
                            <th class="min-w-125px">Credit</th>
                            <th class="min-w-125px">Total</th>
                        </thead>
                        <tbody class="text-gray-600 fw-bold">
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
                        <template x-if="!isLoading && subAccounts.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="(subAccount,index) in subAccounts?.data" :key="subAccount.id">
                            <tr>
                                <td x-text="startIndex + index++"></td>
                                <td x-text="subAccount.code"></td>
                                <td x-text="subAccount.name"></td>
                                <td x-text="`Rp. ${subAccount.debit_balance}`"></td>
                                <td x-text="`Rp. ${subAccount.credit_balance}`"></td>
                                <td x-text="`Rp. ${subAccount.balance}`"></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit" @click="edit(subAccount.id)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" @click="destroy(subAccount.id)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="page-link" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="page-link" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function subAccountData() {
            return {
                subAccounts: null,
                buttonLoading: false,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                subAccountId: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                formImport: document.getElementById('form-import'),
                async init() {
                    const subAccounts = await axios.get('/account-master/sub-account/data');
                    this.subAccounts = subAccounts.data
                    this.startIndex = this.subAccounts.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/account-master/sub-account/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.subAccounts = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async add() {
                    await this.accountData();
                },
                async accountData() {
                    $(".account-select2").select2({
                        ajax: {
                            url: '/account-master/sub-account/account/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async nextPage() {
                    if (this.subAccounts.next_page_url) {
                        const resp = await axios.get(`${this.subAccounts.next_page_url}`);
                        this.startIndex = resp.data.from
                        this.subAccounts = resp.data
                    }
                },
                async previousPage() {
                    if (this.subAccounts.prev_page_url) {
                        const resp = await axios.get(`${this.subAccounts.prev_page_url}`);
                        this.startIndex = resp.data.from
                        this.subAccounts = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/account-master/sub-account', new FormData(this.formCreate));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    this.subAccountId = id;
                    const resp = await axios.get(`/account-master/sub-account/edit/${id}`);
                    this.editVal = resp.data;
                    await this.accountData();
                    await this.selectedAccount();
                },
                async selectedAccount() {
                    const selectedAccount = $('#selectedAccount');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/account-master/sub-account/account/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async update(subAccountId) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/account-master/sub-account/update/${subAccountId}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil diubah');
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false
                    }
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/account-master/sub-account/import/', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil diimport');
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/account-master/sub-account/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
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
