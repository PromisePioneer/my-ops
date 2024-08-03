<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Absen</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="user_id" class="required form-label">Nama Karyawan</label>
                        <select name="user_id" id="user_id" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#modal-create">
                            <option value="0">Pilih</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="date" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid"
                        />
                    </div>
                    <div class="mb-10">
                        <label for="date" class="required form-label">Clock in</label>
                        <input type="time" id="clock_in" name="clock_in" class="form-control form-control-solid"
                        />
                    </div>
                    <div class="mb-10">
                        <label for="date" class="required form-label">Clock out</label>
                        <input type="time" id="clock_out" name="clock_out" class="form-control form-control-solid"
                        />
                    </div>

                    <div class="mb-10">
                        <label for="user_id" class="required form-label">Status Absen (Jika tidak hadir)</label>
                        <select name="user_id" id="user_id" class="form-select form-select-solid">
                            <option value="0" selected disabled>Pilih</option>
                            <option value="Absen">Absen</option>
                            <option value="Izin">Izin</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"
                            :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
