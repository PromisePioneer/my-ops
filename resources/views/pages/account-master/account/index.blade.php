@extends('layouts.template')
@section('page-title', 'Data Account')
@section('content')
    <div x-data="accountData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.account-master.account.modal-account.create')
            @include('pages.account-master.account.modal-account.edit')
            @include('pages.account-master.account.modal-account.import')
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
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary btn-sm me-3" data-bs-toggle="modal"
                                data-bs-target="#modal-import">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-file-earmark-excel-fill"></i>
                            </span>
                            Import
                        </button>
                        <button type="button" class="btn btn-light-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-create">
                            <i class="ki-duotone ki-message-add fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i> Tambah
                        </button>
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="separator my-10"></div>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">Akun</th>
                                <th class="min-w-125px">Saldo Debit</th>
                                <th class="min-w-125px">Saldo Kredit</th>
                                <th class="min-w-125px">Total Saldo</th>
                                <th class="min-w-125px">Aksi</th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && accounts.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(account, index) in accounts.data" :key="account.account_id">
                                <tbody @click="expand($event)" style="cursor:pointer" class="fw-bold">
                                <tr :id="account.account_id">
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="account.account_id"
                                                   :id="'checkbox-' + account.account_id"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" x-text="`${account.account_code} - ${account.account_name}`"></a>
                                    </td>
                                    <td x-text="`Rp. ${account.account_debit_balance}`"></td>
                                    <td x-text="`Rp. ${account.account_credit_balance}`"></td>
                                    <td x-text="`Rp. ${account.account_balance}`"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(account.account_id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                <template x-for="subAccount in account.sub_accounts">
                                    <tr x-show="selectedRow.includes(Number(account.account_id))"
                                        x-transition:enter.duration.500ms
                                        x-transition:leave.duration.400ms>
                                        <td></td>
                                        <td placement="center"
                                            x-text="`${subAccount.sub_account_code} ${subAccount.sub_account_name}`"></td>
                                        <td x-text="`Rp. ${subAccount.sub_account_debit_balance}`"></td>
                                        <td x-text="`Rp. ${subAccount.sub_account_credit_balance}`"></td>
                                        <td x-text="`Rp. ${subAccount.sub_account_balance}`"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </template>
                        </table>
                    </div>

                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in accounts.links">
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
    <script>
        function accountData() {
            return {
                accounts: [],
                buttonLoading: false,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                akunId: '',
                branchId: '',
                selectedRow: [],
                expandRows: false,
                formCreate: document.getElementById('form-create'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formEdit: document.getElementById('form-edit'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formImport: document.getElementById('form-import'),
                modalImport: new bootstrap.Modal(document.getElementById('modal-import')),
                deleteForm: document.getElementById('form-delete'),
                async init() {
                    await this.getAccountData();
                    await this.filterByBranch();
                    await this.getBranchData();
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
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/account-master/account/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.accounts = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.accounts = resp.data
                    }
                },
                expand(event) {
                    const expandedData = event.target.parentNode.id;
                    this.selectedRow.push(Number(expandedData));
                    this.selectedRow = this.selectedRow.filter((val) => val === Number(expandedData))
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/account-master/account', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan');
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
                    console.log(id)
                    const resp = await axios.get(`/account-master/account/edit/${id}`);
                    this.editVal = resp.data;
                    await this.selectedBranch();
                    await this.getBranchData()
                },
                async update() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/account-master/account/update/${this.editVal.id}`, new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil diubah')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/account-master/account/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/account-master/account/import/', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAccountData() {
                    this.isLoading = true;
                    try {
                        const accounts = await axios.get('/account-master/account/data');
                        this.accounts = accounts.data
                        this.startIndex = this.accounts.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/account-master/account/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/account-master/account/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/account-master/account/filter/branch/${selectedBranch.id}`);
                        self.accounts = response.data;
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selectedBranch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/account-master/account/branch/selected/${this.editVal.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },

            }
        }
    </script>
@endpush
