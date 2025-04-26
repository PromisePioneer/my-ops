@extends('layouts.template')
@section('page-title', 'Barang Masuk yang Belum Diproses')
@section('content')
    <div x-data="draftStockData()">
        @include('pages.inventory.goods.stocks.draft-stocks.generate-code')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-250px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid main-branches-select2"
                                            name="branch_id" id="branch-id-filter">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div
                                                class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox"
                                                       @click="toggleAllCheckBox()">
                                            </div>
                                        </th>
                                        <th class="min-w-125px text-center">No.Transaksi</th>
                                        <th class="min-w-125px text-center">Nama Barang</th>
                                        <th class="min-w-125px text-center">Belum Terdata</th>
                                        <th class="min-w-125px text-center">Actions</th>
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
                                    <template x-if="!isLoading && draftStocks.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(stock, index) in draftStocks?.data" :key="stock.id">
                                        <tr>
                                            <td x-text="startIndex + index++"></td>
                                            <td class="text-center" x-text="stock.transaction_number"></td>
                                            <td class="text-center" x-text="stock.name"></td>
                                            <td class="text-center" x-text="stock.qty"></td>
                                            <td class="text-center">
                                                <a :href="`/inventory/goods/draft-stocks/detail/${stock.id}`"
                                                   class="btn btn-light-primary btn-sm">
                                                    <x-icons.plus></x-icons.plus>
                                                    Buat Kode
                                                </a>
                                            </td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in draftStocks.links">
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
@endsection
@push('script')
    <script>
        function draftStockData() {
            return {
                isLoading: false,
                draftStocks: [],
                buttonLoading: false,
                startIndex: null,
                search: '',
                editVal: '',
                async init() {
                    await this.getDraftStocks();
                    await this.getMainBranches();
                },
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async filter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/draft-stocks/filter', {
                            params: {
                                branch_id: $('#branch-id-filter').val()
                            }
                        });
                        this.draftStocks = resp.data;
                    } catch (e) {

                    } finally {
                        this.isLoading = false;
                    }
                },
                async getDraftStocks() {
                    this.isLoading = true
                    try {
                        const resp = await axios.get('/inventory/goods/draft-stocks/data');
                        this.draftStocks = resp.data;
                        this.startIndex = this.draftStocks.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
