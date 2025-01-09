<div class="modal fade" tabindex="-1" id="modal-item-edit">
    <div class="modal-dialog">
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

            <form id="form-item-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Barang</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Barang" :value="editVal.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kategori</label>
                        <select name="category_id" id="selectedCategory"
                                class="form-select form-select-solid item-categories-select2" data-dropdown-parent="#modal-item-edit">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="category_id" class="required form-label">Satuan</label>
                        <select name="unit_type_id" id="selectedUnitType"
                                class="form-select form-select-solid unit-types-select2"
                                data-dropdown-parent="#modal-item-edit">
                            <option></option>
                        </select>
                    </div>
                    <template x-if="snPerPO === false">
                        <div
                            class="d-flex justify-content-between align-items-center form-check form-switch form-check-custom form-check-solid mb-4">
                            <label class="form-label" for="needSN" style="cursor: pointer">
                                Serial Number sudah tertera di barang (Klik jika ya).
                            </label>
                            <input class="form-check-input" x-model="needSN"
                                   :checked="editVal.already_has_sn_on_item === 1"
                                   type="checkbox"
                                   name="already_has_sn_on_item"
                                   id="needSN"/>

                        </div>
                    </template>
                    <template x-if="needSN === false">
                        <div
                            class="d-flex justify-content-between align-items-center form-check form-switch form-check-custom form-check-solid">
                            <label class="form-label" for="snPerPO" style="cursor: pointer">
                                Tidak perlu Serial Number (Klik jika iya).
                            </label>
                            <input class="form-check-input" x-model="snPerPO" :checked="editVal.need_sn === 1"
                                   type="checkbox" name="need_sn"
                                   id="snPerPO"/>
                        </div>
                    </template>
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
