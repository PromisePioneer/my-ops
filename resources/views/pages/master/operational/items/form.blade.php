<div class="modal fade" tabindex="-1" id="modal-item">
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

            <form id="form-item" @submit.prevent="saveItem(editVal?.id ?? null)">
                <div class="modal-body">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch form-check-custom form-check-solid me-10"
                                 x-model="itemMustHaveCode">
                                <input class="form-check-input" type="checkbox" :checked="editVal?.must_have_code === 1"
                                       id="mustHaveCode"
                                       name="must_have_code"/>
                                <label class="form-check-label" for="mustHaveCode">
                                    Wajib Memiliki Kode
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="isCodeListed"
                                       name="is_code_listed" :checked="editVal?.is_code_listed === 1"/>
                                <label class="form-check-label" for="isCodeListed">
                                    Kode Bawaan
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-10">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                       placeholder="Nama Barang" :value="editVal?.name"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="form-label">Kode (Jika Barang memiliki kode)</label>
                                <input type="text" id="code" name="code" class="form-control form-control-solid"
                                       placeholder="Kode Barang" :value="editVal?.code"/>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="type" class="required form-label">Tipe</label>
                                <select name="type"
                                        class="form-select form-select-solid" id="type" x-model="isAset">
                                    <option value="">Pilih Tipe Barang</option>
                                    <option value="JUAL" :selected="isAset === 'JUAL'">Jual</option>
                                    <option value="ASET" :selected="isAset === 'ASET'">Aset</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="isAset === 'ASET'" x-transition x-cloak>
                            <div class="mb-10">
                                <label for="tangible_assets_type" class="required form-label">Kelompok Harta
                                    Berwujud</label>
                                <select name="tangible_assets_type" id="tangible_assets_type"
                                        class="form-select form-select-solid"
                                        data-dropdown-parent="#modal-item" x-model="tangibleAsset">
                                    <option value="">Pilih Kelompok</option>
                                    <option value="Bangunan" :selected="tangibleAsset === 'Bangunan'">Bangunan</option>
                                    <option value="Bukan Bangunan" :selected="tangibleAsset === 'Bukan Bangunan'">
                                        Bukan Bangunan
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6" x-show="tangibleAsset === 'Bangunan'
                        &&
                        isAset === 'ASET'" x-cloak x-transition>
                            <div class="mb-10">
                                <label for="building_type" class="required form-label">Tipe Bangunan</label>
                                <select name="building_type" id="building_type"
                                        class="form-select form-select-solid"
                                        data-dropdown-parent="#modal-item">
                                    <option value="">Pilih Tipe Bangunan</option>
                                    <option value="Permanen"
                                            :selected="editVal?.building_type === 'Permanen'"
                                    >
                                        Permanen
                                    </option>
                                    <option value="Tidak Permanen"
                                            :selected="editVal?.building_type === 'Permanen'"
                                    >
                                        Tidak Permanen
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="unit_type_id" class="required form-label">Satuan</label>
                                <select name="unit_type_id" id="selected-unit-type"
                                        class="form-select form-select-solid unit-types-select2"
                                        data-dropdown-parent="#modal-item">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="tangibleAsset === 'Bukan Bangunan'" x-transition x-cloak>
                            <label for="category_id" class="required form-label">Kategori</label>
                            <select name="category_id" id="selected-item-category"
                                    class="form-select form-select-solid item-category-select2"
                                    data-dropdown-parent="#modal-item">
                                <option></option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6" x-show="isAset === 'ASET'" x-transition x-cloak>
                            <label for="asset_account_id" class="required form-label">
                                Akun Aset (Jika Masuk Aset)
                            </label>
                            <select name="asset_account_id" id="selected-asset-account"
                                    class="form-select form-select-solid asset-accounts-select2"
                                    data-dropdown-parent="#modal-item">
                                <option></option>
                            </select>
                        </div>
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
