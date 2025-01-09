<div class="modal fade" tabindex="-1" id="modal-confirm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Barang</h5>
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

            <form id="form-confirm" @submit.prevent="confirm()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="form-label required">Tanggal Masuk</label>
                            <input type="date" class="form-control form-control-solid date" name="date"
                                   placeholder="Tanggal Masuk">
                        </div>
                        <template x-if="!detailVal.branch_id">
                        <div class="col-lg-6">
                                <label class="form-label required">Tujuan Barang</label>
                                <select name="warehouse_id" id="warehouse_id"
                                    class="form-select form-select-solid warehouse-select2"
                                    data-dropdown-parent="#modal-confirm">
                                <option></option>
                                </select>
                        </div>
                            </template>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="form-label required">Nama Barang</label>
                            <input type="text" disabled class="form-control form-control-solid"
                                   :value="detailVal.name">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label required">Kuantitas (Barang layak pakai)</label>
                            <input type="number" class="form-control form-control-solid" name="qty_can_be_used"
                                   :max="itemsCanBeUsed"
                                   x-model="itemsCanBeUsed"
                                   :value="detailVal.qty">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="form-label required">Kuantitas (barang yang tidak layak pakai)</label>
                            <input type="text" class="form-control form-control-solid" name="qty_cannot_be_used"
                                   :value="detailVal.qty - itemsCanBeUsed" x-model="itemCannotBeUsed" readonly>
                            <span class="text-danger">Kuantitas barang tidak layak pakai akan otomatis masuk ke return PO</span>
                        </div>
                    </div>
                    <div x-show="detailVal.qty - itemsCanBeUsed > 0" x-transition>
                        <label class="form-label">Alasan Barang di retur (Jika retur)</label>
                        <textarea name="reason" id="reason"
                                  class="form-control form-control-solid" data-kt-autosize="true"></textarea>
                    </div>
                    <div class="mt-10">
                    <span class="text-danger">
                         Notes : Jika kuantitas barang yang sampai tidak sesuai, maka ubah kuantitas barang sesuai barang yang diterima.
                    </span>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan & Kirim barang'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
