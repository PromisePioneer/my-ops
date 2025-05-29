@extends('layouts.template')
@section('page-title', 'Aset')
@section('breadcrumbs', 'Master Keuangan - Aset')
@section('content')
    <div x-data="assetsData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8 ">
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
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            @can('Tambah Data Aset')
                                <a href="{{ url('/master/accounting/assets/create') }}"
                                   class="btn btn-light-primary btn-sm">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </a>
                            @endcan
                        </div>
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
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    #
                                </th>
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Kategori</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Masa Manfaat</th>
                                <th class="min-w-125px">Harga / Unit</th>
                                <th class="min-w-125px">Actions</th>
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

                            <template x-if="!isLoading && assets.data?.length === 0">
                                <tbody class="fw-bold">

                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="asset in assets?.data" :key="asset.id">
                                <tbody class="fw-bold text-center">

                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-ch
                                        eck-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <template x-if="Number(asset.status) === 1">
                                                <input class="form-check-input" type="checkbox" :value="asset.id"
                                                       :id="'checkbox-' + asset.id"
                                                       :disabled="Number(deletePermission) !== 1"/>
                                            </template>
                                        </div>
                                    </td>
                                    <td x-text="`${asset.branch_name ?? 'Pusat'}`"></td>
                                    <td x-text="asset.code"></td>
                                    <td x-text="asset.debit_account"></td>
                                    <td>
                                        <a :href="`${Number(viewDetailPermission) === 1 ? `/master/accounting/assets/detail/${asset.id}` : '' }`"
                                           x-text="asset.name"></a>
                                    </td>
                                    <td x-text="asset.useful_life"></td>
                                    <td x-text="asset.price"></td>
                                    <td>
                                        <template x-if="asset.status == 0">
                                            <template x-if="Number(editPermission) === 1">
                                                <a :href="`/master/accounting/assets/edit/${asset.id}`"
                                                   class="btn btn-light-primary btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </template>
                                        </template>
                                        <button
                                            :class="`${asset.status  === 1  ? 'btn btn-success btn-sm' : 'btn btn-danger btn-sm'}`"
                                            @click="asset.status === 0 ? check(asset.id) : ''"
                                            :disabled="asset.status === 1">
                                            <i class="ki-duotone ki-check-square">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in assets.links">
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
        function assetsData() {
            return {
                deletePermission: "{{ request()->user()->can('Hapus Data Aset') }}",
                viewDetailPermission: "{{ request()->user()->can('Lihat Detail Data Aset') }}",
                editPermission: "{{ request()->user()->can('Edit Data Aset') }}",
                assets: [],
                isLoading: false,
                buttonLoading: false,
                startIndex: null,
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                formDelete: document.getElementById('form-delete'),
                async init() {
                    await this.getAssetsData();
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
                async changeItemCondition() {
                    if (this.itemCondition === 'Digudang') {
                        this.initialInventoryBalanceModal.show();
                        await this.getBranches();
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
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.assets = resp.data
                    }
                },
                async saveInitialInventoryBalance() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/initial-inventory-balances', new FormData(this.initialInventoryBalanceForm))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.initialInventoryBalanceForm.reset();
                        await this.initialInventoryBalanceModal.hide();
                        window.location.href = '/master/accounting/initial-inventory-balances';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                inputMask() {
                    Inputmask("decimal", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        rightAlign: false,
                        allowMinus: false
                    }).mask("#price");
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/master/accounting/assets/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.assets = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
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
                        this.modal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async edit(id) {
                    const resp = await axios.get(`/master/accounting/assets/${id}`);
                    this.editVal = resp.data;
                    await this.selectedMainBranch();
                    await this.selectedBranch();
                    await this.selectedAssetItem();
                },
                async saveSupplier() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/operational/suppliers', new FormData(this.supplierForm))
                            .then(async () => {
                                await showAlert('success', 'data berhasil disimpan');
                                await this.supplierForm.reset();
                                this.supplierModal.hide();
                            })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
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
                            await axios.post(`/master/accounting/assets/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },

                async getAssetsData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/master/accounting/assets/data');
                        this.assets = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async importData() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/master/accounting/assets/import', new FormData(this.formImport))
                        await showAlert('success', 'Data berhasil diimport')
                        this.formImport.reset();
                        this.modalImport.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getKasAccount() {
                    $(".kas-accounts-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Akun',
                        ajax: {
                            url: '/master/accounting/assets/credit-account/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async selectedMainBranch() {
                    const selectedBranch = $('#selected-main-asset-branch');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-branch/${this.editVal.branch.parent_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedBranch.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async selectedBranch() {
                    const selectedBranch = $('#selected-asset-branch');
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
                async selectedAssetItem() {
                    const selectedItem = $('#selected-asset-item');
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

                async check(id) {
                    showConfirmModal("Anda yakin?", "Aset yang sudah di konfirmasi tidak bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/master/accounting/assets/confirm/${id}`);
                            await showAlert('success', 'Data sukses Dikonfirmasi').then(() => {
                                this.init()
                            });
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                closeAssetModal() {
                    this.modal.hide();
                }
            }
        }
    </script>
@endpush
