<div class="modal fade" tabindex="-1" id="modal-confirmation">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Konfirmasi Transaksi</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-confirmation" @submit.prevent="saveConfirmation(editVal?.id)">
                <div class="modal-body">
                    <div class="row mb-10">
                        <label class="form-label required">Status Konfirmasi</label>
                        <select name="confirmation_status" id="confirmation_status"
                                x-model="selectedConfirmationStatus" class="form-select form-select-solid">
                            <option selected>Pilih</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Revisi">Revisi</option>
                        </select>
                    </div>


                    <div class="row mb-10">
                        <label class="form-label required">Alasan</label>
                        <textarea name="reason" id="reason" class="form-control form-control-solid"
                                  data-kt-autosize="true"></textarea>
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
