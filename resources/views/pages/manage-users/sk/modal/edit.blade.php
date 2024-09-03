<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form SK</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Pilih Karyawan</label>
                            <select name="user_id" id="selectedUser" class="form-select form-select-solid users-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option>Pilih</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">SK</label>
                            <select name="sk_type" id="sk_type" class="form-select form-select-solid">
                                <option value="0">Pilih</option>
                                <option value="Promosi" :selected="editVal.sk_type === 'Promosi'">Promosi</option>
                                <option value="Demosi" :selected="editVal.sk_type === 'Demosi'">Demosi</option>
                                <option value="Mutasi" :selected="editVal.sk_type === 'Mutasi'">Mutasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Tanggal</label>
                            <input type="date" name="date" id="date" class="form-control form-control-solid"
                                   :value="editVal.date">
                        </div>
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Cabang</label>
                            <select name="branch_id" id="selectedBranch"
                                    class="form-select form-select-solid branch-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option>Pilih Cabang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <label for="user_id" class="required form-label">Jabatan Baru</label>
                            <select name="role_id" id="selectedRole"
                                    class="form-select form-select-solid roles-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option>Pilih</option>
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
