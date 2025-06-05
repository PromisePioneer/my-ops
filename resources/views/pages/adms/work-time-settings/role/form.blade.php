<div class="modal fade" tabindex="-1" id="modal-role-work-time">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengaturan Jam Kerja Kantor Cabang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-role-work-time" @submit.prevent="save(editVal.id)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Jabatan</label>
                                <x-select2.index name="role_id"
                                                 id="selected-role"
                                                 class="form-select form-select-solid"
                                                 elementSelector="roles-select2"
                                                 parentElementIfExist="#modal-role-work-time"
                                />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Jam Kerja</label>
                                <x-select2.index name="work_time_id"
                                                 id="selected-role-default-work-time"
                                                 class="form-select form-select-solid"
                                                 elementSelector="work-times-select2"
                                                 parentElementIfExist="#modal-role-work-time"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                        <x-icons.save/>
                        <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
