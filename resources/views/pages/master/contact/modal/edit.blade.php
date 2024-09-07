<div class="modal fade" tabindex="-1" id="contact-edit">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Contact</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <form id="contactFormEdit" @submit.prevent="update(editVal.id)">
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                            <span class="required">Nama Lengkap</span>
                        </label>
                        <input type="text" class="form-control form-control-solid" placeholder="Nama Lengkap"
                               name="full_name" :value="editVal.full_name"/>
                    </div>
                    <div class="row g-9 mb-8">
                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Nama Perusahaan</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Nama Perusahaan"
                                   name="company_name" :value="editVal.company_name"/>
                        </div>

                        <div class="col-md-6 fv-row">
                            <label class="required fs-6 fw-bold mb-2">Email</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Email"
                                   name="email" :value="editVal.email"/>
                        </div>
                    </div>
                    <div class="d-flex flex-column mb-8">
                        <label class="fs-6 fw-bold mb-2">No. Telepon</label>
                        <input type="text" class="form-control form-control-solid" placeholder="No. Telepon"
                               name="phone_number" :value="editVal.phone_number"/>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Identitas</span>
                            </label>
                            <select name="identity_type" class="form-select form-select-solid"
                                    data-placeholder="Select an option">
                                <option value="ktp" :selected="`${editVal.identity_type === 'ktp'}`">KTP</option>
                                <option value="sim" :selected="`${editVal.identity_type === 'sim'}`">SIM</option>
                                <option value="passport" :selected="`${editVal.identity_type === 'passport'}`">
                                    PASSPORT
                                </option>
                            </select>
                        </div>
                        <div class="col-md-10">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">No. Identitas</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="No. Identitas"
                                   name="identity_number" :value="editVal.identity_number"/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">FAX</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="FAX" name="fax"
                                   :value="editVal.fax"/>
                        </div>
                        <div class="col-md-6">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">NPWP</span>
                            </label>
                            <input type="text" class="form-control form-control-solid" placeholder="No. Identitas"
                                   name="npwp" :value="editVal.fax"/>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Alamat Lengkap</span>
                            </label>
                            <textarea name="complete_address" class="form-control form-control-solid" id=""
                                      data-kt-autosize="true" :value="editVal.complete_address"></textarea>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <div class="col-md-12">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Other Info</span>
                            </label>
                            <textarea name="other_info" class="form-control form-control-solid" id=""
                                      data-kt-autosize="true" :value="editVal.other_info"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
