<div class="modal fade" tabindex="-1" id="modal-if-unit-type-meter">
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

            <form id="form-if-unit-type-meter" @submit.prevent="ifUnitTypeMeterStore(stockWithdrawalItem.id)">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-4">
                            <label
                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Status</label>
                            <select class="form-select form-select-solid" name="status" id="status"
                                    x-model="itemStatus">
                                <option value="Sisa">Sisa</option>
                                <option value="Habis">Habis</option>
                            </select>
                        </div>


                        <div class="mb-4" x-show="itemStatus === 'Sisa'" x-transition x-cloak>
                            <label
                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Jumlah</label>
                            <input type="number" class="form-control form-control-solid"
                                   placeholder="Jumlah Sisa" name="remaining_qty" id="remaining_qty"
                                   value="0">
                        </div>

                        <div class="mb-4" x-show="itemStatus === 'Sisa'" x-transition x-cloak>
                            <label
                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Kondisi</label>
                            <select class="form-select form-select-solid" x-model="itemCondition"
                                    name="item_condition" id="item_condition"
                            >
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>

                        <div class="mb-4" x-show="itemCondition === 'Rusak' && itemStatus === 'Sisa'"
                             x-transition x-cloak>
                            <label
                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                Jumlah Rusak</label>
                            <input type="number" class="form-control form-control-solid"
                                   name="broken_qty"
                                   id="broken_qty"
                                   placeholder="Jumlah Rusak" value="0">
                        </div>
                        <div>
                            <label class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Bukti</label>
                            <input type="file" class="form-control form-control-solid" name="attachment"
                                   accept=".jpg,.png,.jpeg"
                                   id="attachment">
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
