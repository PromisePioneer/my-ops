<div class="modal fade" tabindex="-1" id="edit-job-experience-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Riwayat Pekerjaan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="edit-job-experience-form" @submit.prevent="jobExperienceUpdate(jobExperiencesVal.id)">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="company_name" class="required form-label">Nama Perusahaan</label>
                            <input type="text" id="company_name" name="company_name"
                                   class="form-control form-control-solid"
                                   placeholder="Nama Perusahaan" :value="jobExperiencesVal.company_name"/>
                        </div>
                        <div class="col-md-6">
                            <label for="position" class="required form-label">Jabatan</label>
                            <input type="text" id="position" name="position"
                                   class="form-control form-control-solid" placeholder="Jabatan"
                                   :value="jobExperiencesVal.position"/>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="required form-label">Tanggal Masuk</label>
                            <input type="date" id="start_date" name="start_date"
                                   class="form-control form-control-solid" :value="jobExperiencesVal.start_date"/>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="required form-label">Tanggal Keluar</label>
                            <input type="date" id="end_date" name="end_date" class="form-control form-control-solid"
                                   :value="jobExperiencesVal.end_date"/>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="responsibility" class="required form-label">Tanggung jawab</label>
                        <textarea type="text" id="responsibilities" name="responsibilities"
                                  class="form-control form-control-solid"
                                  placeholder="Tanggung Jawab" data-kt-autosize="true"
                                  x-text="jobExperiencesVal.responsibilities"></textarea>
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
