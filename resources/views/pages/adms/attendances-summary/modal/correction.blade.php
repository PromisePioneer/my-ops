<div class="modal fade" tabindex="-1" id="modal-attendance-correction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Koreksi Absen</h5>
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

            <form id="form-attendance-correction" @submit.prevent="saveCorrection(correctionVal?.date ?? correctionVal)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid date"
                               placeholder="Tanggal" :value="correctionVal?.date ?? correctionVal"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Clock In</label>
                        <input type="time" id="clock_in" name="clock_in" class="form-control form-control-solid"
                               placeholder="Nama Cabang" :value="correctionVal?.clock_in"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Clock Out</label>
                        <input type="time" id="clock_out" name="clock_out" class="form-control form-control-solid"
                               placeholder="Nama Cabang" :value="correctionVal?.clock_out"/>
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
