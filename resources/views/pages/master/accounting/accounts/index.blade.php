@extends('layouts.template')
@section('page-title', 'Daftar Akun')
@section('breadcrumbs', 'Master Keuangan - Akun')
@section('content')
    <div x-data="accountData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.master.accounting.accounts.modal.create')
            @include('pages.master.accounting.accounts.modal.create-children')
            @include('pages.master.accounting.accounts.modal.edit-children')
            @include('pages.master.accounting.accounts.modal.edit')
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
                        @can('Tambah Data Akun')
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create" @click="add()">
                                <x-icons.add-item/>
                                Tambah
                            </button>
                        @endcan
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input"
                                               type="checkbox" @click="toggleAllCheckBox()"
                                               :disabled="Number(deletePermission) !== 1"
                                        >
                                    </div>
                                </th>
                                <th class="min-w-125px">Akun</th>
                                <th class="min-w-125px">Tipe Neraca Saldo / Saldo Awal</th>
                                <th class="min-w-125px">Aksi</th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <x-table.loading colspan="4"/>
                            </template>
                            <template x-if="!isLoading && accounts.data?.length === 0">
                                <x-table.empty colspan="4"/>
                            </template>
                            <template x-for="(account, index) in accounts.data" :key="account.account_id">
                                <tbody style="cursor:pointer" class="fw-bold text-center">
                                <tr :id="account.account_id" @click="expand($event)">
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox"
                                                   :value="account.account_id"
                                                   :id="'checkbox-' + account.account_id"
                                                   :disabled="Number(deletePermission) !== 1"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" x-text="`${account.account_code} - ${account.account_name}`"></a>
                                    </td>
                                    <td class="text-uppercase" x-text="account.trial_balance_type"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(account.account_id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-create-children"
                                                @click="edit(account.account_id)">
                                            <i class="ki-duotone ki-add-folder">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                <template x-for="subAccount in account.sub_accounts">
                                    <tr :id="subAccount.sub_account_id" @click="expand($event)"
                                        x-transition:enter.duration.500ms
                                        x-transition:leave.duration.400ms>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox"
                                                       :value="subAccount.sub_account_id"
                                                       :id="'checkbox-' + subAccount.sub_account_id"
                                                       :disabled="Number(deletePermission) !== 1"/>
                                            </div>
                                        </td>
                                        <td placement="center"
                                            x-text="`${subAccount.sub_account_code} ${subAccount.sub_account_name}`"></td>
                                        <td class="text-uppercase" x-text="subAccount.trial_balance_type"></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit-children"
                                                    @click="edit(subAccount.sub_account_id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
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
    @include('components.select2.script')
@endsection
@push('script')
    <script>
        function accountData() {
            return {
                editPermission: "{{ request()->user()->can('Edit Data Akun') }}",
                deletePermission: "{{ request()->user()->can('Hapus Data Akun') }}",
                accounts: [],
                buttonLoading: false,
                toggleAccountCategory: false,
                isLoading: true,
                startIndex: null,
                search: '',
                editVal: '',
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                akunId: '',
                selectedRow: [],
                expandRows: false,
                formCreate: document.getElementById('form-create'),
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formEdit: document.getElementById('form-edit'),
                modalEdit: new bootstrap.Modal(document.getElementById('modal-edit')),
                deleteForm: document.getElementById('form-delete'),
                modalCreateChildren: new bootstrap.Modal(document.getElementById('modal-create-children')),
                formCreateChildren: document.getElementById('form-create-children'),
                formEditChildren: document.getElementById('form-edit-children'),
                modalEditChildren: new bootstrap.Modal(document.getElementById('modal-edit-children')),
                async init() {
                    await this.getAccountData();
                    await select2('.companies-select2', 'Pilih Perusahaan', '/select2/companies-data');
                    await select2('.parent-account-select2', 'Pilih Akun Induk', '/select2/parent-accounts-data');
                    await select2('.account-categories-select2', 'Pilih Kategori Akun', '/select2/account-categories-data');
                },
                add() {
                    this.editVal = '';
                    this.toggleAccountCategory = false;
                },
                toggleAllCheckBox() {
                    this.selectAll = true;
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
                        const response = await axios.get('/master/accounting/accounts/search', {
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
                        await axios.post('/master/accounting/accounts', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.successResponse();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async saveChild(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/accounting/accounts/create-child/${id}`,
                            new FormData(this.formCreateChildren))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formCreateChildren.reset();
                        this.modalCreateChildren.hide();
                        await this.successResponse();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/accounting/accounts/edit/${id}`);
                    this.editVal = resp.data;
                    this.toggleAccountCategory = resp.data.category_id !== null;
                    console.log(this.toggleAccountCategory);

                    if (this.toggleAccountCategory) {
                        await selectedValue('selected-account-category', `/select2/selected-account-category/${this.editVal.category_id}`);
                    }


                    if(this.editVal.company_id){
                        await selectedValue('selected-company', `/select2/selected-company/${this.editVal.company_id}`);
                    }

                    if (this.editVal.parent_id) {
                        await selectedValue('parent_id', `/select2/selected-account/${this.editVal.parent_id}`);
                    }
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/accounting/accounts/update/${id}`,
                            new FormData(this.formEditChildren))
                        await showAlert('success', 'Data berhasil diubah')
                        this.formEditChildren.reset();
                        this.modalEditChildren.hide();
                        await this.successResponse();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },

                async updateParent(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/master/accounting/accounts/update/${id}`,
                            new FormData(this.formEdit))
                        await showAlert('success', 'Data berhasil diubah')
                        this.formEdit.reset();
                        this.modalEdit.hide();
                        await this.successResponse();
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
                            await axios.post(`/master/accounting/accounts/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.successResponse();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/accounts/import/', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.successResponse();
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
                        const accounts = await axios.get('/master/accounting/accounts/data');
                        this.accounts = accounts.data
                        this.startIndex = this.accounts.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async successResponse() {
                    this.editVal = '';
                    const resp = await axios.get(`${this.accounts.path}?page=${this.accounts.current_page}`, {
                        params: {
                            search: this.search,
                        }
                    });
                    this.accounts = resp.data
                }
            }
        }
    </script>
@endpush
