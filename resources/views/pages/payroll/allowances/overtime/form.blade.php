<div class="modal fade" tabindex="-1" id="modal-overtime">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Lembur</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>

            <form id="form-overtime" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Karyawan</label>
                        <select name="user_id" id="selected-user" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#modal-overtime">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Jumlah Lembur (Dalam Jam)</label>
                        <input type="number" id="hours" name="hours" class="form-control form-control-solid"
                               placeholder="Jumlah Lembur" :value="editVal.hours"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Bukti</label>
                        <input type="file" class="form-control form-control-solid" name="file" id="file"
                               accept="application/pdf">
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alasan Lembur</label>
                        <textarea class="form-control form-control-solid" name="reason"
                                  data-kt-autosize="true" x-text="editVal?.reason"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
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
