@php @endphp
@extends('layouts.template')
@section('page-title', 'Saldo Awal')
@section('breadcrumbs', 'Master Keuangan - Saldo Awal')
@section('content')
    @push('styles')
        <style>
            .accounts-select2 optgroup {
                background: #000;
                color: #fff;
                font-style: normal;
                font-weight: normal;
                padding: 0;
            }
        </style>
    @endpush


    <div x-data="InitialBalancesData()">
        @include('pages.finance-master-data.initial-balances.modal.create')
        @include('pages.finance-master-data.initial-balances.modal.edit')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid filter-branch-select2"
                                            name="branch_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
                            @can('Tambah Data Saldo Awal')
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-create">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            @endcan
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1"
                                 data-kt-view-roles-table-toolbar="base">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
															<svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viemanagewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="17.0365" y="15.1223"
                                                                      width="8.15546" height="2" rx="1"
                                                                      transform="rotate(45 17.0365 15.1223)"
                                                                      fill="black"></rect>
																<path
                                                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                                        fill="black"></path>
															</svg>
														</span>
                                <input type="text" class="form-control form-control-solid w-250px ps-15"
                                       x-model="search" @input.debounce="searchData()" placeholder="Cari...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
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
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped"
                                       id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                    class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            </div>
                                        </th>
                                        <th class="min-w-125px">Akun</th>
                                        <th class="min-w-125px">Saldo</th>
                                        <template x-if="branchId !== null">
                                            <th class="min-w-125px">Actions</th>
                                        </template>
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
                                    <template x-if="!isLoading && initialBalances.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="(account, index) in initialBalances?.data" :key="index">
                                        <tbody style="cursor:pointer" class="fw-bold">
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <template x-if="account?.sub_accounts?.length === 0">
                                                        <template x-if="branchId !== null">
                                                            <input class="form-check-input" type="checkbox"
                                                                   :value="account.id"
                                                                   :id="'checkbox-' + account.id"
                                                                   :disabled="account.initial_balance === null || Number(deletePermission) !== 1"/>
                                                        </template>
                                                    </template>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" x-text="account.account"></a>
                                            </td>
                                            <td x-text="account.initial_balance"></td>
                                            <td>
                                                <template x-if="account?.sub_accounts?.length === 0">
                                                    <template x-if="branchId !== null">
                                                        <button class="btn btn-light-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-edit"
                                                                @click="edit(account.account_id)"
                                                                :disabled="account.initial_balance === null">
                                                            <i class="ki-duotone ki-pencil fs-2">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </button>
                                                    </template>
                                                </template>
                                            </td>
                                        </tr>
                                        <template x-for="(subAccount, index) in account.sub_accounts"
                                                  :key="subAccount.id">
                                            <tr>
                                                <td>
                                                    <div
                                                            class="form-check form-check-sm form-check-custom form-check-solid"
                                                            @click="selectCheckBox($event)">
                                                        <input class="form-check-input" type="checkbox"
                                                               :value="subAccount.id"
                                                               :id="'checkbox-' + subAccount.id"
                                                               :disabled="branchId === null
                                                               || Number(deletePermission) !== 1"
                                                        />
                                                    </div>
                                                </td>
                                                <td placement="center"
                                                    x-text="`${subAccount.sub_account_code} ${subAccount.sub_account_name}`"></td>
                                                <td x-text="subAccount.initial_balance"></td>
                                                <td>
                                                    <template x-if="branchId !== null">
                                                        <template x-if="Number(editPermission) === 1">
                                                            <button class="btn btn-light-primary btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modal-edit"
                                                                    @click="edit(subAccount.id)"
                                                                    :disabled="subAccount.initial_balance === null">
                                                                <i class="ki-duotone ki-pencil fs-2">
                                                                    <span class="path1"></span>
                                                                    <span class="path2"></span>
                                                                </i>
                                                            </button>
                                                        </template>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in initialBalances.links">
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
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script defer>
        const disabledMonths = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $('.date').flatpickr({
            monthSelectorType: 'static',
            disable: [
                function (date) {
                    return disabledMonths.includes(date.getMonth());
                }
            ]
        });

        function InitialBalancesData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Saldo Awal') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Saldo Awal') }}",
                initialBalances: [],
                isLoading: false,
                buttonLoading: false,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                branchId: null,
                search: '',
                editVal: '',
                formCreate: document.getElementById('form-create'),
                formEdit: document.getElementById('form-edit'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getAccountData();
                    await this.getInitialBalances();
                    await this.getBranchDataForFilter();
                    await this.getBranchData();
                },
                async getInitialBalances() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/finances-master-data/initial-balances/data');
                        this.initialBalances = resp.data;
                        this.isLoading = false;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/finances-master-data/initial-balances/search', {
                            params: {
                                search: this.search,
                                branch_id: this.branchId,
                            },
                        });

                        this.initialBalances = resp.data
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.initialBalances = resp.data
                    }
                },
                toggleAllCheckBox() {
                    if (Number(this.deletePermission) !== 1) {
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
                async getAccountData() {
                    $(".accounts-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Akun',
                        ajax: {
                            url: '/finances-master-data/initial-balances/account/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchDataForFilter() {
                    $(".filter-branch-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/finances-master-data/initial-balances/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/finances-master-data/initial-balances/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selectedBranch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/finances-master-data/initial-balances/branch/selected/${this.editVal.branch_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {
                            results: response,
                        }
                    });
                },
                async selectedAccount() {
                    const selectedAccount = $('#selectedAccount');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/finances-master-data/initial-balances/account/selected/${this.editVal.account_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedAccount.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {
                            results: response,
                        }
                    });
                },
                async filter() {
                    this.branchId = $(".filter-branch-select2").val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/finances-master-data/initial-balances/filter', {
                            params: {
                                branch_id: this.branchId,
                            }
                        });
                        this.initialBalances = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/finances-master-data/initial-balances/', new FormData(this.formCreate))
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
                    const branch_id = $(".filter-branch-select2").val();
                    const resp = await axios.get(`/finances-master-data/initial-balances/${id}`, {
                        params: {
                            branch_id: branch_id
                        }
                    });
                    this.editVal = resp.data;
                    await this.selectedBranch();
                    await this.selectedAccount();
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/finances-master-data/initial-balances/${id}`, new FormData(this.formEdit))
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
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/finances-master-data/initial-balances/destroy`, new FormData(this.formDelete), {
                                params: {
                                    branch_id: this.branchId,
                                }
                            });
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
