@extends('layouts.template')
@section('page-title', 'Inventory Controller - Stok Barang Pusat')
@section('content')
    <div>
        @include('pages.inventory.list-of-items.central-warehouse.modal.item-distribution')
        <div class="card ">
            <div class="card-header card-header-stretch">
                <h3 class="card-title">Stok Barang</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#item-distribution">
                                Transaksi Barang
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
                        @include('pages.inventory.list-of-items.central-warehouse.item-transactions.index')
                    </div>

                    <div class="tab-pane fade" id="stock-data" role="tabpanel">
                        @include('pages.inventory.list-of-items.central-warehouse.central-stock.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
