<div class="modal fade" tabindex="-1" id="modal-stock-mutations-detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mutasi barang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="card card-custom mb-4">
                            <div class="card-header p-0">
                                <div class="card-body">
                                    <table class="table table-bordered w-100">
                                        <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <template x-for="item in editVal?.stock_mutation_items">
                                            <tr>
                                                <td x-text="item.code ?? '-'"></td>
                                                <td x-text="item.stock.item.name"></td>
                                                <td x-text="item.qty"></td>
                                                <td x-text="item.stock.condition"></td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex align-items-center justify-content-center">\
                    <button class="btn btn-light-primary btn-sm">
                        <x-icons.print/>
                        Cetak Laporan BAST
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
