@extends('layouts.template')
@section('page-title', 'Pengembalian Barang')
@section('breadcrumbs', 'Inventory Controller - Pemakaian Barang - Pengembalian Barang')
@section('content')
    <div x-data="returnedItemsData()">
        @include('pages.inventory.goods.stocks.stock-withdrawals.returned-stock-modal')
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Barang dibawa</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-bordered fs-6 gy-5">
                                <thead>
                                <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">#</th>
                                    <th class="min-w-125px">Kode</th>
                                    <th class="min-w-125px">Barang</th>
                                    <th class="min-w-125px">Qty</th>
                                    <th class="min-w-125px">Status Terkini</th>
                                    <th class="min-w-125px">Actions</th>
                                </tr>
                                </thead>
                                <template x-if="isLoading">
                                    <tbody>
                                    <tr>
                                        <td colspan="5">
                                            <div style="text-align: center;">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-if="!isLoading && carriedStock?.length === 0">
                                    <tbody class="fw-bolder text-center">
                                    <tr>
                                        <td colspan="5">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-for="(stock, index) in carriedStock" :key="index">
                                    <tbody class="fw-bolder text-center">
                                    <tr>
                                        <td x-text="startIndex + index++"></td>
                                        <td x-text="stock.code ?? '-'"></td>
                                        <td x-text="stock.stock.transaction.item.name"></td>
                                        <td x-text="stock.qty"></td>
                                        <td x-text="stock.status"></td>
                                        <td>
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-returning-items"
                                                    @click="getStockWithdrawalItem(stock.id)">
                                                <i class="ki-duotone ki-tablet-up fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm">Konfirmasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @push('script')
            <script>
                function returnedItemsData() {
                    return {
                        isLoading: false,
                        buttonLoading: false,
                        returnedItems: [],
                        search: '',
                        selectedCheckBox: [],
                        carriedStock: [],
                        startIndex: 1,
                        id: "{{ $stockWithdrawal->id }}",
                        stockWithdrawalItem: {},
                        returnStockModal: new bootstrap.Modal(document.getElementById('modal-returning-items')),
                        returnStockForm: document.getElementById('form-returning-items'),
                        async init() {
                            await this.getCarriedStock();
                        },
                        async getCarriedStock() {
                            this.isLoading = true;
                            try {
                                const resp = await axios.get(`/inventory/goods/stock-withdrawals/stock-withdrawal-items/${this.id}`);
                                this.carriedStock = resp.data
                            } catch (e) {
                                console.log(e);
                            } finally {
                                this.isLoading = false;
                            }
                        },
                        async getStockWithdrawalItem(id) {
                            try {
                                const resp = await axios.get(`/inventory/goods/stock-withdrawals/stock-withdrawal-item/${id}`);
                                this.stockWithdrawalItem = resp.data;
                            } catch (e) {
                                console.log(e);
                            }
                        },
                        async save(id) {
                            this.buttonLoading = true;
                            try {
                                await axios.post(`/inventory/goods/stock-withdrawals/stock-withdrawal-item/return/${id}`, new FormData(this.returnStockForm));
                            } catch (e) {

                            } finally {
                                this.buttonLoading = false;
                            }
                        }
                    }
                }
            </script>
    @endpush
