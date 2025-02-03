@extends('layouts.template')
@section('content')




    <div x-data="goodsStockData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div x-model="placement" class="mb-2">
                            <select class="form-select form-select-solid"
                                    name="placement" id="placement">
                                <option value="0" selected>Pilih tujuan barang</option>
                                <option value="Cabang">Cabang</option>
                                <option value="Gudang">Gudang</option>
                            </select>
                        </div>
                        <div x-show="placement === 'Cabang'" x-transition x-cloak>
                            <div>
                                <select class="form-select form-select-solid branches-select2"
                                        name="branch_id" id="branch_id">
                                </select>
                            </div>
                        </div>
                        <div x-show="placement === 'Gudang'" x-transition x-cloak>
                            <select name="warehouse_id" id="warehouse_id"
                                    class="form-select form-select-solid warehouses-select2">
                                <option></option>
                            </select>
                        </div>

                    </div>
                    <div class="card-footer pt-4 text-end">
                        <button type="button" class="btn btn-light btn-active-primary btn-sm" @click="filter()">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
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
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-1">Total Stok</span>
                                    </div>
                                </th>
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
                            <template x-if="!isLoading && goodsStock.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(item, index) in goodsStock?.data" :key="item.id">
                                <tbody class="fw-bold text-center">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="item.name"></td>
                                    <td x-text="item.total_stock"></td>
                                    <td>
                                        <a :href="`/inventory/goods/stock/detail/${item.id}`"
                                           class="btn btn-light-primary btn-sm">
                                            <i class="bi bi-arrow-right-square-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in goodsStock.links">
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

@endsection
@push('script')
    <script>
        function goodsStockData() {
            return {
                goodsStock: [],
                search: '',
                placement: null,
                isLoading: false,
                startIndex: null,
                async init() {
                    await this.getGoodsStock();
                    await this.getWarehouses();
                    await this.getBranchData();
                },
                async getGoodsStock() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/data');
                        this.goodsStock = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getWarehouses() {
                    $(".warehouses-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Gudang',
                        ajax: {
                            url: '/inventory/goods/po/warehouses/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchData() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/inventory/goods/po/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/goods/stock/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.goodsStock = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    this.isLoading = true;
                    try {
                        const branch_id = $('#branch_id').val();
                        const warehouse_id = $('#warehouse_id').val();
                        const resp = await axios.get('/inventory/goods/stock/filter', {
                            params: {
                                branch_id: branch_id,
                                warehouse_id: warehouse_id,
                            }
                        });
                        this.goodsStock = resp.data;

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
