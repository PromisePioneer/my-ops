<div class="modal fade" tabindex="-1" id="modal-returning-items">
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

            <form id="form-returning-items" @submit.prevent="save(stockWithdrawalItem.id)">
                <div class="modal-body">
                    <template x-if="stockWithdrawalItem.code">
                        <div class="row">
                            <label
                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Status</label>
                            <select name="status" id="status" class="form-select form-select-solid"
                                    x-model="itemStatus">
                                <option value="Terpakai">Terpakai</option>
                                <option value="Dibawa">Dibawa</option>
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                        </div>
                    </template>
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
