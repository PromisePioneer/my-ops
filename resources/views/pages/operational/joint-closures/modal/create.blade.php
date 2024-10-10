<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kabel FO</h5>
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

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kode</label>
                            <select name="code_id"
                                    class="form-select form-select-solid code-select2"
                                    data-dropdown-parent="#modal-create">
                                <option></option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Kabel</label>
                            <select name="fo_cable_id"
                                    class="form-select form-select-solid foCable-select2"
                                    data-dropdown-parent="#modal-create">
                                <option></option>
                            </select>
                        </div>

                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Wilayah</label>
                            <input type="text" class="form-control form-control-solid" name="region"
                                   placeholder="Wilayah">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Tanggal Cut Off</label>
                            <input type="date" class="form-control form-control-solid" name="cut_off_date"
                                   placeholder="Tanggal Cut Off">
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Latitude</label>
                            <input type="text" id="lat" name="lat" class="form-control form-control-solid"
                                   placeholder="Lattitude"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="name" class="required form-label">Longitude</label>
                            <input type="text" id="long" name="long" class="form-control form-control-solid"
                                   placeholder="Longitude"/>
                        </div>
                        <div class="col-lg-3 align-self-end py-2">
                            <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
                        </div>
                    </div>

                    <div id="map-create" style="z-index: 9999; height: 500px; margin-top: 90px"></div>
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
