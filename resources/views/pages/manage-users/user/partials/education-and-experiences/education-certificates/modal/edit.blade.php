<div class="modal fade" tabindex="-1" id="education-certificate-edit-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Riwayat Pendidikan Terakhir</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="education-certificate-edit-form"
                  @submit.prevent="educationCertificatesUpdate(educationCertificateVal.id)">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="organization" class="required form-label">Organisasi Penerbit</label>
                            <input type="text" id="organization" name="organization"
                                   class="form-control form-control-solid"
                                   placeholder="Organisasi Penerbit" :value="educationCertificateVal.organization"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Nama Sertifikat</label>
                            <input type="text" id="name" name="name"
                                   class="form-control form-control-solid"
                                   placeholder="Nama Sertifikat" :value="educationCertificateVal.name"/>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="year" class="required form-label">Masa berlaku</label>
                            <input type="text" id="year" name="year" maxlength="4"
                                   class="form-control form-control-solid"
                                   placeholder="Masa berlaku" :value="educationCertificateVal.year"/>
                        </div>
                        <div class="col-md-6">
                            <label for="file" class="required form-label">File</label>
                            <input type="file" id="file" name="file"
                                   class="form-control form-control-solid"
                                   placeholder="File"/>
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
