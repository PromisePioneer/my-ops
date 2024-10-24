<div class="modal fade" tabindex="-1" id="modal-assign-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tetapkan Shift Karyawan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-assign-user" @submit.prevent="assignShift()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Karyawan</label>
                                <select name="user_id[]" class="form-select form-select-solid user-select2"
                                        id="selectedUserShift"
                                        multiple>
                                    <option value="0">Pilih</option>
                                </select>
                            </div>
                        </div>
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
