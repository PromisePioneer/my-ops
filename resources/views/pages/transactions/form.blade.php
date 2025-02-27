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
                        <div class="col-md-6">
                                <label for="branch_id" class="required form-label">Cabang</label>
                                <select name="branch_id" id="selected-branch"
                                        class="form-select form-select-solid main-branches-select2"
                                        data-dropdown-parent="#modal-transactions">
                                    <option></option>
                                </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="required form-label">Tanggal</label>
                            <input type="date" id="date" name="date" class="form-control-solid form-control date"
                                   placeholder="Tanggal Transaksi"
                                   :value="editVal?.date">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="detail" class="required form-label">
                            Detail Transaksi
                        </label>
                        <textarea class="form-control form-control-solid" name="detail" id="detail"
                                  placeholder="Detail Transaksi" :value="editVal?.detail"
                                  data-kt-autosize="true"></textarea>
                    </div>

                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Qty</label>
                            <input type="text" class="form-control form-control-solid" name="qty" id="qty"
                                   placeholder="Kuantitas" :value="editVal?.qty">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Satuan</label>
                            <select name="unit_type_id" id="selected-unit-type"
                                    class="form-select form-select-solid unit-types-select2"
                                    data-dropdown-parent="#modal-transactions"></select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">
                                Harga Satuan
                            </label>
                            <input type="number" class="form-control form-control-solid" name="unit_price"
                                   id="unit_price" :value="editVal?.unit_price" placeholder="Harga Satuan"/>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="required form-label">
                                Total Harga
                            </label>
                            <input type="number" class="form-control form-control-solid" name="total_price"
                                   id="total_price" :value="editVal?.unit_price" placeholder="Total Harga"/>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">
                                Akun Debit
                            </label>
                            <select name="debit_account_id" id="selected-debit-account"
                                    class="form-select form-select-solid accounts-select2"
                                    data-dropdown-parent="#modal-transactions">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">
                                Akun Kredit
                            </label>
                            <select name="credit_account_id" id="selected-credit-account"
                                    class="form-select-solid form-select accounts-select2"
                                    data-dropdown-parent="#modal-transactions">
                                <option></option>
                            </select>
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
