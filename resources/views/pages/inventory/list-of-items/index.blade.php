@extends('layouts.template')
@section('page-title','Daftar Barang')
@section('content')
    <div x-data="listOfItemData()">
        @include('pages.inventory.list-of-items.modal.detail')
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
                        <button class="btn btn-light-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-create"
                        >
                            Tambah
                        </button>
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox"
                                               @click="toggleAllCheckBox()">
                                    </div>
                                </th>
                                <th class="min-w-125px">SN</th>
                                <th class="min-w-125px">Tanggal Masuk</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Harga Satuan</th>
                                <th class="min-w-125px">Ongkir</th>
                                <th class="min-w-125px">PPN</th>
                                <th class="min-w-125px">Total</th>
                                <th class="min-w-125px">Supplier</th>
                                <th class="min-w-125px">Resi Surat Jalan</th>
                                <th class="min-w-125px">Action</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && leaves.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(item, index) in listOfItems?.data" :key="listOfItems.id">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid"
                                             @click="selectCheckBox($event)">
                                            <input class="form-check-input" type="checkbox" :value="leave.id"
                                                   :id="'checkbox-' + item.id"/>
                                        </div>
                                    </td>
                                    <td x-text="item.sn"></td>
                                    <td x-text="item.date"></td>
                                    <td x-text="item.name"></td>
                                    <td x-text="item.unit_price"></td>
                                    <td x-text="item.shipping_cost"></td>
                                    <td x-text="item.ppn"></td>
                                    <td x-text="item.total_price"></td>
                                    <td x-text="item.supplier"></td>
                                    <td x-text="item.travel_letter_receipt"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail" @click="detail(item.id)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in leaves.links">
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
@endsection
@push('script')
    <script>
        function listOfItemData() {
            return {
                modalDetail: new bootstrap.Modal(document.getElementById('modal-detail')),
                isLoading: false,
                listOfItems: [],
                async init() {
                    const resp = await axios.get('/inventory/list-of-items/data');
                    this.listOfItems = resp.data;
                }
            }
        }
    </script>
@endpush
