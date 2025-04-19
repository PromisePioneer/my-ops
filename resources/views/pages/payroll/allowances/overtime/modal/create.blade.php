<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tunjangan Transportasi</h5>
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

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Karyawan</label>
                        <select name="user_id" class="form-control form-control-solid users-select2"
                                data-dropdown-parent="#modal-create">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Jumlah Lembur (Dalam Jam)</label>
                        <input type="number" id="hours" name="hours" class="form-control form-control-solid"
                               placeholder="Jumlah Lembur"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alasan Lembur</label>
                        <textarea class="form-control form-control-solid" name="reason"
                                  data-kt-autosize="true"></textarea>
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
