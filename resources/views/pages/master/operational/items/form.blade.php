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
                                 x-show="!hasSNOnItem" x-transition x-cloak
                            >
                                <input class="form-check-input" type="checkbox" x-model="itemMustHaveCode"
                                       :checked="editVal?.must_have_code === 1"
                                       id="mustHaveCode"
                                       name="must_have_code"/>
                                <label class="form-check-label" for="mustHaveCode">
                                    Wajib Memiliki Kode
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid me-10"
                                 x-show="!itemMustHaveCode" x-transition x-cloak>
                                <input class="form-check-input" type="checkbox" id="isCodeListed" x-model="hasSNOnItem"
                                       :name="`${!itemMustHaveCode ? 'is_code_listed' : ''}`"
                                       :checked="editVal?.is_code_listed === 1"/>
                                <label class="form-check-label" for="isCodeListed">
                                    Kode Bawaan
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid"
                                 x-show="tangibleAsset === 'Bukan Bangunan'"
                                 x-transition x-cloak>
                                <input class="form-check-input" type="checkbox" id="isVehicle" x-model="isVehicleAsset"
                                       name="is_vehicle"
                                       :checked="editVal?.is_vehicle === 1"/>
                                <label class="form-check-label" for="isVehicle">
                                    Aset Berupa Kendaraan
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="row mt-10 mb-10">
                        <div class="col-md-6">
                                <label for="name" class="required form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                       placeholder="Nama Barang" :value="editVal?.name"/>
                        </div>
                        <div class="col-md-6"
                             x-show="itemMustHaveCode && !hasSNOnItem" x-transition x-cloak>
                            <label for="name" class="form-label">
                                Kode (Jika Barang memiliki kode)
                            </label>
                            <input type="text" id="code"
                                   :name="`${itemMustHaveCode && !hasSNOnItem ? 'code' : ''}`"
                                   class="form-control form-control-solid"
                                       placeholder="Kode Barang" :value="editVal?.code"/>
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-md-6">
                                <label for="type" class="required form-label">Tipe</label>
                                <select name="type"
                                        class="form-select form-select-solid" id="type" x-model="isAset">
                                    <option value="">Pilih Tipe Barang</option>
                                    <option value="JUAL" :selected="editVal.type === 'JUAL'">Jual</option>
                                    <option value="ASET" :selected="editVal.type === 'ASET'">Aset</option>
                                </select>
                        </div>
                        <div class="col-md-6" x-show="isAset === 'ASET' && !isLandAsset" x-transition x-cloak>
                                <label for="tangible_assets_type" class="required form-label">
                                    Kelompok Harta Berwujud
                                </label>
                                <select :name="`${isAset ? 'tangible_assets_type' : ''}`" id="tangible_assets_type"
                                        class="form-select form-select-solid"
                                        data-dropdown-parent="#modal-item" x-model="tangibleAsset">
                                    <option value="">Pilih Kelompok</option>
                                    <option value="Tanah"
                                            :selected="editVal.tangible_assets_type === 'Tanah'">
                                        Tanah
                                    </option>
                                    <option value="Bangunan"
                                            :selected="editVal.tangible_assets_type === 'Bangunan'">
                                        Bangunan
                                    </option>
                                    <option value="Bukan Bangunan"
                                            :selected="editVal.tangible_assets_type === 'Bukan Bangunan'">
                                        Bukan Bangunan
                                    </option>
                                </select>
                            </div>
                        </div>


                    <div class="row mb-10">
                        <div class="col-md-6" x-show="tangibleAsset === 'Bangunan'
                        &&
                        isAset === 'ASET' && !isLandAsset" x-cloak x-transition>
                                <label for="building_type" class="required form-label">
                                    Tipe Bangunan
                                </label>
                                <select
                                    :name="`${
                                    tangibleAsset === 'Bangunan' &&
                                    isAset === 'ASET' && !isLandAsset ? 'building_type' : ''}`"
                                    id="building_type"
                                    class="form-select form-select-solid"
                                    data-dropdown-parent="#modal-item">
                                    <option value="">Pilih Tipe Bangunan</option>
                                    <option value="Permanen"
                                            :selected="editVal?.building_type === 'Permanen'"
                                    >
                                        Permanen
                                    </option>
                                    <option value="Tidak Permanen"
                                            :selected="editVal?.building_type === 'Tidak Permanen'"
                                    >
                                        Tidak Permanen
                                    </option>
                                </select>
                        </div>
                        <div class="col-md-6">
                                <label for="unit_type_id" class="required form-label">Satuan</label>
                                <select name="unit_type_id" id="selected-unit-type"
                                        class="form-select form-select-solid unit-types-select2"
                                        data-dropdown-parent="#modal-item">
                                    <option></option>
                                </select>
                        </div>
                        <div class="col-md-6" x-show="tangibleAsset === 'Tanah'">
                            <label for="" class="form-label required">Akun Aset</label>
                            <input type="text" class="form-control form-control-solid" value="Tanah" disabled>
                        </div>
                        <div class="col-md-6" x-show="tangibleAsset === 'Bukan Bangunan'" x-transition x-cloak>
                            <div class="d-flex align-items-center mb-1">
                                <label for="category_id" class="required form-label me-2 mb-0">Kelompok</label>
                                <span class="text-danger">
                                    <a class="p-0 m-0" target="_blank"
                                       href="{{ url('master/operational/items/non-building-group-details') }}">Bantuan</a>
                                </span>
                            </div>
                            <select
                                :name="`${tangibleAsset === 'Bukan Bangunan' && isAset === 'ASET' ? 'non_building_group' : ''}`"
                                id="non_building_group"
                                class="form-select form-select-solid"
                                data-dropdown-parent="#modal-item" x-model="nonBuildingGroup">
                                <option value="">Pilih Kelompok</option>
                                <option value="Kelompok I"
                                        :selected="editVal?.non_building_group === 'Kelompok I'">
                                    Kelompok I
                                </option>
                                <option value="Kelompok II" :selected="editVal?.non_building_group === 'Kelompok II'">
                                    Kelompok II
                                </option>
                                <option value="Kelompok III"
                                        :selected="editVal?.non_building_group === 'Kelompok III'"
                                >
                                    Kelompok III
                                </option>
                                <option value="Kelompok IV"
                                        :selected="editVal?.non_building_group === 'Kelompok IV'"
                                >
                                    Kelompok IV
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-md-6" x-show="tangibleAsset === 'Bukan Bangunan' && !isVehicleAsset"
                             x-transition x-cloak>
                            <label for="category_id" class="required form-label">Kategori</label>
                            <select
                                :name="`${tangibleAsset === 'Bukan Bangunan' || isAset === 'ASET' || isAset === 'JUAL' || !isLandAsset || !isVehicleAsset ? 'category_id' : ''}`"
                                id="selected-item-category"
                                class="form-select form-select-solid item-category-select2"
                                data-dropdown-parent="#modal-item">
                                <option></option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6"
                             x-show="tangibleAsset === 'Bukan Bangunan' && !isVehicleAsset"
                             x-transition x-cloak>
                            <label for="unit_type_id" class="required form-label">Reorder Level</label>
                            <input type="text" class="form-control form-control-solid"
                                   :name="`${tangibleAsset === 'Bukan Bangunan' && !isVehicleAsset ? 'reorder_level' : ''}`"
                                   id="reorder_level" placeholder="Reorder Level" :value="editVal.reorder_level">
                        </div>
                        <div class="col-md-6" x-show="isAset === 'ASET' && tangibleAsset !== 'Tanah' && !isVehicleAsset"
                             x-transition
                             x-cloak>
                            <label for="asset_account_id" class="required form-label">
                                Akun Aset (Jika Masuk Aset)
                            </label>
                            <select
                                :name="`${isAset === 'ASET' && !isLandAsset && !isVehicleAsset ? 'asset_account_id' : '' }`"
                                id="selected-asset-account"
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
