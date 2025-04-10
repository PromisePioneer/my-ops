<div class="modal fade" tabindex="-1" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengajuan Cuti</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form" @submit.prevent="save(editVal.id ?? null)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Mulai" :value="editVal?.start_date"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Selesai" :value="editVal?.end_date"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Status Cuti</label>
                        <select name="leaves_status" id="leaves_status" class="form-select form-select-solid"
                                x-model="sickLetter">
                            <option value="0" selected>Pilih</option>
                            <option value="Sakit" :selected="editVal.leaves_status === 'Sakit'">Sakit</option>
                            <option value="Cuti" :selected="editVal.leaves_status === 'Cuti'">Cuti tahunan</option>
                            <option value="Cuti Menikah" :selected="editVal.leaves_status === 'Izin'">
                                Cuti menikah (3 Hari)
                            </option>
                            <option value="Cuti Menikahkan Anak" :selected="editVal.leaves_status === 'Izin'">
                                Cuti menikahkan anak (2 Hari)
                            </option>
                            <option value="Cuti Mengkhitankan Anak" :selected="editVal.leaves_status === 'Izin'">
                                Cuti mengkhitankan anak (2 Hari)
                            </option>
                            <option value="Cuti Membaptis Anak" :selected="editVal.leaves_status === 'Izin'">
                                Cuti membaptis anak (2 Hari)
                            </option>
                            <option value="Cuti Istri Melahirkan" :selected="editVal.leaves_status === 'Izin'">
                                Cuti istri melahirkan atau keguguran kandungan (2 Hari)
                            </option>
                            <option value="Cuti Keluarga Meninggal Dunia" :selected="editVal.leaves_status === 'Izin'">
                                Cuti Suami/istri, orang tua/mertua atau anak atau menantu meninggal dunia (2 Hari)
                            </option>
                            <option value="Cuti Anggota Keluarga Satu Rumah Meninggal Dunia"
                                    :selected="editVal.leaves_status === 'Izin'">
                                Cuti anggota keluarga dalam satu rumah meninggal dunia (1 Hari)
                            </option>

                        </select>
                    </div>
                    <div class="mb-10" x-show="sickLetter === 'Sakit'" x-transition>
                        <label for="name" class="required form-label">Surat Ketarangan Dokter</label>
                        <input type="file" id="end_date" :name="`${sickLetter === 'Sakit' ? 'sick_letter' : ''}`"
                               class="form-control form-control-solid"
                               accept=".jpg,.png,.jpeg"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alasan</label>
                        <textarea type="date" id="reason" name="reason" class="form-control form-control-solid"
                                  data-kt-autosize="true" placeholder="ALasan" x-text="editVal?.reason"></textarea>
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
