<div class="modal fade" tabindex="-1" id="modal-sp">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form SP</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="bi bi-x-circle fs-2"></i>
                    </span>
                </div>
            </div>

            <form id="form-sp" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center flex-equal fw-row me-4 order-2"
                                 data-bs-toggle="tooltip" data-bs-trigger="hover">
                                <div class="fs-6 fw-bolder text-gray-700 text-nowrap">Tanggal :</div>
                                <div class="position-relative d-flex align-items-center w-150px">
                                    <input type="date" class="form-control form-control-white fw-bolder pe-5"
                                           placeholder="Tanggal" name="sp_date" id="sp_date"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="row gx-10 mb-5">
                        <div class="col-lg-6">
                            <div class="form-group row mb-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">Tipe SP</label>
                                <div class="col-lg-11 fv-row">
                                    <select name="sp_type" class="form-select form-select-solid account-select2"
                                            data-placeholder="Select an option">
                                        <option value="0" selected>Pilih</option>
                                        <option value="SP-1">SP-1</option>
                                        <option value="SP-2">SP-2</option>
                                        <option value="SP-3">SP-3</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-0">
                                <label class="form-label fs-6 fw-bolder text-gray-700 required">Alasan SP</label>
                                <input class="form-control form-control-solid" type="text" name="reason"
                                       placeholder="Alasan"/>
                            </div>
                        </div>
                    </div>
                    <div class="mb-10">
                        <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                        <textarea name="description" id="description" class="form-control form-control-solid"
                                  rows="3"
                                  placeholder="Thanks for your business"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Save'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
