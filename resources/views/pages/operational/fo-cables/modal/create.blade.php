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
                            <label for="name" class="required form-label">Segmen</label>
                            <input type="text" class="form-control form-control-solid" name="segment_id"
                                   id="segment_id" placeholder="Segmen">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Klasifikasi</label>
                            <select name="classification" id="classification" class="form-select form-select-solid">
                                <option value="Backbone">Backbone</option>
                                <option value="Backhaul">Backhaul</option>
                                <option value="Fronthaul">Fronthaul</option>
                                <option value="Akses">Akses</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Letak Kabel</label>
                            <select name="cable_placement" id="cable_placement" class="form-select form-select-solid">
                                <option value="Udara">Udara</option>
                                <option value="Underground">Darat</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Panjang Kabel</label>
                            <input type="number" class="form-control form-control-solid" name="length"
                                   id="length">
                        </div>
                    </div>


                    <div class="row mb-10">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Jumlah Core</label>
                            <input type="text" class="form-control form-control-solid" name="total_core"
                                   placeholder="Jumlah Core">
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Jalur Kabel</label>
                            <input type="text" class="form-control form-control-solid" name="cable_address"
                                   placeholder="Jumlah Core">
                        </div>
                    </div>


                    <div class="col-lg-6 mb-10">
                        <label for="name" class="required form-label">Tanggal Cutoff</label>
                        <input type="date" class="form-control form-control-solid" name="cut_off_date"
                               id="cut_off_date">
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Latitude (Titik Awal)</label>
                            <input type="text" id="starting_point_lat" name="starting_point_lat"
                                   class="form-control form-control-solid" x-model="startingPointLat"
                                   placeholder="Latitude (Titik Awal)"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Longitude (Titik Awal)</label>
                            <input type="text" id="starting_point_long" name="starting_point_long"
                                   class="form-control form-control-solid"
                                   placeholder="Longitude (Titik Akhir)" x-model="startingPointLong"/>
                        </div>
                    </div>

                    <div class="row mb-10 align-items-center">
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Latitude (Titik Akhir)</label>
                            <input type="text" id="ending_point_lat" name="ending_point_lat"
                                   class="form-control form-control-solid" x-model="endingPointLat"
                                   placeholder="Latitude (Titik Awal)"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="name" class="required form-label">Longitude (Titik Akhir)</label>
                            <input type="text" id="ending_point_long" name="ending_point_long"
                                   class="form-control form-control-solid"
                                   placeholder="Longitude (Titik Akhir)" x-model="endingPointLong"/>
                        </div>
                    </div>

                    <div class="float-end row mb-4">
                        <button type="button" @click="checkCoords()" class="btn btn-primary btn-sm">Check</button>
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
