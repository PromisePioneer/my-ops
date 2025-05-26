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
                                <option value="Dikembalikan">Dikembalikan</option>
                            </select>
                        </div>
                    </template>


                    <template x-if="!stockWithdrawalItem.code">
                        <div>
                            <div class="mb-4">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">
                                    Total Barang dibawa
                                </label>
                                <input type="text" class="form-control form-control-solid"
                                       name="code" id="code" :value="stockWithdrawalItem.qty"
                                       disabled>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Barang yang dikembalikan
                                </label>
                                <input type="number" class="form-control form-control-solid mb-4"
                                       placeholder="" :max="stockWithdrawalItem.qty" min="0"
                                       name="qty" id="qty"
                                       required>
                                <span class="text-danger">Jika tidak ada barang dikembalikan maka buat 0</span>
                            </div>
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
