<div class="modal fade" tabindex="-1" id="modal-transactions">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Transaksi</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-transactions" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label class="form-label required">Tipe Transaksi</label>
                            <select name="type" id="type" class="form-select form-select-solid"
                                    x-model="transactionType">
                                <option>--- Pilih ---</option>
                                <option value="Barang">Barang</option>
                                <option value="Beban">Beban</option>
                                <option value="Hutang">Hutang</option>
                                <option value="Piutang">Piutang</option>
                            </select>
                        </div>
                    </div>


                    <div x-show="transactionType === 'Barang'" x-transition x-cloak>
                        @include('pages.transactions.form-types.goods')
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
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
