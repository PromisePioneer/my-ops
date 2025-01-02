@extends('layouts.template')
@section('page-title', 'Daftarkan Barang')
@section('content')
    <div x-data="centralWarehouseItemDetailData()">
        @include('pages.inventory.list-of-items.central-warehouse-items.modal.create-sn')
        <div class="row">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title text-uppercase text-decoration-underline">
                        Detail barang yang belum diberi SN
                    </div>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table w-25">
                            <thead>
                            <tr class="fw-bold">
                                <th>Tanggal Masuk</th>
                                <th>:</th>
                                <th x-text="formatDate(centralWarehouseItem?.po.date)"></th>
                            </tr>
                            <tr class="fw-bold">
                                <th>Nama Barang</th>
                                <th>:</th>
                                <th x-text="centralWarehouseItem?.item.name"></th>
                            </tr>
                            <tr class="fw-bold">
                                <th>Kuantitas</th>
                                <th>:</th>
                                <th x-text="centralWarehouseItem?.qty"></th>
                            </tr>
                            <tr class="fw-bold">
                                <th>Satuan</th>
                                <th>:</th>
                                <th x-text="centralWarehouseItem?.unit_type.name"></th>
                            </tr>
                            <tr class="fw-bold">
                                <th>Lokasi</th>
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
                                <button type="button" class="btn btn-light-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-sn-create">
                                    <i class="ki-duotone ki-message-add fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="col-12">
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
                            <table class="table table-bordered fs-6 gy-5" id="kt_table_users">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox"
                                                   @click="toggleAllCheckBox()">
                                        </div>
                                    </th>
                                    <template x-if="centralWarehouseItem.item.need_sn === 1">
                                        <th class="min-w-125px">SN</th>
                                    </template>
                                    <th class="min-w-125px">Kode</th>
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
                                <template x-if="!isLoading && branches.data?.length === 0">
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
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-sm form-check-custom form-check-solid"
                                                 @click="selectCheckBox($event)">
                                                <input class="form-check-input" type="checkbox" :value="stock.id"
                                                       :id="'checkbox-' + stock.id"/>
                                            </div>
                                        </td>
                                        <td x-text="stock.sn"></td>
                                        <td x-text="stock.code"></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-edit" @click="edit(branch.id)">
                                                <i class="ki-duotone ki-pencil fs-2">
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
                        <ul class="pagination float-end mb-4 mt-4">
                            <template x-for="pagination in centralWarehouseStocks.links">
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
                search: '',
                modalCreateSN: new bootstrap.Modal(document.getElementById('modal-sn-create')),
                formCreateSN: document.getElementById('form-sn-create'),
                async init() {
                    await this.getCentralWarehouseItem();
                    await this.getCentralWarehouseStock();
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
                        if (this.centralWarehouseItem.qty === 0) {
                            toastr.error(error.response.data.message);
                        } else {
                            const respError = error.response.data.errors;
                            Object.keys(respError).map(err => toastr.error(respError[err][0]))
                        }


                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
