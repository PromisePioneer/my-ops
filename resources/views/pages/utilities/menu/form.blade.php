<div class="modal fade" tabindex="-1" id="modal-menu">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Mesin Absen</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-menu" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="branch_id" class="required form-label">Menu</label>
                            <input type="text" name="name" id="name" class="form-control form-control-solid"
                                   :value="editVal.name">
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="required form-label">Hak Akses</label>
                            <select name="permissions[]" id="selected-permission"
                                    class="form-select form-select-solid permissions-select2"
                                    data-dropdown-parent="#modal-menu" multiple>
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
