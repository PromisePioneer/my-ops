@extends('layouts.template')
@section('page-title', 'Saldo Awal Persediaan')
@section('breadcrumbs', 'Master Keuangan - Saldo Awal Persediaan')
@section('content')
    <div x-data="initialInventoryBalance()">
        @include('pages.master.accounting.initial-inventory-balances.filter')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
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
                            @can('Tambah Data Saldo Awal Persediaan')
                                <a href="{{ url('/master/accounting/initial-inventory-balances/create') }}"
                                   type="button" class="btn btn-light-primary btn-sm me-2">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </a>
                            @endcan
                            @can('Filter Data Saldo Awal Persediaan Berdasarkan Cabang')
                                <button class="btn btn-light-info btn-sm"
                                        id="initial-inventory-balances-filter">
                                    <x-icons.filter/>
                                    Filter
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <form id="form-confirm" @submit.prevent="confirm()" class="me-3">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="ki-duotone ki-check-square">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Konfirmasi
                        </button>
                    </form>

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
                        <table class="table align-middle fs-6 gy-5 table-bordered">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    #
                                </th>
                                <th class="min-w-125px text-center">Informasi Persediaan</th>
                                <th class="min-w-125px text-center">Akun</th>
                                <th class="min-w-125px text-center">Detail</th>
                                <th class="min-w-125px text-center">Dokumentasi</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && initialInventoryBalances.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(inventory, index) in initialInventoryBalances?.data" :key="index">
                                <tr class="text-center">
                                    <td>
                                        <template x-if="inventory.status === 'Diproses'">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox"
                                                       :value="inventory.id"
                                                       :id="'checkbox-' + inventory.id"/>
                                            </div>
                                        </template>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column text-center">
                                            <span x-text="inventory.branch_name"></span>
                                            <span x-text="inventory.transaction_number"></span>
                                            <span x-text="inventory.date"></span>
                                            <hr>
                                            <div>
                                                <template x-if="inventory.unit_type === 'Meter'">
                                                    <div>
                                                        <p class="text-decoration-underline m-0"
                                                           x-text="`${inventory.qty} Haspel`"></p>
                                                        <p class="text-decoration-underline m-0"
                                                           x-text="`${inventory.qty_in_meter} Meter per Haspel`"></p>
                                                    </div>
                                                </template>
                                            </div>
                                            <template x-if="inventory.unit_type !== 'Meter'">
                                             <span class="text-decoration-underline"
                                                   x-text="`${inventory.qty} ${inventory.unit_type}`"></span>
                                            </template>
                                            <span x-text="inventory.item_name"></span>
                                            <span x-text="inventory.total_price"></span>
                                        </div>
                                    </td>
                                    <td x-text="inventory.stock_account"></td>
                                    <td x-text="inventory.detail"></td>
                                    <td class="text-center">
                                        <a href="#">
                                            <div class="symbol-label">
                                                <a href="#" @click="openImageList(inventory.attachment)">
                                                    <img :src="getImageURL(inventory.attachment ?? null)"
                                                         alt="Image" class="img-thumbnail h-50 w-50">
                                                </a>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <template x-if="inventory.status === 'Diproses'">
                                            <a :href="`/master/accounting/initial-inventory-balances/edit/${inventory.id}`"
                                               class="btn btn-light-primary btn-sm">
                                                <i class="ki-duotone ki-pencil">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                        </template>


                                        <template x-if="inventory.status === 'Diterima'">
                                              <span class="badge badge-light-success">
                                            <i class="ki-duotone ki-check-square fs-2x text-success">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in initialInventoryBalances.links">
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
        @include('components.toast')
        @include('components.select2.script')
    </div>
@endsection
@push('script')
    <script>
        function initialInventoryBalance() {
            return {
                buttonLoading: false,
                isLoading: false,
                toggleAllCheckBox: false,
                selectedCheckBox: [],
                initialInventoryBalances: [],
                editVal: '',
                search: '',
                deleteForm: document.getElementById('form-delete'),
                confirmForm: document.getElementById('form-confirm'),
                async init() {
                    await this.getInitialInventoryBalances();
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data');
                },
                async filter() {
                    this.buttonLoading = true;
                    this.isLoading = true;
                    this.initialInventoryBalances = [];
                    try {
                        const resp = await axios.get('/master/accounting/initial-inventory-balances/filter', {
                            params: {
                                branch_id: $('#branch_id').val()
                            }
                        })
                        this.initialInventoryBalances = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.buttonLoading = false;
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    this.initialInventoryBalances = [];
                    try {
                        const resp = await axios.get('/master/accounting/initial-inventory-balances/search', {
                            params: {
                                search: this.search,
                                branch_id: $('#branch_id').val()
                            }
                        })
                        this.initialInventoryBalances = resp.data;
                    } catch (e) {

                    } finally {
                        this.isLoading = false;
                    }
                },
                openImageList(imagePath) {
                    console.log(imagePath);
                    const lightbox = new FsLightbox();
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "<?php echo e(asset('')); ?>" + placeholders;
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "<?php echo e(Storage::url('')); ?>" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
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
                add() {
                    this.editVal = '';
                    $('.suppliers-select2').val('', true).trigger('change');
                    $('.stock-accounts-select2').val('', true).trigger('change');
                    this.branchVal = false;
                },
                async getInitialInventoryBalances() {
                    this.isLoading = false;
                    try {
                        const resp = await axios.get('/master/accounting/initial-inventory-balances/data');
                        this.initialInventoryBalances = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "data akan dihapus dan tidak akan dapat dikembalikan.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/master/accounting/initial-inventory-balances/destroy`, new FormData(this.deleteForm));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Data tidak akan bisa diubah ataupun dihapus.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/master/accounting/initial-inventory-balances/confirm`, new FormData(this.confirmForm));
                            await showAlert('success', 'Data sukses dikonfirmasi');
                            await this.init();
                            this.selectedCheckBox = [];
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },

                async saveInitialInventoryBalance(id = null) {
                    this.buttonLoading = true;
                    try {
                        if (!id) {
                            await axios.post('/master/accounting/initial-inventory-balances/store', new FormData(this.form))
                        } else {
                            await axios.post(`/master/accounting/initial-inventory-balances/update/${id}`, new FormData(this.form))
                        }
                        await showAlert('success', 'Data berhasil disimpan')
                        this.form.reset();
                        await this.modal.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error?.response?.data?.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('')  }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
            }
        }
    </script>
@endpush
