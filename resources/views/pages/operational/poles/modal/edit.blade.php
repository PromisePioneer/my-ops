<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tiang</h5>
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
                            <label for="diameter" class="required form-label">Diameter</label>
                            <input type="text" id="diameter" name="diameter" class="form-control form-control-solid"
                                   placeholder="Diameter" :value="editVal.diameter"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="length" class="required form-label">Panjang</label>
                            <input type="text" id="length" name="length" class="form-control form-control-solid"
                                   placeholder="Panjang" :value="editVal.length"/>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="region" class="required form-label">Wilayah</label>
                            <input type="text" id="region" name="region" class="form-control form-control-solid"
                                   placeholder="Wilayah" :value="editVal.region"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kode Tiang</label>
                            <input type="text" id="code" name="code" class="form-control form-control-solid"
                                   placeholder="Kode Tiang" :value="editVal.code"/>
                        </div>
                    </div>

                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kode Tiang</label>
                            <input type="date" id="cut_off_date" name="cut_off_date"
                                   class="form-control form-control-solid"
                                   placeholder="Kode Tiang" :value="editVal.cut_off_date"/>
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Lattitude</label>
                            <input type="text" id="lat" name="lat" class="form-control form-control-solid"
                                   placeholder="Lattitude" :value="editVal.lat"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Longitude</label>
                            <input type="text" id="long" name="long" class="form-control form-control-solid"
                                   placeholder="Longitude" :value="editVal.long"/>
                        </div>
                        <div class="col-lg-3 align-self-end py-2">
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
