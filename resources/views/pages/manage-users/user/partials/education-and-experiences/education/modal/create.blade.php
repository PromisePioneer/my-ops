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
                            <input type="text" id="level" name="level" class="form-control form-control-solid"
                                   placeholder="Tingkat pendidikan" :value="education.level ?? ''"/>
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
