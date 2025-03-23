<div class="modal fade" tabindex="-1" id="modal-psb">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form PSB</h5>
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

            <form id="form-psb" @submit.prevent="save(editVal?.id ?? null)">
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="form-label required">
                            Tanggal Penarikan
                        </label>
                        <input type="date" class="form-control form-control-solid  date" name="date"
                               placeholder="Tanggal Penarikan">
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">Tanggal Aktif</label>
                        <input type="date" class="form-control form-control-solid date" name="active_date"
                               placeholder="Tanggal Aktif">
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">
                            Tanggal Registrasi
                        </label>
                        <input type="date" class="form-control form-control-solid  date" name="registration_date"
                               placeholder="Tgl. Registration">
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">
                            Nama Pelanggan
                        </label>
                        <input type="text" class="form-control form-control-solid" name="customer_name"
                               placeholder="Nama pelanggan">
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">
                            Nomor HP
                        </label>
                        <input type="text" class="form-control form-control-solid" name="phone_number"
                               placeholder="Nomor HP">
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">Area</label>
                        <select name="area_id" id="area_id"
                                class="form-select form-select-solid areas-select2"
                                data-dropdown-parent="#modal-psb">
                            <option></option>
                        </select>
                    </div>
                    <div class="row mb-4">
                        <label class="form-label required">Alamat</label>
                        <textarea name="address" id="address" class="form-control form-control-solid"
                                  data-kt-autosize="true">
                        </textarea>
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
