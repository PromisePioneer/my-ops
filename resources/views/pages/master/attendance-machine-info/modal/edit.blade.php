<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cabang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Cabang</label>
                        <select name="branch_id" id="selectedBranch"
                                class="form-select form-select-solid branch-select2">
                            <option value="0" selected>Pilih</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Versi</label>
                        <input type="text" id="version" name="version" class="form-control form-control-solid"
                               placeholder="Versi Mesin" :value="editVal.version"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">IP Address</label>
                        <input type="text" id="ip_address" name="ip_address" class="form-control form-control-solid"
                               placeholder="IP Address" :value="editVal.ip_address"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Port</label>
                        <input type="text" id="port" name="port" class="form-control form-control-solid"
                               placeholder="Port" :value="editVal.ip_address"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Key</label>
                        <input type="text" id="key" name="key" class="form-control form-control-solid"
                               placeholder="Key" :value="editVal.key"/>
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
