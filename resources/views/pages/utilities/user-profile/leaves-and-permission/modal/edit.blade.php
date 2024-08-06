<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengajuan Cuti</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>
            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control form-control-solid"
                               placeholder="Tanggal Mulai" :value="editVal.start_date"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-solid"
                               placeholder="Tanggal Selesai" :value="editVal.end_date"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Status Cuti</label>
                        <select name="leaves_status" id="leaves_status" class="form-select form-select-solid"
                                x-model="sickLetter">
                            <option value="0" selected>Pilih</option>
                            <option value="Sakit" :selected="editVal.leaves_status === 'Sakit'">Sakit</option>
                            <option value="Cuti" :selected="editVal.leaves_status === 'Cuti'">Cuti</option>
                            <option value="Izin" :selected="editVal.leaves_status === 'Izin'">Izin</option>
                        </select>
                    </div>
                    <div class="mb-10" x-show="sickLetter === 'Sakit'" x-transition>
                        <label for="name" class="required form-label">Surat Ketarangan Dokter</label>
                        <input type="file" id="end_date" :name="`${sickLetter === 'Sakit' ? 'sick_letter' : ''}`"
                               class="form-control form-control-solid"
                               accept=".jpg,.png,.jpeg,.pdf"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alasan Cuti</label>
                        <textarea id="reason" name="reason" class="form-control form-control-solid"
                                  data-kt-autosize="true" placeholder="ALasan Cuti" x-text="editVal.reason"></textarea>
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
