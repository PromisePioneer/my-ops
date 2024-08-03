<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Jurnal Awal</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="update(initialJournalId)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="description" class="required form-label">Deskripsi</label>
                        <textarea name="description" data-kt-autosize="true"
                                  class="form-control form-control-solid" x-text="editVal.description"></textarea>
                    </div>
                    <div class="mb-10">
                        <label for="akun" class="required form-label">Akun Jurnal Awal (Debit)</label>
                        <select name="sub_account_debit" id="selectedDebitAccount"
                                class="form-select form-select-solid account-debit-select2">
                            <option value="0">Pilih Akun</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="akun" class="required form-label">Akun Jurnal Awal (Credit)</label>
                        <select name="sub_account_credit" id="selectedCreditAccount"
                                class="form-select form-select-solid account-credit-select2">
                            <option value="0">Pilih Akun</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="test" class="required form-label">Saldo Jurnal Awal</label>
                        <input type="text" class="form-control form-control-solid" name="initial_payment"
                               :value="editVal.initial_payment">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Save'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
