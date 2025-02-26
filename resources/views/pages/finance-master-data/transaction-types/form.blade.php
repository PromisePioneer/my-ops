<div class="modal fade" tabindex="-1" id="modal-transaction-type">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Jenis Transaksi</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
            <span class="svg-icon svg-icon-2x">
                        <i class="fas fa-xmark-circle"></i>
                    </span>
                </div>
            </div>

            <form id="form-transaction-type" @submit.prevent="save(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="name" :value="editVal.name"/>
                    </div>

                    <div class="mb-10">
                        <label for="debit_account_id" class="required form-label">Akun Debit</label>
                        <select name="debit_account_id" id="selected-debit-account"
                                class="form-select form-select-solid accounts-select2"
                                data-dropdown-parent="#modal-transaction-type">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="credit_account_id" class="required form-label">Akun Kredit</label>
                        <select name="credit_account_id" id="selected-credit-account"
                                class="form-select form-select-solid accounts-select2"
                                data-dropdown-parent="#modal-transaction-type">
                            <option></option>
                        </select>
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
