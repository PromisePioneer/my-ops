<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengeluaran</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Deskripsi</label>
                        <input type="text" id="description" name="description" class="form-control form-control-solid"
                               placeholder="Deskripsi"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Akun Debit</label>
                        <select name="debit_account_id"
                                class="form-select form-select-solid debit-account-select2"
                                data-dropdown-parent="#modal-create">
                            <option value="0">Pilih</option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Akun Kredit</label>
                        <select name="credit_account_id"
                                class="form-select form-select-solid credit-account-select2"
                                data-dropdown-parent="#modal-create">
                            <option value="0">Pilih</option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Nominal</label>
                        <input type="number" class="form-control form-control-solid" name="amount"
                               placeholder="Nominal">
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">File</label>
                        <input type="file" class="form-control form-control-solid" name="file"
                               placeholder="File" accept=".jpg,.png,.jpeg"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
