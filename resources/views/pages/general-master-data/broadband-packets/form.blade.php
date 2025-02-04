<div class="modal fade" tabindex="-1" id="modal-broadband-packet">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Paket Broadband</h5>
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

            <form id="form-broadband-packet" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Cabang</label>
                            <select name="branch_id" id="selected-branch"
                                class="form-select form-select-solid branches-select2"
                                    data-dropdown-parent="#modal-broadband-packet">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Paket</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Paket" :value="editVal?.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Capacity</label>
                        <input type="text" id="capacity" name="capacity" class="form-control form-control-solid"
                               placeholder="Kapasitas (Mbps)" :value="editVal?.capacity"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Harga</label>
                        <input type="number" id="price" name="price" class="form-control form-control-solid"
                               placeholder="Harga (Rp)" :value="editVal?.price"/>
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
