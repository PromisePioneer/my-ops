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
                                x-model="leavesStatus">
                            <option value="0" selected>Pilih</option>
                            <option value="Sakit" :selected="editVal?.leaves_status === 'Sakit'">Sakit</option>
                            <option value="Cuti" :selected="editVal?.leaves_status === 'Cuti'">Cuti</option>
                            <option value="Izin" :selected="editVal?.leaves_status === 'Izin'">Izin</option>
                            <option value="Cuti Penting" :selected="editVal?.leaves_status === 'Cuti Penting'">Cuti
                                Penting
                            </option>
                            <option value="Lembur" :selected="editVal?.leaves_status === 'Lembur'">Lembur</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal Mulai" :value="editVal?.start_date"/>
                    </div>
                    <div class="mb-10" x-show="leavesStatus !== 'Cuti Penting'">
                        <label for="name" class="required form-label">Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-solid date"
                               placeholder="Tanggal Selesai" :value="editVal?.end_date"/>
                    </div>
                    <div class="mb-10" x-show="leavesStatus === 'Cuti'">
                        <label for="name" class="required form-label">Sisa Cuti</label>
                        <input type="text" class="form-control form-control-solid"
                               readonly :value="leavesLeft"/>
                    </div>

                    <div class="mb-10" x-show="leavesStatus === 'Cuti Penting'" x-transition x-cloak>
                        <label for="name" class="required form-label">Pilih Kategori Cuti Penting</label>
                        <select name="important_leaves" id="important_leaves" class="form-select form-select-solid">
                            <option value="0" selected>Pilih</option>
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
                        </select>
                    </div>
                    <div class="mb-10" x-show="leavesStatus === 'Sakit'" x-transition x-cloak>
                        <label for="name" class="required form-label">Surat Ketarangan Dokter</label>
                        <input type="file" id="end_date" :name="`${leavesStatus === 'Sakit' ? 'sick_letter' : ''}`"
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
