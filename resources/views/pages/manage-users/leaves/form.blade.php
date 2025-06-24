<div class="modal fade" tabindex="-1" id="modal-leaves">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengajuan Cuti</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-leaves" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Pilih Karyawan</label>
                        <select name="user_id" id="selected-user" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#modal-leaves">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Status Cuti</label>
                        <select name="leaves_status" id="leaves_status" class="form-select form-select-solid"
                                x-model="leavesStatus" @change="ifNotImportantLeave()">
                            <option value="" selected>Pilih</option>
                            <option value="Sakit" :selected="editVal?.leaves_status === 'Sakit'">Sakit</option>
                            <option value="Cuti" :selected="editVal?.leaves_status === 'Cuti'">Cuti</option>
                            <option value="Izin" :selected="editVal?.leaves_status === 'Izin'">Izin</option>
                            <option value="Cuti Penting" :selected="editVal?.leaves_status === 'Cuti Penting'">Cuti
                                Penting
                            </option>
                            <option value="Lembur" :selected="editVal?.leaves_status === 'Lembur'">Lembur</option>
                        </select>
                    </div>
                    <div class="mb-10"
                         x-show="importantLeaveType !== 'Mendapat Musibah'
                    && importantLeaveType !== 'Memenuhi Panggilan Instansi Pemerintah'"
                         x-transition x-cloak>
                        <label for="name" class="required form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date"
                               :name="`${importantLeaveType !== 'Mendapat Musibah' &&
                               importantLeaveType !== 'Memenuhi Panggilan Instansi Pemerintah'
                               ? 'start_date' : ''}`"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Mulai" :value="editVal?.start_date"/>
                    </div>
                    <div class="mb-10" x-show="leavesStatus !== 'Cuti Penting' && importantLeaveType !== 'Mendapat Musibah'
                    && importantLeaveType !== 'Memenuhi Panggilan Instansi Pemerintah'">
                        <label for="name" class="required form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date"
                               :name="`${leavesStatus !== 'Cuti Penting' && importantLeaveType !== 'Mendapat Musibah'
                                    && importantLeaveType !== 'Memenuhi Panggilan Instansi Pemerintah' ? 'end_date' : ''}`"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Selesai" :value="editVal?.end_date"/>
                    </div>
                    <div class="mb-10" x-show="leavesStatus === 'Cuti'">
                        <label for="name" class="required form-label">Sisa Cuti</label>
                        <input type="text" class="form-control form-control-solid"
                               readonly :value="leavesLeft"/>
                    </div>

                    <div class="mb-10" x-show="leavesStatus === 'Cuti Penting'" x-transition x-cloak>
                        <label for="name" class="required form-label">Pilih Kategori Cuti Penting</label>
                        <select :name="`${leavesStatus === 'Cuti Penting' ? 'important_leaves' : ''}`"
                                id="important_leaves" class="form-select form-select-solid"
                                x-model="importantLeaveType">
                            <option value="" selected>Pilih</option>
                            <option value="Menikah" :selected="editVal?.important_leaves === 'Menikah'">Menikah</option>
                            <option value="Menikahkan Anak" :selected="editVal?.important_leaves === 'Menikahkan Anak'">
                                Menikahkan anak
                            </option>
                            <option value="Mengkhitankan Anak"
                                    :selected="editVal?.important_leaves === 'Mengkhitankan Anak'">Mengkhitankan anak
                            </option>
                            <option value="Membaptis Anak" :selected="editVal?.important_leaves === 'Membaptis Anak'">
                                Membaptis anak
                            </option>
                            <option value="Istri Melahirkan"
                                    :selected="editVal?.important_leaves === 'Istri Melahirkan'">
                                Istri melahirkan atau keguguran kandungan
                            </option>
                            <option value="Anggota Keluarga Meninggal Dunia"
                                    :selected="editVal?.important_leaves === 'Anggota Keluarga Meninggal Dunia'">
                                Suami / istri / orang tua / mertua / anak / menantu meninggal dunia
                            </option>
                            <option value="Anggota Keluarga Dalam Satu Rumah Meninggal Dunia"
                                    :selected="editVal?.important_leaves === 'Anggota Keluarga Dalam Satu Rumah Meninggal Dunia'">
                                Anggota keluarga dalam satu rumah meninggal dunia
                            </option>
                            <option value="Pemakaman Saudara Kandung"
                                    :selected="editVal?.important_leaves === 'Pemakaman Saudara Kandung'">
                                Pemakaman Saudara Kandung
                            </option>
                            <option value="Memenuhi Panggilan Instansi Pemerintah"
                                    :selected="editVal?.important_leaves === 'Memenuhi Panggilan Instansi Pemerintah'">
                                Memenuhi Panggilan Instansi Pemerintah
                            </option>
                            <option value="Mendapat Musibah"
                                    :selected="editVal?.important_leaves === 'Mendapat Musibah'">
                                Mendapat Musibah (Banjir, Kebakaran dll)
                            </option>
                        </select>
                    </div>
                    <div class="mb-10"
                         x-show="leavesStatus === 'Sakit' || importantLeaveType === 'Memenuhi Panggilan Instansi Pemerintah'"
                         x-transition x-cloak>
                        <label for="sick_letter" class="required form-label" x-text="leavesStatus === 'Sakit' ? 'Surat Ketarangan Dokter'
                        : 'Surat Resmi Dari Instansi' "></label>
                        <input type="file" id="file"
                               :name="`${leavesStatus === 'Sakit' || importantLeaveType === 'Memenuhi Panggilan Instansi Pemerintah' ? 'attachment' : ''}`"
                               class="form-control form-control-solid"
                               accept=".jpg,.png,.jpeg"/>
                    </div>
                    <div class="mb-10" x-show="leavesStatus !== 'Cuti Penting'" x-transition x-cloak>
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
