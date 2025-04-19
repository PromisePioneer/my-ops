<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tunjangan Transportasi</h5>
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
                    <div class="mb-10">
                        <label for="name" class="required form-label">Karyawan</label>
                        <select name="user_id[]" class="form-control form-control-solid users-select2"
                                data-dropdown-parent="#modal-create" multiple>
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid date"
                               placeholder="Pilih tanggal"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Jarak SPK</label>
                        <select class="form-select form-select-solid" name="transportation_type"
                                x-model="transportationType">
                            <option>Pilih</option>
                            <option value="Dibawah 15 Km">Dibawah 15 Kilometer</option>
                            <option value="Diatas 15 Km">Diatas 15 Kilometer</option>
                        </select>
                    </div>
                    <div class="mb-10" x-show="transportationType === 'Diatas 15 Km'" x-transition>
                        <label for="name" class="required form-label">SPK</label>
                        <input type="file" id="spk_image"
                               :name="transportationType === 'Diatas 15 Km' ? 'spk_image' : ''"
                               class="form-control form-control-solid"
                        />
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
