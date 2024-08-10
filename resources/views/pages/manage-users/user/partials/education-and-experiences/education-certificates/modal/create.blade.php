<div class="modal fade" tabindex="-1" id="education-certificate-create-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Riwayat Sertifikat Atau Pelatihan Relevan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="education-certificate-create-form" @submit.prevent="educationCertificateStore()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="organization" class="required form-label">Organisasi Penerbit</label>
                            <input type="text" id="organization" name="organization"
                                   class="form-control form-control-solid"
                                   placeholder="Organisasi Penerbit"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Nama Sertifikat</label>
                            <input type="text" id="name" name="name"
                                   class="form-control form-control-solid"
                                   placeholder="Nama Sertifikat"/>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="year" class="required form-label">Tahun</label>
                            <input type="text" id="year" name="year" maxlength="4"
                                   class="form-control form-control-solid"
                                   placeholder="Tahun"/>
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
