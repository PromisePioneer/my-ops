<div class="modal fade" tabindex="-1" id="modal-get-attendances-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Import Data Absen</h5>
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

            <p class="text-danger ps-md-5">*Pastikan SN Mesin sudah terdaftar, dan tanggal awal dan akhir sesuai</p>
            <div class="modal-body">
                <div class="mb-10">
                    <label for="name" class="required form-label">SN Mesin</label>
                    <select name="serial_number" id="serial_number"
                            class="form-select form-select-solid devices-select2"
                            data-dropdown-parent="#modal-get-attendances-data">
                        <option></option>
                    </select>
                </div>

                <div class="mb-10">
                    <label for="name" class="required form-label">Tanggal Awal</label>
                    <input type="datetime-local" id="start_date" name="start_date"
                           class="form-control form-control-solid"
                           placeholder="Tanggal awal"/>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Tanggal akhir</label>
                    <input type="datetime-local" id="end_date" name="end_date"
                           class="form-control form-control-solid"
                           placeholder="Tanggal akhir"/>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                    <i class="ki-duotone ki-click fs-2" @click="getAttendanceData()">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                    <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
