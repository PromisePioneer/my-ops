<div class="modal fade" id="modal-create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">Tambah Jabatan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)"
                                  fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black"></rect>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 my-7">
                <form id="form-create" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                      @submit.prevent="save()">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                         data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                         data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header"
                         data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px"
                         style="max-height: 627px;">
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="fs-5 fw-bolder form-label mb-2">
                                    <span class="required">Nama Jabatan</span>
                                </label>
                                <input class="form-control form-control-solid" name="name" :value="editVal.name">
                            </div>
                            <div class="col-md-6">
                                <label class="fs-5 fw-bolder form-label mb-2">
                                    <span class="required">Department</span>
                                </label>
                                <select class="form-select form-select-solid departments-select2" name="department_id"
                                        id="department_id" data-dropdown-parent="#modal-create">
                                    <option value="0">Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="fv-row">
                            <label class="fs-5 fw-bolder form-label mb-2">Hak Akses Menu</label>
                            <div class="row justify-content-center align-items-center">
                                <template x-for="permission in permissionData">
                                    <div class="col-md-6">
                                        <div class="form-check mb-4">
                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                <input class="form-check-input" type="checkbox"
                                                       :value="permission.name"
                                                       name="permission[]"
                                                       multiple
                                                >
                                                <span class="form-check-label text-capitalize text-gray-600 fw-bold"
                                                      x-text="permission.name"></span>
                                            </label>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3 btn-sm">
                            Discard
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                        </button>
                    </div>
                    <div></div>
                </form>
            </div>
        </div>
    </div>
</div>