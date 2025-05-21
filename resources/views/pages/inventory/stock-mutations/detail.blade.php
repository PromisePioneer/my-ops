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
                        <div class="ms-4 mb-4">
                            <table class="fw-bolder">
                                <tr>
                                    <td>
                                        Tanggal
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        <span x-text="editVal.date"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Pengirim
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        <span x-text="editVal.sender.name"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Penerima
                                    </td>
                                    <td>
                                        :
                                    </td>
                                    <td>
                                        <span x-text="editVal.receiver.name"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <table class="table table-bordered w-100">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Kode</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="item in editVal?.stock_mutation_items">
                                <tr>
                                    <td x-text="item.stock.item.name"></td>
                                    <td x-text="item.code ?? '-'"></td>
                                    <td x-text="item.qty"></td>
                                    <td x-text="item.stock.condition"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex align-items-center justify-content-center">
                    <template x-if="editVal.sender_signature">
                        <a target="_blank" :href="`/inventory/stock-mutations/bast-document/${editVal.id}`"
                           class="btn btn-light-primary btn-sm me-2">
                            <x-icons.print/>
                            Cetak Laporan BAST
                        </a>
                    </template>
                    <template x-if="editVal.sender_signature">
                        <button class="btn btn-light-danger btn-sm me-2" @click="cancelDelivery(editVal.id)">
                            <x-icons.close/>
                            Batalkan Pengiriman
                        </button>
                    </template>
                    <template x-if="!editVal.sender_signature">
                        <button class="btn btn-light-primary btn-sm" @click="sendItem(editVal.id)">
                            <x-icons.confirm/>
                            Kirim Barang
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
