<div class="modal fade" tabindex="-1" id="education-update-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Riwayat Pendidikan Terakhir</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-education-update" @submit.prevent="educationUpdate()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="level" class="required form-label">Tingkat Pendidikan</label>
                            <select name="level" id="level" class="form-select form-select-solid">
                                <option selected>Pilih</option>
                                <option value="SD" :selected="education.level === 'SD'">SD</option>
                                <option value="SMP" :selected="education.level === 'SMP'">SMP</option>
                                <option value="SMA" :selected="education.level === 'SMA'">SMA</option>
                                <option value="D1" :selected="education.level === 'D1'">D1</option>
                                <option value="D2" :selected="education.level === 'D2'">D2</option>
                                <option value="D3" :selected="education.level === 'D3'">D3</option>
                                <option value="D4" :selected="education.level === 'D4'">D4</option>
                                <option value="S1" :selected="education.level === 'S1'">S1</option>
                                <option value="S2" :selected="education.level === 'S2'">S2</option>
                                <option value="S3" :selected="education.level === 'S3'">S3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="institution" class="required form-label">Institusi Pendidikan</label>
                            <input type="text" id="institution" name="institution"
                                   class="form-control form-control-solid"
                                   placeholder="Institusi" :value="education.institution ?? ''"/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="major" class="required form-label">Jurusan</label>
                            <input type="text" id="major" name="major" class="form-control form-control-solid"
                                   placeholder="Tingkat pendidikan" :value="education.major ?? ''"/>
                        </div>
                        <div class="col-md-6">
                            <label for="graduation_year" class="required form-label">Tahun Lulus</label>
                            <input type="number" maxlength="4" id="graduation_year" name="graduation_year"
                                   class="form-control form-control-solid"
                                   placeholder="Tahun lulus" :value="education.graduation_year ?? ''"/>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="certificate_of_graduation" class="required form-label">Ijazah</label>
                            <input type="file" id="certificate_of_graduation" name="certificate_of_graduation"
                                   class="form-control form-control-solid" accept="application/pdf"/>
                        </div>
                        <div class="col-md-6">
                            <label for="gpa" class="required form-label">IPK / Nilai rata - rata</label>
                            <input type="text" id="gpa" name="gpa" class="form-control form-control-solid"
                                   accept="application/pdf" :value="education.gpa ?? ''"/>
                        </div>
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
