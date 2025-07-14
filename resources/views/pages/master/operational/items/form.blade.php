<div class="modal fade" id="modal-item">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Barang</h5>
                <button class="btn btn-icon btn-sm btn-active-light-danger ms-2 btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </button>
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
                                <input class="form-check-input" type="checkbox" id="isCodeListed"
                                       x-model="hasSNOnItem"
                                       :name="`${!itemMustHaveCode ? 'is_code_listed' : ''}`"
                                       :checked="editVal?.is_code_listed === 1"/>
                                <label class="form-check-label" for="isCodeListed">
                                    Kode Bawaan
                                </label>
                            </div>
                            <div class="form-check form-switch form-check-custom form-check-solid"
                                 x-show="tangibleAsset === 'Bukan Bangunan'"
                                 x-transition x-cloak>
                                <input class="form-check-input" type="checkbox" id="isVehicle"
                                       x-model="isVehicleAsset"
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
                        <div class="col-md-6 mb-7">
                            <label for="name" class="required form-label">Perusahaan</label>
                            <select name="company_id" id="selected-company"
                                    class="form-select form-select-solid companies-select2">
                                <option></option>
                            </select>
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
                                    class="form-select form-select-solid" data-dropdown-parent="#item-drawer-action"
                                    id="type" x-model="isAset">
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
                                    x-model="tangibleAsset">
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
                            >
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
                            <x-select2.index
                                name="unit_type_id"
                                id="selected-unit-type"
                                class="form-select form-select-solid"
                                elementSelector="unit-types-select2"
                                parentElementIfExist="#modal-item"
                            />
                        </div>
                        <div class="col-md-6" x-show="tangibleAsset === 'Tanah'">
                            <label for="" class="form-label required">Akun Aset</label>
                            <input type="text" class="form-control form-control-solid" value="Tanah" disabled>
                        </div>
                        <div class="col-md-6 mb-10" x-show="tangibleAsset === 'Bukan Bangunan'" x-transition
                             x-cloak>
                            <div class="d-flex align-items-center mb-1">
                                <label for="category_id" class="required form-label me-2 mb-0">Kelompok</label>
                                <span class="text-danger">
                                    <a class="btn btn-sm btn-link p-0 m-0" target="_blank"
                                       href="{{ url('master/operational/items/non-building-group-details') }}">Bantuan</a>
                                </span>
                            </div>
                            <select
                                :name="`${tangibleAsset === 'Bukan Bangunan' && isAset === 'ASET' ? 'non_building_group' : ''}`"
                                id="non_building_group"
                                class="form-select form-select-solid"
                                x-model="nonBuildingGroup">
                                <option value="">Pilih Kelompok</option>
                                <option value="Kelompok I"
                                        :selected="editVal?.non_building_group === 'Kelompok I'">
                                    Kelompok I
                                </option>
                                <option value="Kelompok II"
                                        :selected="editVal?.non_building_group === 'Kelompok II'">
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
                        <div class="col-md-6"
                             x-show="(tangibleAsset === 'Bukan Bangunan' && !isVehicleAsset)  || isAset === 'JUAL'"
                             x-transition x-cloak>
                            <div class="d-flex align-items-center mb-1">
                                <label for="" class="required form-label me-2 mb-0">Kategori</label>
                                <span class="text-danger">
                                   <a class="btn btn-sm btn-link p-0 m-0" target="_blank"
                                      href="{{ url('master/operational/item-categories') }}">Bantuan</a>
                                </span>
                            </div>

                            <x-select2.index
                                name="category_id"
                                id="selected-item-category"
                                class="form-select form-select-solid"
                                elementSelector="item-category-select2"
                                parentElementIfExist="#modal-item"
                            />
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
                        <div class="col-md-6"
                             x-show="isAset === 'ASET' && tangibleAsset !== 'Tanah' && !isVehicleAsset"
                             x-transition
                             x-cloak>
                            <label for="asset_account_id" class="required form-label">
                                Akun Aset (Jika Masuk Aset)
                            </label>
                            <x-select2.index
                                name="asset_account_id"
                                id="selected-asset-account"
                                class="form-select form-select-solid"
                                elementSelector="asset-accounts-select2"
                                parentElementIfExist="#modal-item"
                            />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @if(Request::path() === 'master/accounting/assets')
                        <button class="btn btn-light-info btn-sm" type="button">
                            Form Aset
                        </button>
                        <button class="btn btn-light-info btn-sm" type="button">
                            Form Saldo Awal Persediaan
                        </button>
                    @endif
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                            <x-icons.save/>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
