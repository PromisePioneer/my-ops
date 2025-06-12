@extends('layouts.template')
@section('page-title', 'Pengembalian Barang')
@section('breadcrumbs', 'Inventory Controller - Pemakaian Barang - Pengembalian Barang')
@section('content')
    <div x-data="returnedItemsData()">
        @include('pages.inventory.stock-withdrawals.returned-stock-modal')
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Barang dibawa</h2>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ url('/inventory/stock-withdrawals') }}" class="btn btn-light-danger btn-sm">
                                <x-icons.back/>
                                Kembali
                            </a>
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
                                        <td colspan="6">
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
                                        <td x-text="stock.item_name"></td>
                                        <td>
                                            <template x-if="stock.qty_in_meter">
                                                <span x-text="`${stock.qty_in_meter} Meter`"></span>
                                            </template>

                                            <template x-if="!stock.qty_in_meter">
                                                <span x-text="stock.qty"></span>
                                            </template>
                                        </td>
                                        <td>
                                            <template x-if="!stock.returned_item">
                                                <span
                                                    class="!stock_returned_item">Belum Dikembalikan / Terpakai / Habis</span>
                                            </template>
                                            <template x-if="stock.returned_item">
                                                <span
                                                    class="!stock_returned_item">Sudah Dikembalikan / Terpakai / Habis</span>
                                            </template>

                                        </td>
                                        <td>
                                            <template x-if="!stock.returned_item">
                                                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-returning-items"
                                                        @click="getStockWithdrawalItem(stock.id)">
                                                    <i class="ki-duotone ki-tablet-up fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                    </i>
                                                </button>
                                            </template>
                                            <template x-if="stock.returned_item">
                                                <button class="btn btn-light-success" disabled>
                                                    <x-icons.confirm/>
                                                </button>
                                            </template>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Barang dikembalikan / terpakai / habis</h2>
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
                                    <th class="min-w-125px">Terpakai</th>
                                    <th class="min-w-125px">Dikembalikan</th>
                                    <th class="min-w-125px">Rusak</th>
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
                                <template x-if="!isLoading && consumedOrAppliedStock.data?.length === 0">
                                    <tbody class="fw-bolder text-center">
                                    <tr>
                                        <td colspan="6">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-for="(stock, index) in consumedOrAppliedStock.data" :key="index">
                                    <tbody class="fw-bolder text-center">
                                    <tr>
                                        <td x-text="startIndex + index++"></td>
                                        <td x-text="stock.code ?? '-'"></td>
                                        <td x-text="stock.item_name"></td>
                                        <td x-text="stock.consumed_qty"></td>
                                        <td x-text="stock.returned_qty"></td>
                                        <td x-text="stock.broken_qty"></td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('components.toast')
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
                        itemStatusForItemExceptUnitTypeValue: null,
                        startIndex: 1,
                        consumedOrAppliedStock: [],
                        id: "{{ $stockWithdrawal->id }}",
                        stockWithdrawalItem: {},
                        itemStatus: 'Sisa',
                        itemCondition: null,
                        returnStockModal: new bootstrap.Modal(document.getElementById('modal-returning-items')),
                        returnStockForm: document.getElementById('form-returning-items'),
                        async init() {
                            await this.getCarriedStock();
                            await this.getConsumedOrAppliedStock();
                        },
                        async getCarriedStock() {
                            this.isLoading = true;
                            try {
                                const resp = await axios.get(`/inventory/stock-withdrawals/stock-withdrawal-items/${this.id}`);
                                this.carriedStock = resp.data
                            } catch (e) {
                                console.log(e);
                            } finally {
                                this.isLoading = false;
                            }
                        },
                        async getStockWithdrawalItem(id) {
                            try {
                                const resp = await axios.get(`/inventory/stock-withdrawals/stock-withdrawal-item/${id}`);
                                this.stockWithdrawalItem = resp.data;
                            } catch (e) {
                                console.log(e);
                            }
                        },
                        async getConsumedOrAppliedStock() {
                            try {
                                const resp = await axios.get(`/inventory/returned-items/${this.id}`);
                                this.consumedOrAppliedStock = resp.data;
                                this.startIndex = this.consumedOrAppliedStock.from
                            } catch (e) {
                                console.log(e);
                            }
                        },
                        async save() {
                            this.buttonLoading = true;
                            try {
                                await axios.post(`/inventory/stock-withdrawals/stock-withdrawal-item/return/${this.stockWithdrawalItem.withdrawal_item.id}`, new FormData(this.returnStockForm));
                                await showAlert('success', 'Data berhasil disimpan');
                                this.returnStockModal.hide();
                                await this.init();
                            } catch (error) {
                                const respError = error.response.data.errors;
                                Object.keys(respError).map(err => toastr.error(respError[err][0]))
                            } finally {
                                this.buttonLoading = false;
                            }
                        }
                    }
                }
            </script>
    @endpush
