<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form ODP Area</h5>
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

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Area</label>
                            <select name="area_id" id="selectedArea" class="form-select form-select-solid areas-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Nama</label>
                            <input type="text" id="name" name="name" class="form-control form-control-solid"
                                   placeholder="Nama" :value="editVal.name"/>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Klasifikasi</label>
                            <select name="classification" id="classification"
                                    class="form-select form-select-solid">
                                <option>Pilih Klasifikasi</option>
                                <option value="AS" :selected="editVal.classification === 'AS'">AS</option>
                                <option value="Turunan" :selected="editVal.classification === 'Turunan'">Turunan
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Passive Spliter</label>
                            <select name="passive_splitter" id="passive_splitter"
                                    class="form-select form-select-solid">
                                <option>Pilih Passive Splitter</option>
                                <option value="ODP" :selected="editVal.passive_splitter === 'ODP'">ODP</option>
                                <option value="FAT" :selected="editVal.passive_splitter === 'FAT'">FAT</option>
                                <option value="ODU" :selected="editVal.passive_splitter === 'ODU'">ODU</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Maks. Kapasitas</label>
                            <input type="number" id="max_capacity" name="max_capacity"
                                   class="form-control form-control-solid"
                                   placeholder="Maks. Kapasitas" :value="editVal.max_capacity"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kapasitas Terpakai</label>
                            <input type="number" id="used_capacity" name="used_capacity"
                                   class="form-control form-control-solid" :value="editVal.used_capacity"
                                   placeholder="Kapasitas Terpakai"/>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Tanggal CutOff</label>
                            <input type="date" id="cut_off_date" name="cut_off_date"
                                   class="form-control form-control-solid"
                                   placeholder="Tanggal CutOff" :value="editVal.cut_off_date"/>
                        </div>
                    </div>


                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-5">
                            <label for="name" class="required form-label">Lattitude</label>
                            <input type="text" id="lat" name="lat" class="form-control form-control-solid"
                                   placeholder="Lattitude" :value="editVal.lat"/>
                        </div>
                        <div class="col-lg-5">
                            <label for="name" class="required form-label">Longitude</label>
                            <input type="text" id="long" name="long" class="form-control form-control-solid"
                                   placeholder="Longitude" :value="editVal.long"/>
                        </div>
                        <div class="col-lg-2 align-self-end py-2">
                            <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
                        </div>
                    </div>

                    <div id="map-edit" style="z-index: 9999; height: 500px;"></div>
                </div>


                <div class=" modal-footer
                    ">
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
