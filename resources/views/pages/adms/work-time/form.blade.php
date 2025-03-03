<div class="modal fade" tabindex="-1" id="modal-work-time">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengaturan Jam Kerja</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-work-time" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for="name" class="required form-label">Nama</label>
                                <input type="text" id="name" name="name" class="form-control form-control-solid"
                                       placeholder="Nama" :value="editVal.name"/>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Jam masuk</label>
                                <input type="time" id="clock_in" name="clock_in"
                                       class="form-control form-control-solid time"
                                       placeholder="Jam Masuk" :value="editVal.clock_in"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Jam keluar</label>
                                <input type="time" id="clock_out" name="clock_out"
                                       class="form-control form-control-solid time"
                                       placeholder="Jam Keluar" :value="editVal.clock_out"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Mulai Checkin</label>
                                <input type="time" id="time_to_checkin" name="time_to_checkin"
                                       class="form-control form-control-solid time"
                                       placeholder="Mulai Checkin" :value="editVal.time_to_checkin"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Akhir Checkin</label>
                                <input type="time" id="end_time_to_checkin" name="end_time_to_checkin"
                                       class="form-control form-control-solid time"
                                       placeholder="Akhir Checkin" :value="editVal.end_time_to_checkin"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Mulai Check out</label>
                                <input type="time" id="time_to_checkout" name="time_to_checkout"
                                       class="form-control form-control-solid time"
                                       placeholder="Mulai Check out" :value="editVal.time_to_checkout"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-10">
                                <label for="time" class="required form-label">Akhir Check out</label>
                                <input type="time" id="end_time_to_checkout" name="end_time_to_checkout"
                                       class="form-control form-control-solid time"
                                       placeholder="Akhir Check out" :value="editVal.end_time_to_checkout"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
