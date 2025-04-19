<div class="modal fade" tabindex="-1" id="modal-query-attlog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tarik Data Kehadiran</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-query-attlog" @submit.prevent="queryAttLog(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="start_date" class="required form-label">
                            Tanggal Mulai
                        </label>
                        <input type="datetime-local" id="start_date" name="start_date"
                               class="form-control form-control-solid date" placeholder="Tanggal Awal"/>
                    </div>
                    <div class="mb-10">
                        <label for="end_date" class="required form-label">
                            Tanggal Akhir
                        </label>
                        <input type="datetime-local" id="end_date" name="end_date"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Akhir"/>
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
