<div class="modal fade" tabindex="-1" id="modal-edit">
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

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Segmen</label>
                            <input type="text" class="form-control form-control-solid" name="segment_id"
                                   id="segment_id" placeholder="Segmen" :value="editVal.segment_id">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Cabang</label>
                            <select name="branch_id" id="selectedBranch"
                                    class="form-select form-select-solid branch-select2">
                                <option></option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Letak Kabel</label>
                            <select name="cable_placement" id="cable_placement" class="form-select form-select-solid">
                                <option value="Udara" :selected="`${editVal.cable_placement === 'Backbone'}`">Udara
                                </option>
                                <option value="Underground">Darat</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Panjang Kabel</label>
                            <input type="number" class="form-control form-control-solid" name="length"
                                   id="length" :value="editVal.length" placeholder="Panjang Kabel">
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Jumlah Core</label>
                            <input type="text" class="form-control form-control-solid" name="total_core"
                                   placeholder="Jumlah Core" :value="editVal.total_core">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Jalur Kabel</label>
                            <input type="text" class="form-control form-control-solid" name="cable_address"
                                   placeholder="Jumlah Core" :value="editVal.cable_address">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Klasifikasi</label>
                            <select name="classification" id="classification" class="form-select form-select-solid">
                                <option value="Backbone"
                                        :selected="`${editVal.classification === 'Backbone'}`">
                                    Backbone
                                </option>
                                <option value="Backhaul" :selected="`${editVal.classification === 'Backhaul'}`">
                                    Backhaul
                                </option>
                                <option value="Fronthaul" :selected="`${editVal.classification === 'Fronthaul'}`">
                                    Fronthaul
                                </option>
                                <option value="Akses" :selected="`${editVal.classification === 'Akses'}`">Akses</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-10">
                            <label for="name" class="required form-label">Tanggal Cutoff</label>
                            <input type="date" class="form-control form-control-solid date" name="cut_off_date"
                                   id="cut_off_date" :value="editVal.cut_off_date">
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Latitude (Titik Awal)</label>
                            <input type="text" id="starting_point_lat" name="starting_point_lat"
                                   x-model="startingPointLat"
                                   class="form-control form-control-solid"
                                   placeholder="Latitude (Titik Awal)" :value="editVal.starting_point_lat"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Longitude (Titik Awal)</label>
                            <input type="text" id="starting_point_long" x-model="startingPointLong"
                                   name="starting_point_long"
                                   class="form-control form-control-solid"
                                   placeholder="Longitude (Titik Awal)" :value="editVal.starting_point_long"/>
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Latitude (Titik Akhir)</label>
                            <input type="text" id="ending_point_lat" name="ending_point_lat" x-model="endingPointLat"
                                   class="form-control form-control-solid"
                                   placeholder="Latitude (Titik Akhir)" :value="editVal.ending_point_lat"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Longitude (Titik Akhir)</label>
                            <input type="text" id="ending_point_long" name="ending_point_long"
                                   class="form-control form-control-solid" x-model="endingPointLong"
                                   placeholder="Longitude (Titik Akhir)" :value="editVal.ending_point_long"/>
                        </div>
                    </div>

                    <div class="float-end row mb-4">
                        <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
                    </div>


                    <div id="map-edit" style="z-index: 9999; height: 500px; margin-top: 90px"></div>
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
