<div class="modal fade" tabindex="-1" id="modal-ops-manager-approval">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kabel FO</h5>
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

            <form id="form-ops-manager-approval" @submit.prevent="approvedByOperationalManager()">
                <div class="modal-body">
                    <div class="row mb-10">
                        <label for="name" class="required form-label">Approval</label>
                        <select class="form-select form-select-solid" name="operational_manager_approval">
                            <option value="Diterima">Diterima</option>
                            <option value="Revisi">Revisi</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>


                    <div class="row mb-10">
                        <label for="name" class="required form-label">Alasan</label>
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
