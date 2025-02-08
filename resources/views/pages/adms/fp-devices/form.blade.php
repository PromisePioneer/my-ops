<div class="modal fade" tabindex="-1" id="modal-fp-device">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Mesin Absen</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-fp-device" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="branch_id" class="required form-label">Cabang</label>
                        <select name="branch_id" id="selected-branch"
                                class="form-select form-select-solid branch-select2"
                                data-dropdown-parent="#modal-create">
                            <option value="0">Pilih</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Kantor/Pop" :value="editVal?.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="serial_number" class="required form-label">Serial Number</label>
                        <input type="text" id="ip_address" name="ip_address"
                               class="form-control form-control-solid"
                               placeholder="Nama" :value="editVal?.ip_address"/>
                    </div>
                    <div class="mb-10">
                        <label for="serial_number" class="required form-label">Serial Number</label>
                        <input type="text" id="serial_number" name="serial_number"
                               class="form-control form-control-solid"
                               placeholder="Nama" :value="editVal?.serial_number"/>
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
