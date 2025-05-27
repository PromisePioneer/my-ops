<div class="modal fade" id="asset-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Aset</h5>
                <button class="btn btn-icon btn-sm btn-active-light-danger ms-2 btn-close" @click="modal.hide()"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </button>
            </div>

            <form id="asset-form" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Cabang</label>
                            <select name="branch_id" class="form-select form-select-solid branches-select2"
                                    data-dropdown-parent="#asset-modal" id="selected-main-asset-branch">
                                <option></option>
                            </select>
                        </div>

                    </div>


                    <div class="row mb-7">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Kondisi Barang</label>
                            <select class="form-select form-select-solid" x-model="itemCondition"
                                    @change="changeItemCondition()">
                                <option value="Terpakai">Terpakai</option>
                                <option value="Digudang">Digudang</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Kode Aset</label>
                            <input type="text" class="form-control form-control-solid" name="code" id="code"
                                   placeholder="Kode Aset" :value="editVal?.code ?? ''">
                        </div>
                    </div>
                    <div class="row mb-7">


                        <div class="col-md-6">
                            <label for="name" class="required form-label">Nama Barang</label>
                            <select name="item_id" id="selected-asset-item"
                                    class="form-select form-select-solid asset-items-select2"
                                    data-dropdown-parent="#asset-modal">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Jumlah Unit</label>
                            <input type="text" id="unit" name="unit" class="form-control form-control-solid"
                                   placeholder="Jumlah unit" :value="editVal?.unit ?? 1" readonly/>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Tanggal Perolehan</label>
                            <br>
                            <input type="date" id="date_received" name="date_received"
                                   class="form-control form-control-solid date"
                                   placeholder=" Tanggal Perolehan" :value="editVal?.date_received"/>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="required form-label">Harga / Unit</label>
                            <input type="text" id="price_per_unit" name="price_per_unit"
                                   class="form-control form-control-solid"
                                   placeholder="Harga per unit" :value="parseFloat(editVal?.price_per_unit)"/>
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
