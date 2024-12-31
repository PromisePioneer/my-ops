@extends('layouts.template')
@section('page-title', 'Daftarkan Barang')
@section('content')
    <div class="row">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            @include('pages.general-master-data.branch.modal.create')
            @include('pages.general-master-data.branch.modal.edit')
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    Total barang yang belum terdaftar SN
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama Barang</th>
                                <th class="min-w-125px">Qty</th>
                                <th class="min-w-125px">Satuan</th>
                            </thead>
                            <tbody class="fw-bold">
                            <tr>
                                <td>{{ $centralWarehouseItem->item->name }}</td>
                                <td>{{ $centralWarehouseItem->qty }}</td>
                                <td>{{ $centralWarehouseItem->unitType->name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in branches.links">
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
