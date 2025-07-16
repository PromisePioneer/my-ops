@extends('layouts.template')
@section('page-title', 'Data Pengeluaran')
@section('content')
    <div x-data="expenditureData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.transaction.expenditure.modal.create')
            @include('pages.transaction.expenditure.modal.edit')
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
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light-info me-3 btn-sm " data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-funnel-fill"></i>
                            </span>
                            Filter
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" style="">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bolder">Filter</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-bold">Cabang:</label>
                                    <select name="" id=""
                                            class="form-select form-select-solid filter-branch-select2">
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-primary btn-sm" @click="add()" data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                    <form id="deleteForm" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-danger btn-sm mt-5" x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="bi bi-trash"></i>
                            Hapus
                        </button>
                    </form>

                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase ">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Deskripsi</th>
                                <th class="min-w-125px">Akun Debit</th>
                                <th class="min-w-125px">Akun Kredit</th>
                                <th class="min-w-125px">Nominal</th>
                                <th class="min-w-125px">Foto</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && expenditures.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(expenditure, index) in expenditures?.data" :key="expenditure.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="expenditure.description"></td>
                                    <td x-text="expenditure.debit_account"></td>
                                    <td x-text="expenditure.credit_account"></td>
                                    <td x-text="`Rp.${expenditure.amount}`"></td>
                                    <td>
                                        <img :src="getImageURL(expenditure.file)"
                                             @click="$dispatch('lightbox', `${getImageURL(expenditure.file)}`)"
                                             width="100"
                                             height="100"/>
                                    </td>
                                    <template x-if="expenditure.status_confirmation === 0">
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(expenditure.id)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm" @click="confirm(expenditure.id)">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" @click="destroy(expenditure.id)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="expenditure.status_confirmation === 1">
                                        <td>
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function expenditureData() {
            return {
                expenditures: [],
                isLoading: false,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                async init() {
                    this.isLoading = true;
                    await this.getExpenditureData();
                    await this.filterByBranch();
                    this.isLoading = false;
                },
                async add() {
                    await this.getDebitAccount();
                    await this.getCreditAccount();
                },
                async searchData() {
                    this.isLoading = true;
                    this.expenditure = await axios.get('/master/branch/search', {
                        params: {
                            search: this.search
                        },
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                    this.isLoading = false;
                },
                async nextPage() {
                    if (this.expenditures.next_page_url) {
                        const resp = await axios.get(`${this.expenditures.next_page_url}`);
                        this.startIndex = this.expenditure.from
                        this.expenditure = resp.data
                    }
                },
                async previousPage() {
                    if (this.expenditures.prev_page_url) {
                        const resp = await axios.get(`${this.expenditures.prev_page_url}`);
                        this.startIndex = this.expenditure.from
                        this.expenditure = resp.data
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/expenditure-transactions/expenditure/', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/expenditure-transactions/expenditure/${id}`);
                    this.editVal = resp.data
                    await this.getCreditAccount();
                    await this.getDebitAccount();
                    await this.selectedDebitAccount();
                    await this.selectedCreditAccount();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/expenditure-transactions/expenditure/${id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalEdit.hide();
                        this.formEdit.reset();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async confirm(id) {
                    showConfirmModal("Anda yakin?", "Data tidak bisa dihapus atau diubah jika di konfirmasi.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/expenditure-transactions/expenditure/confirm/${id}`);
                            await showAlert('success', 'Data sukses dikonfirmasi');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.delete(`/expenditure-transactions/expenditure/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getExpenditureData() {
                    const expenditures = await axios.get('/expenditure-transactions/expenditure/data');
                    this.expenditures = expenditures.data
                    this.startIndex = this.expenditures.from;
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        ajax: {
                            url: '/expenditure-transactions/expenditure/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/expenditure-transactions/expenditure/filter/branch/data/${selectedBranch.id}`);
                        self.expenditures = response.data;
                    });
                },
                async getDebitAccount() {
                    $(".debit-account-select2").select2({
                        ajax: {
                            url: '/expenditure-transactions/expenditure/get-debit-account',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getCreditAccount() {
                    $(".credit-account-select2").select2({
                        ajax: {
                            url: '/expenditure-transactions/expenditure/get-credit-account',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedDebitAccount() {
                    const selectedDebitAccount = $('#selectedDebitAccount');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/expenditure-transactions/expenditure/selected-debit-account/${this.editVal.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedDebitAccount.append(option).trigger('change');
                        selectedDebitAccount.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                async selectedCreditAccount() {
                    const selectedCreditAccount = $('#selectedCreditAccount');
                    $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/expenditure-transactions/expenditure/selected-credit-account/${this.editVal.id}`,
                    }).then(function (response) {
                        const option = new Option(response.name, response.id, true, true);
                        selectedCreditAccount.append(option).trigger('change');

                        selectedCreditAccount.trigger({
                            type: 'select2:select',
                            params: {results: response}
                        });
                    });
                },
                getImageURL(imagePath) {
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
