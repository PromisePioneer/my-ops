<div class="modal fade" tabindex="-1" id="health-information-update-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Data keluarga</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-health-information-update" @submit.prevent="healthInformationUpdate()">
                <div class="modal-body">
                    <label for="nik" class="required form-label">Penyakit</label>
                    <template x-for="(name, index) in diseaseData" :key="index">
                        <div class="row mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <input type="text" id="child" :name="`data[${index}][name]`"
                                       class="form-control form-control-solid"
                                       placeholder="Nama Penyakit" x-model="name.name.name"
                                       :value="name.name"/>
                                <button type="button" class="btn btn-light-danger btn-sm"
                                        @click="removeDiseaseData(index)">
                                    <i class="bi bi-trash fs-3"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" class="btn btn-link primary btn-sm" @click="addDiseaseData()">
                        Tambah Penyakit
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
