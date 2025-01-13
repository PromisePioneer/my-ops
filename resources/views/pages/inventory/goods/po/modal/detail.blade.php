<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" x-text="detailVal.name"></h5>
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
                <template x-if="detailVal.branch_id !== null">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="form-label required">Cabang</label>
                            <input type="text" disabled class="form-control form-control-solid"
                                   :value="detailVal?.branch_name">
                        </div>
                    </div>
                </template>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="form-label required">No. PO</label>
                        <input type="text" disabled class="form-control form-control-solid"
                               :value="detailVal.po_number">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label required">No. Invoice</label>
                        <input type="text" disabled class="form-control form-control-solid"
                               :value="detailVal.invoice_number">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="form-label required">Nama Barang</label>
                        <input type="text" disabled class="form-control form-control-solid"
                               :value="detailVal.name">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label required">Tanggal Masuk</label>
                        <input type="text" disabled class="form-control form-control-solid"
                               :value="detailVal.date">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="form-label required">Harga Satuan</label>
                        <input type="text" class="form-control form-control-solid" disabled
                               :value="detailVal.unit_price">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label required">Kuantitas</label>
                        <input type="text" class="form-control form-control-solid" disabled
                               :value="detailVal.qty">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label class="form-label required">Ongkos Kirim</label>
                        <input type="text" class="form-control form-control-solid" disabled
                               :value="detailVal.shipping_cost">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label required">PPN</label>
                        <input type="text" class="form-control form-control-solid" disabled
                               :value="detailVal.ppn">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label required">Total Harga</label>
                        <input type="text" class="form-control form-control-solid" disabled
                               :value="detailVal.total_price">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="d-flex align-items-center justify-content-end">
                    <button class="btn btn-light-danger btn-sm" data-bs-dismiss="modal"
                            aria-label="Close">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
