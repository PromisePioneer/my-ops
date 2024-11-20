<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Jadwal Karyawan</h5>
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
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid date"
                               placeholder="Tanggal" :value="schedulesValue?.date"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Jam Kerja</label>
                        <select class="form-select form-select-solid work-time-select2" name="work_time_id"
                                id="selectedWorkTime" data-dropdown-parent="#modal-create">
                            <option></option>
                        </select>
                    </div>


                    <input type="hidden" name="employee_id" id="employee_id" :value="schedulesValue?.employee_id">

                    <div class="mb-10">
                        <label for="name" class="required form-label">Status</label>
                        <select class="form-select form-select-solid" name="status" id="status">
                            <option selected>Pilih</option>
                            <option value="H" :selected="schedulesValue?.status === 'H' ? 'selected' : ''">Hadir
                            </option>
                            <option value="L" :selected="schedulesValue?.status === 'L' ? 'selected' : ''">Libur
                            </option>
                        </select>
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
