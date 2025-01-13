@extends('layouts.template')
@section('page-title', 'Inventory Controller - Stok Barang Pusat')
@section('content')
    <div>
        @include('pages.inventory.goods.central-warehouse.modal.item-distribution')
        <div class="card ">
            <div class="card-header card-header-stretch">
                <h3 class="card-title">Stok Barang</h3>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#po">
                                PO Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#incoming-item">
                                Barang Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#outcoming-item">
                                Barang Keluar
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
                    <div class="tab-pane fade show active" id="po" role="tabpanel">
                        @include('pages.inventory.goods.central-warehouse.item-transactions.po')
                    </div>
                    <div class="tab-pane fade show" id="incoming-item" role="tabpanel">
                        @include('pages.inventory.goods.central-warehouse.item-transactions.incoming-item')
                    </div>

                    <div class="tab-pane fade show" id="outcoming-item" role="tabpanel">
                        @include('pages.inventory.goods.central-warehouse.item-transactions.outgoing-item')
                    </div>

                    <div class="tab-pane fade" id="stock-data" role="tabpanel">
                        @include('pages.inventory.goods.central-warehouse.central-stock.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
