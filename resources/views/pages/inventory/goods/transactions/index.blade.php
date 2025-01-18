@extends('layouts.template')
@section('page-title', 'Transaksi Barang')
@section('content')
    <div x-data="transactionData()">
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
                            <a href="{{ url('/inventory/goods/goods-transaction/create') }}"
                               class="btn btn-light-primary btn-sm">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Tambah Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12">
                    <form id="form-delete" @submit.prevent="destroy()">
                        <input type="hidden" :name="`id[]`">
                        <button type="submit" class="btn btn-light-danger btn-sm mt-5">
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
                        <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Barang</th>
                                <th class="min-w-125px">Lokasi Kirim</th>
                                <th class="min-w-125px">Lokasi Terima</th>
                                <th class="min-w-125px">Qty</th>
                                <th class="min-w-125px">Pengirim</th>
                                <th class="min-w-125px">Penerima</th>
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
                            <template x-if="!isLoading && goodsTransaction.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(transaction, index) in goodsTransaction?.data" :key="transaction.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td x-text="formatDate(transaction.date)"></td>
                                    <td x-text="transaction.item_name"></td>
                                    <td x-text="transaction.from"></td>
                                    <td x-text="transaction.to"></td>
                                    <td x-text="transaction.from_po === 1 ? transaction.po.qty : transaction.qty"></td>
                                    <td x-text="transaction.sent_by"></td>
                                    <td x-text="transaction.received_by"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" @click="edit(transaction.id)">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </button>
                                        <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-create-children"
                                                @click="edit(transaction.id)">
                                            <i class="ki-duotone ki-add-folder">
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
                        <template x-for="pagination in goodsTransaction.links">
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
        function transactionData() {
            return {
                isLoading: false,
                goodsTransaction: [],
                search: '',
                startIndex: null,
                async init() {
                    const resp = await axios.get('/inventory/goods/goods-transaction/data');
                    this.goodsTransaction = resp.data;
                    this.startIndex = this.goodsTransaction.from
                }
            }
        }
    </script>
@endpush
