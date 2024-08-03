<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Akun</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="update()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Cabang</label>
                        <select name="branch_id" id="selectedBranch" class="form-select form-select-solid branch-select2" data-dropdown-parent="#modal-edit" >
                            <option value='0'>Pilih</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid" placeholder="Kode" :value="editVal.code"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Nama akun" :value="editVal.name"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading" x-text="buttonLoading ? 'Loading...' : 'Save'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
