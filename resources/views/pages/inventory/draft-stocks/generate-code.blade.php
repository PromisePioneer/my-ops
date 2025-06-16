<div class="modal fade" tabindex="-1" id="modal-generate-code">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Kode</h5>
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

            <form id="form-generate-code" @submit.prevent="generateItemCatalogCode(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode / SN</label>
                        <input type="text" id="code" name="code" :readonly="autoGenerateCode !== ''" class="form-control form-control-solid"
                               placeholder="Kode" :value="autoGenerateCode"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kondisi</label>
                        <select name="condition" id="condition" class="form-select form-select-solid">
                            <option value="Baik">Baik</option>
                            <option value="Rusak" >Rusak</option>
                        </select>
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
