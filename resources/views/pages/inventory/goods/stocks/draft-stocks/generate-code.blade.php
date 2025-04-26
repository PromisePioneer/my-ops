<div class="modal fade" tabindex="-1" id="modal-generate-code">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Cabang</h5>
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

            <form id="form-generate-code" @submit.prevent="generateItemCatalogCode(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode / SN</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid"
                               :readonly="draftStock?.item?.must_have_code === 1 && draftStock?.item?.is_code_listed === 0"
                               placeholder="Kode" :value="autoGenerateCode ?? editVal?.code"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kondisi</label>
                        <select name="condition" id="condition" class="form-select form-select-solid">
                            <option value="Rusak" :selected="editVal?.condition === 'Rusak'">Rusak</option>
                            <option value="Baik" :selected="editVal?.condition === 'Baik'">Baik</option>
                            <option value="Diperbaiki" :selected="editVal?.condition === 'Diperbaiki'">Diperbaiki
                            </option>
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
