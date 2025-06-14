<div class="modal fade" tabindex="-1" id="modal-single-and-without-category-3">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengembalian Barang</h5>
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

            <form id="form-single-and-without-category-3"
                  @submit.prevent="itemStatusWithoutCategory3AndUnitTypeMeterStore(stockWithdrawalItem.id)">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-4">
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Status</label>
                            <select name="status" id="status" x-model="itemStatus"
                                    class="form-select form-select-solid">
                                <option value="Terpakai">Terpakai</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                        </div>
                        <div class="mb-4" x-show="itemStatus === 'Dikembalikan'"
                             x-transition x-cloak>
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Kondisi</label>
                            <select :name="`${itemStatus === 'Dikembalikan' ? 'item_condition' : ''}`"
                                    class="form-select form-select-solid">
                                <option value="Rusak">Rusak</option>
                                <option value="Baik">Baik</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Bukti</label>
                            <input type="file" class="form-control form-control-solid" name="attachment"
                                   id="attachment" accept=".jpg,.jpeg,.png"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                        <i class="ki-duotone ki-click fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
