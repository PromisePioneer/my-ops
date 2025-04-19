<div class="modal fade" tabindex="-1" id="modal-allowance-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tunjangan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-allowance-create" @submit.prevent="saveAllowance()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="role_id" class="required form-label">Jabatan</label>
                        <select name="role_id[]" data-dropdown-parent="#modal-allowance-create"
                                class="form-select form-select-solid roles-select2" multiple>
                            <option></option>
                            <option value="all">all</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama"/>
                    </div>

                    <div class="mb-10">
                        <label for="amount" class="required form-label">Jumlah</label>
                        <input type="number" class="form-control form-control-solid" name="amount" id="amount"
                               data-kt-autosize="true" placeholder="Jumlah"/>
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
