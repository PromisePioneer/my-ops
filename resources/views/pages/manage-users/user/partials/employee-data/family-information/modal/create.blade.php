<div class="modal fade" tabindex="-1" id="family-information-update-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Data keluarga</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-family-information-update" @submit.prevent="familyInformationUpdate()">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label for="nik" class="required form-label">Nama Pasangan</label>
                        <input type="text" id="partner_name" name="partner_name" class="form-control form-control-solid"
                               placeholder="Nama pasangan" :value="familyInformation.partner_name?.partner_name ?? ''"/>
                    </div>

                    <label for="nik" class="required form-label">Nama Anak</label>
                    <template x-for="(child, index) in childNameData" :key="index">
                        <div class="row mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" id="child" :name="`data[${index}][child]`"
                                       class="form-control form-control-solid"
                                       placeholder="Nama Anak" x-model="child.name"
                                       :value="child.name"/>
                                <button type="button" class="btn btn-light-danger btn-sm"
                                        @click="removeChildData(index)">
                                    <i class="bi bi-trash fs-3"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" class="btn btn-link primary btn-sm" @click="addChildNameData()">
                        Tambah Anak
                    </button>
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
