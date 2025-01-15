@extends('layouts.template')
@section('page-title', 'Inventory Controller - Stok Barang Cabang')
@section('content')
    <div class="card">
        <div class="card-header card-header-stretch">
            <h3 class="card-title">Stok Barang</h3>
            <div class="card-toolbar">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#item-distribution">
                            Distribusi Barang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#stock-data">Stok Barang</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="item-distribution" role="tabpanel">
                    @include('pages.inventory.goods.branch-warehouse-items.item-distribution.index')
                </div>

                <div class="tab-pane fade" id="stock-data" role="tabpanel">
                    @include('pages.inventory.goods.branch-warehouse-items.branch-stock.index')
                </div>
            </div>
        </div>
    </div>

    @include('components.toast')
@endsection
@push('script')
    <script defer>
        function warehouseStocksData() {
            return {
                startIndex: null,
                warehouseStocks: [],
                stocks: [],
                stockDetail: [],
                isLoading: false,
                buttonLoading: false,
                search: '',
                editVal: '',
                // modalGetStock: new bootstrap.Modal(document.getElementById('modal-get-stocks')),
                async init() {
                    await this.getCentralWarehouseStock();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/list-of-items/branch-warehouse-items/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.warehouseStocks = resp.data;
                    } catch (error) {
                        console.log(error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.warehouseStocks = resp.data
                    }
                },
                async getPaginationEnpointForStock(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.stock = resp.data
                    }
                },
                async getCentralWarehouseStock() {
                    const resp = await axios.get('/inventory/list-of-items/branch-warehouse-items/data');
                    this.warehouseStocks = resp.data;
                    this.startIndex = this.warehouseStocks.from;
                },
                async getStock(id) {
                    this.isLoadingStock = true;
                    try {
                        const resp = await axios.get(`/inventory/list-of-items/central-warehouse-items/get-stocks/${id}`);
                        this.stock = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoadingStock = false;
                    }
                },
            }
        }
    </script>
@endpush
