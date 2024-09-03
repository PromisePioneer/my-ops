<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form SK</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Pilih Karyawan</label>
                            <select name="user_id" id="user_id" class="form-select form-select-solid users-select2"
                                    data-dropdown-parent="#modal-create">
                                <option>Pilih</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">SK</label>
                            <select name="sk_type" id="sk_type" class="form-select form-select-solid">
                                <option value="0">Pilih</option>
                                <option value="Promosi">Promosi</option>
                                <option value="Demosi">Demosi</option>
                                <option value="Mutasi">Mutasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control form-control-solid">
                        </div>
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Cabang</label>
                            <select name="branch_id" id="branch_id"
                                    class="form-select form-select-solid branch-select2"
                                    data-dropdown-parent="#modal-create">
                                <option>Pilih Cabang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Jabatan Baru</label>
                            <select name="role_id" id="role_id"
                                    class="form-select form-select-solid roles-select2"
                                    data-dropdown-parent="#modal-create">
                                <option>Pilih Jabatan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"
                            :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
