@extends('layouts.template')
@section('page-title', 'Daftarkan Barang')
@section('content')
    <div x-data="centralWarehouseItemDetailData()">
        @include('pages.inventory.list-of-items.central-warehouse.modal.create-sn')
        @include('pages.inventory.list-of-items.central-warehouse.modal.edit-item')
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title text-uppercase text-decoration-underline fw-bold">
                            Detail barang yang belum diberi SN
                        </div>
                    </div>
                    <div class="card-body py-0">
                        <div class="table-responsive">
                            <table class="table w-100">
                                <thead>
                                <tr class="fw-bold ">
                                    <th class="w-25">Tanggal Masuk</th>
                                    <th class="w-10px">:</th>
                                    <th x-text="formatDate(centralWarehouseItem?.date)"></th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Nama Barang</th>
                                    <th>:</th>
                                    <th x-text="centralWarehouseItem?.item?.name"></th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Kuantitas</th>
                                    <th>:</th>
                                    <th x-text="`${centralWarehouseItem?.qty} ${centralWarehouseItem?.item?.unit_type.name}`"></th>
                                </tr>
                                <tr class="fw-bold">
                                    <th>Lokasi Gudang</th>
                                    <th>:</th>
                                    <th x-text="centralWarehouseItem?.warehouse.name"></th>
                                </tr>
                                </thead>
                                <tbody class="fw-bold">
                                <tr>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="alert alert-info px-5 pt-2 mb-xl-8">
                    <div class="d-flex align-items-center mb-2 pt-5">
                        <i class="bi bi-info-circle-fill fs-1 text-info me-4">
                            <span
                                    class="path1"></span><span class="path2"></span>
                        </i>
                        <h4 class="pt-1 text-dark">Informasi</h4>
                    </div>
                    <ol>
                        <li>Barang yang belum diberi SN tidak dapat didistribusikan.</li>
                        <li>Barang yang sudah terverifikasi tidak dapat dihapus maupun diubah jika ada kesalahan
                            (dapat menghubungi stakeholder terkait).
                        </li>
                        <li>Barang yang memiliki SN tidak dapat generate langsung dan harus memasukkan SN satu
                            persatu.
                        </li>
                    </ol>
                </div>
            </div>


        </div>
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
                            <template
                                    x-if="centralWarehouseItem?.item?.need_sn === 0 && centralWarehouseItem?.qty !== 0">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        @click="generateCodeWithoutSN()">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Buat Kode Otomatis
                                </button>
                            </template>
                            <template
                                    x-if="centralWarehouseItem?.item?.need_sn === 1 && centralWarehouseItem?.qty !== 0">
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-sn-create">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="d-flex align-items-center">
                    <form id="form-delete" @submit.prevent="destroy()" class="me-3">
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
                    <form id="form-confirm" @submit.prevent="confirm()">
                        <input type="hidden" :name="`id[]`" :value="selectedCheckBox">
                        <button type="submit" class="btn btn-light-info btn-sm mt-5"
                                x-show="selectedCheckBox.length > 0"
                                x-transition x-cloak>
                            <i class="bi bi-check-circle-fill"></i>
                            Konfirmasi
                        </button>
                    </form>
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">SN</th>
                                <th class="min-w-125px">Status</th>
                                <template x-if="centralWarehouseItem?.item?.need_sn === 1">
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
                            <template x-if="!isLoading && centralWarehouseStocks?.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="stock in centralWarehouseStocks?.data"
                                      :key="stock.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td>
                                        <template x-if="stock.status === 0">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="stock.id"
                                                       :id="'checkbox-' + stock.id"/>
                                            </div>
                                        </template>
                                    </td>
                                    <td x-text="stock.sn"></td>
                                    <template x-if="stock.status === 0">
                                        <td>
                                            <button class="btn btn-sm btn-light-danger">
                                                <i class="bi bi-x-square-fill"></i>
                                                Pending
                                            </button>
                                        </td>
                                    </template>
                                    <template x-if="stock.status === 1">
                                        <td>
                                            <button class="btn btn-sm btn-light-success">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Terverifikasi
                                            </button>
                                        </td>
                                    </template>
                                    <td>
                                        <template
                                                x-if="centralWarehouseItem?.item?.need_sn === 1 && stock.status === 0">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-item-edit" @click="editItem(stock.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </button>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ url('/inventory/list-of-items/central-warehouse-items') }}"
                           class="btn btn-light-danger btn-sm">Kembali</a>
                        <ul class="pagination float-end">
                            <template x-for="pagination in centralWarehouseStocks?.links">
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        function centralWarehouseItemDetailData() {
            return {
                buttonLoading: false,
                isLoading: false,
                id: "{{ $centralWarehouseItem->id }}",
                centralWarehouseItem: null,
                centralWarehouseStocks: [],
                selectedCheckBox: [],
                selectAll: false,
                singleChecked: false,
                search: '',
                modalCreateSN: new bootstrap.Modal(document.getElementById('modal-sn-create')),
                formCreateSN: document.getElementById('form-sn-create'),
                formConfirm: document.getElementById('form-confirm'),
                formDelete: document.getElementById('form-delete'),
                modalEditItem: new bootstrap.Modal(document.getElementById('modal-item-edit')),
                formEditItem: document.getElementById('form-item-edit'),
                editVal: '',
                async init() {
                    await this.getCentralWarehouseItem();
                    await this.getCentralWarehouseStock();
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
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.centralWarehouseStocks = resp.data
                    }
                },
                async getCentralWarehouseStock() {
                    const resp = await axios.get(`/inventory/list-of-items/central-warehouse-stocks/data/${this.id}`);
                    this.centralWarehouseStocks = resp.data;
                },
                async getCentralWarehouseItem() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/inventory/list-of-items/central-warehouse-items/detail/data/${this.id}`);
                        this.centralWarehouseItem = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    try {
                        const resp = await axios.get(`/inventory/list-of-items/central-warehouse-stocks/search/${this.id}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.centralWarehouseStocks = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async generateCodeAndSN() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/central-warehouse-stocks/generate-sn/${this.id}`, new FormData(this.formCreateSN))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreateSN.reset();
                        this.modalCreateSN.hide();
                        await this.init();
                    } catch (error) {
                        if (this.centralWarehouseItem?.qty === 0) {
                            toastr.error(error.response.data.message);
                        } else {
                            const respError = error.response.data.errors;
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }


                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async generateCodeWithoutSN() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/central-warehouse-stocks/generate-code-without-sn/${this.id}`, new FormData(this.formCreateSN))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreateSN.reset();
                        this.modalCreateSN.hide();
                        await this.init();
                    } catch (error) {
                        if (this.centralWarehouseItem?.qty === 0) {
                            toastr.error(error.response.data.message);
                        } else {
                            const respError = error.response.data.errors;
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async editItem(id) {
                    const resp = await axios.get(`/inventory/list-of-items/central-warehouse-stocks/edit/${id}`);
                    this.editVal = resp.data;
                },
                async update(id) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/list-of-items/central-warehouse-stocks/update/${id}`, new FormData(this.formEditItem))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formEditItem.reset();
                        this.modalEditItem.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async confirm() {
                    showConfirmModal("Anda yakin?", "Data tidak bisa dihapus atau diubah jika di konfirmasi.", "Ya, Konfirmasi!", async () => {
                        try {
                            await axios.post(`/inventory/list-of-items/central-warehouse-stocks/confirm`, new FormData(this.formConfirm));
                            await showAlert('success', 'Data sukses dikonfirmasi');
                            await this.init();
                        } catch (error) {
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async destroy() {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/inventory/list-of-items/central-warehouse-stocks/destroy`, new FormData(this.formDelete));
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
