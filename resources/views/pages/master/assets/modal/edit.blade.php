<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Aset</h5>
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
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Cabang</label>
                            <select name="branch_id" id="selectedBranch"
                                    class="form-select form-select-solid branches-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Kategori Akun</label>
                            <select name="account_id" id="selectedAccount"
                                    class="form-select form-select-solid accounts-select2"
                                    data-dropdown-parent="#modal-edit">
                                <option></option>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Nama</label>
                            <input type="text" id="name" name="name" class="form-control form-control-solid"
                                   placeholder="Nama Aset" :value="editVal.name"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Jumlah Unit</label>
                            <input type="number" id="unit" name="unit" class="form-control form-control-solid"
                                   placeholder="Jumlah unit" :value="editVal.unit"/>
                        </div>
                    </div>


                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Masa Manfaat</label>
                            <input type="number" id="useful_life" name="useful_life"
                                   class="form-control form-control-solid"
                                   placeholder="Nama Aset" :value="editVal.useful_life"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Tanggal Perolehan</label>
                            <input type="date" id="date_received" name="date_received"
                                   class="form-control form-control-solid date"
                                   placeholder="Tanggal Perolehan" :value="editVal.date_recieved"/>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Harga / Unit</label>
                            <input type="number" id="price_per_unit" name="price_per_unit"
                                   class="form-control form-control-solid"
                                   placeholder="Jumlah unit" :value="editVal.price_per_unit"/>
                        </div>
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
