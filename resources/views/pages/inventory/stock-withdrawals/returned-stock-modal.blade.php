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
                    <div>

                    </div>
                    <div>
                        <template
                            x-if="!stockWithdrawalItem.item_catalog?.qty_in_meter && stockWithdrawalItem.item_catalog?.item?.category?.name !== 'Kategori 3'">
                            <div>

                                <div class="mb-4">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Status</label>
                                    <select class="form-select form-select-solid" name="status" id="status"
                                            x-model="itemStatus">
                                        <option value="Habis">Habis</option>
                                        <option value="Dikembalikan">Dikembalikan</option>
                                    </select>
                                </div>
                                <div x-show="itemStatus === 'Habis' || itemStatus === 'Dikembalikan'">
                                    <div class="mb-4">
                                        <label
                                            class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Status</label>
                                        <select class="form-select form-select-solid" name="item_condition"
                                                id="item_condition">
                                            <option value="Rusak">Rusak</option>
                                            <option value="Baik">Baik</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div>
                        <template x-if="stockWithdrawalItem?.withdrawal_item?.code">
                            <div>
                                <template x-if="stockWithdrawalItem.item_catalog?.qty_in_meter">
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

                                        <div class="mb-4" x-show="itemCondition === 'Ada Rusak'" x-transition x-cloak>
                                            <label
                                                class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                                Jumlah Rusak</label>
                                            <input type="number" class="form-control form-control-solid"
                                                   name="broken_qty"
                                                   id="broken_qty"
                                                   placeholder="Jumlah Rusak" value="0">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div>
                        <template x-if="!stockWithdrawalItem.withdrawal_item?.code">
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
