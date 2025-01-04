<div class="modal fade" tabindex="-1" id="modal-item-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Master Barang</h5>
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

            <form id="form-item-create" @submit.prevent="saveItem()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Barang"/>
                    </div>
                    <div class="mb-10">
                        <label for="category_id" class="required form-label">Kategori</label>
                        <select name="category_id" id="category_id"
                                class="form-select form-select-solid item-categories-select2"
                                data-dropdown-parent="#modal-item-create">
                            <option></option>
                        </select>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center form-check form-switch form-check-custom form-check-solid">
                        <label class="form-label" for="flexSwitchDefault" style="cursor: pointer">
                            Serial Number sudah tertera di barang (Klik jika ya).
                        </label>
                        <input class="form-check-input" type="checkbox" name="need_sn" id="flexSwitchDefault"/>

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
