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
        @include('pages.master.accounting.initial-balances.form')
        <div class="d-flex flex-column flex-xl-row">
            @if(Auth::user()->branch_id === null)
                <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                    <div class="card card-flush">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="mb-0">Filter</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="mb-4">
                                    <x-select2.index
                                        name="branch_id"
                                        id="branch-id-filter"
                                        class="form-select form-select-solid"
                                        elementSelector="main-branches-select2"
                                    />
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
            @endif
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5">
                        <div class="card-title">
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
                            <form id="form-delete" class="py-5" @submit.prevent="destroy()">
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
                            <div class="table-responsive mb-4">
                                <table class="table align-middle fs-6 gy-5 table-bordered mb-0">
                                    <thead>
                                    <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <td colspan="2" class="min-w-125px">Total Saldo Debit</td>
                                        <td colspan="2" class="min-w-125px">Total Saldo Kredit</td>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bolder text-center">
                                    <tr>
                                        <td colspan="2"
                                            :class="initialBalances.total_debit > initialBalances.total_credit || initialBalances.total_debit < initialBalances.total_credit ? 'text-center text-danger' : 'text-success'"
                                            x-text="initialBalances.total_debit"></td>
                                        <td colspan="2"
                                            :class="initialBalances.total_debit > initialBalances.total_credit || initialBalances.total_debit < initialBalances.total_credit ? 'text-center text-danger' : 'text-success'"
                                            x-text="initialBalances.total_credit"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-start">
                                    <span class="text-danger">Note :</span>
                                    <span class="text-danger"><em>Jika berwarna merah maka total debit dan kredit tidak seimbang</em></span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle fs-6 gy-5 table-bordered">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            </div>
                                        </th>
                                        <th class="min-w-125px">Akun</th>
                                        <th class="min-w-125px">Saldo Debit</th>
                                        <th class="min-w-125px">Saldo Kredit</th>
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
                                    <template x-if="!isLoading && initialBalances.initial_balances.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="(account, index) in initialBalances?.initial_balances?.data"
                                              :key="index">
                                        <tbody style="cursor:pointer" class="fw-bold">
                                        <tr>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                     @click="selectCheckBox($event)">
                                                    <template
                                                        x-if="account?.sub_accounts?.length === 0 && branchId !== null">
                                                        <input class="form-check-input" type="checkbox"
                                                               :value="account.id"
                                                               :id="'checkbox-' + account.id"
                                                               :disabled="account.initial_balance === null || Number(deletePermission) !== 1"/>
                                                    </template>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" x-text="account.account"></a>
                                            </td>
                                            <td>
                                                <div>
                                                    <template x-if="account.trial_balance_type === 'debit'">
                                                        <button class="btn btn-light-info btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-initial-balance"
                                                                @click="edit(account.id, 'debit')"
                                                                :disabled="Boolean(disabledAccountButton(branchId, account))">
                                                            <span x-text="account.initial_balance_debit"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </td>
                                            <td>
                                                <template x-if="account.trial_balance_type === 'credit'">
                                                    <button class="btn btn-light-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-initial-balance"
                                                            @click="edit(account.id, 'credit')"
                                                            :disabled="Boolean(disabledAccountButton(branchId, account))">
                                                        <span x-text="account.initial_balance_credit"></span>
                                                    </button>
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
                                                <td>
                                                    <template x-if="subAccount.trial_balance_type === 'debit'">
                                                        <button class="btn btn-light-info btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-initial-balance"
                                                                @click="edit(subAccount.id, 'debit')"
                                                                :disabled="Boolean(disabledSubAccountButton(branchId, subAccount))">
                                                            <span x-text="subAccount.initial_balance_debit"></span>
                                                        </button>
                                                    </template>
                                                </td>
                                                <td>
                                                    <template x-if="subAccount.trial_balance_type === 'credit'">
                                                        <button class="btn btn-light-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modal-initial-balance"
                                                                @click="edit(subAccount.id, 'credit')"
                                                                :disabled="Boolean(disabledSubAccountButton(branchId, subAccount))">
                                                            <span x-text="subAccount.initial_balance_credit"></span>
                                                        </button>
                                                    </template>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in initialBalances?.initial_balances?.links">
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
    @include('components.input-mask')
    @include('components.select2.script')
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
                branchId: "{{ request()->user()->branch_id }}",
                search: "",
                editVal: "",
                accountVal: "",
                branchVal: "",
                entriesType: null,
                form: document.getElementById('form-initial-balance'),
                modalForm: new bootstrap.Modal(document.getElementById('modal-initial-balance')),
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getInitialBalances();
                    await inputMask('amount', 'numeric');
                    await select2('.main-branches-select2', 'Pilih Cabang', '/select2/main-branches-data');
                    if (this.branchId === '') {
                        this.branchId = null;
                    }
                },
                async getInitialBalances() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/accounting/initial-balances/data');
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
                        const resp = await axios.get('/master/accounting/initial-balances/search', {
                            params: {
                                search: this.search,
                                branch_id: this.branchId,
                            },
                        });

                        this.initialBalances.initial_balances = resp.data
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
                async selectedBranch(id) {
                    try {
                        const resp = await axios.get(`/select2/selected-branch/${id}`);
                        this.branchVal = resp.data;
                    } catch (e) {
                        console.log(e)
                    }

                },
                async selectedAccount(id) {
                    try {
                        const resp = await axios.get(`/select2/selected-account/${id}`);
                        this.accountVal = resp.data;
                    } catch (e) {
                        console.log(e);
                    }
                },
                async filter() {
                    this.branchId = $("#branch-id-filter").val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/accounting/initial-balances/filter', {
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
                async save(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/initial-balances/', new FormData(this.form))
                            .then(async () => {
                                await this.successResponse();
                            });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id, entriesType) {
                    this.entriesType = entriesType;
                    const branch_id = $("#branch-id-filter").val();
                    this.editVal = [];
                    const resp = await axios.get(`/master/accounting/initial-balances/${id}`, {
                        params: {
                            branch_id: branch_id,
                            entries_type: this.entriesType
                        }
                    });
                    this.editVal = resp.data;
                    await this.selectedBranch(resp.data.branch_id ?? branch_id);
                    await this.selectedAccount(this.editVal.account_id ?? this.editVal.id);
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/accounting/initial-balances/destroy`, new FormData(this.formDelete), {
                                params: {
                                    branch_id: this.branchId,
                                }
                            });
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            const respError = error.response.data.message;
                            await showAlert('error', `${respError}`);
                        }
                    });
                },
                disabledAccountButton(branchId, account) {
                    if (account?.sub_accounts === []) {
                        return true;
                    }


                    if (account?.sub_accounts?.length > 0) {
                        return true;
                    }


                    if (this.branchId) {
                        return false;
                    }
                    if (!this.branchId) {
                        return true;
                    }


                    return null;
                },
                disabledSubAccountButton(branchId, subAccount) {
                    if (this.branchId === '' || this.branchId === null
                        || subAccount.sub_account_code === '112-01'
                        || subAccount.sub_account_code === '112-02') {
                        return true;
                    } else if (subAccount?.sub_accounts?.length > 0) {
                        return true;
                    }

                    if (this.branchId !== '') {
                        return false;
                    }


                    return null;
                },
                async successResponse() {
                    await showAlert('success', 'Data berhasil disimpan')
                    this.form.reset();
                    this.modalForm.hide();
                    this.entriesType = null;
                    const resp = await axios.get(`${this.initialBalances.initial_balances.path}?page=${this.initialBalances.initial_balances.current_page}`, {
                        params: {
                            branch_id: this.branchId,
                        }
                    });
                    this.initialBalances = resp.data
                },
                notBalanceCondition() {
                    if (this.initialBalances.total_debit > this.initialBalances.total_credit) {
                        return 'text-danger text-center bg-danger';
                    }

                    if (this.initialBalances.total_debit < this.initialBalances.total_credit) {
                        return 'text-danger text-center bg-danger ';
                    }
                }
            }
        }
    </script>
@endpush
