<div class="modal fade" tabindex="-1" id="modal-additional-deduction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Denda Lainnya</h5>
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

            <form id="form-additional-deduction" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" id="date" name="date" class="form-control form-control-solid date"
                               placeholder="Pilih Tanggal" :value="editVal?.date ?? ''"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Karyawan</label>
                        <select name="user_id" id="selected-user" class="form-select form-select-solid users-select2"
                                data-dropdown-parent="#modal-additional-deduction">
                            <option></option>
                        </select>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Tipe Denda</label>
                        <select name="type" class="form-control form-control-solid">
                            <option selected>Pilih</option>
                            <option value="Tangga" :selected="editVal.type === 'Tangga'">Tangga</option>
                            <option value="Piket" :selected="editVal.type === 'Piket'">Piket</option>
                            <option value="Mobil" :selected="editVal.type === 'Mobil'">Mobil</option>
                        </select>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Nominal Denda</label>
                        <input type="number" id="amount" name="amount" class="form-control form-control-solid"
                               placeholder="Nominal" :value="editVal.amount"/>
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
