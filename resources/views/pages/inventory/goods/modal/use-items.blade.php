<div class="modal fade" tabindex="-1" id="modal-item-details">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="used-goods-form" @submit.prevent="saveUsedGoods()">
                <div class="modal-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Stok</th>
                                <th class="min-w-125px">Harga Satuan</th>
                                <th class="min-w-125px">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td x-text="detailData.serial_number"></td>
                                <td x-text="detailData.name"></td>
                                <td x-text="detailData.qty"></td>
                                <td x-text="formatNumber(detailData.unit_price)"></td>
                                <td x-text="formatNumber(detailData.total_price)"></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="modal-title mt-10">Detail Barang</h5>
                    <div class="mb-10 mt-10">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name" class="required form-label">Total Terpakai</label>
                                <input type="number" name="total_used" class="form-control form-control-solid"
                                       placeholder="Total Terpakai"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
