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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="branch_id" class="required form-label">Cabang</label>
                                <select name="branch_id" id="selected-branch"
                                        class="form-select form-select-solid main-branches-select2"
                                        data-dropdown-parent="#modal-transactions">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="transaction_type_id" class="required form-label">Tipe Transaksi</label>
                                <select name="transaction_type_id" id="selected-transaction-type"
                                        class="form-select form-select-solid transaction-types-select2"
                                        data-dropdown-parent="#modal-transactions">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">
                                    Jumlah
                                </label>
                                <input type="number" class="form-control form-control-solid" name="amount"
                                       id="amount"
                                       placeholder="Jumlah" :value="editVal.amount">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Tanggal</label>
                                <input type="date" id="date" name="date" class="form-control form-control-solid date"
                                       placeholder="Tanggal" :value="editVal?.date"
                                />
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="mb-10">
                            <label for="name" class="required form-label">
                                Detail Transaksi
                            </label>
                            <textarea type="text" class="form-control form-control-solid" name="detail" id="detail"
                                      placeholder="Detail" x-text="editVal.detail">
                                </textarea>
                        </div>
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
