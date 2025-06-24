<div class="modal fade" tabindex="-1" id="modal-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Konfirmasi Cuti</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-confirm" @submit.prevent="confirm(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Status Konfirmasi</label>
                        <select name="confirmation_status" id="confirmation_status"
                                class="form-select form-select-solid" x-model="confirmationStatus">
                            <option value="" selected>Pilih</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-10"
                         x-show="(editVal.important_leaves === 'Mendapat Musibah' || editVal.important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && (confirmationStatus === 'Diterima')"
                         x-transition x-cloak>
                        <div class="mb-10">
                            <label for="end_date" class="required form-label">Tanggal Awal</label>
                            <input type="date" class="form-control-solid form-control date"
                                   :name="`${(editVal.important_leaves === 'Mendapat Musibah' || editVal.important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && (confirmationStatus === 'Diterima') ? 'start_date' : ''}`"
                                   id="start_date" placeholder="Tanggal Awal">
                        </div>
                        <div class="mb-10">
                            <label for="end_date" class="required form-label">Tanggal Akhir</label>
                            <input type="date"
                                   :name="`${(editVal.important_leaves === 'Mendapat Musibah' || editVal.important_leaves === 'Memenuhi Panggilan Instansi Pemerintah') && (confirmationStatus === 'Diterima') ? 'end_date' : ''}`"
                                   id="end_date"
                                   class="form-control-solid form-control date" placeholder="Tanggal Akhir">
                        </div>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alasan</label>
                        <textarea class="form-control form-control-solid" name="confirmation_reason"
                                  data-kt-autosize="true"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
