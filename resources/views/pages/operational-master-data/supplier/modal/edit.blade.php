<div class="modal fade" tabindex="-1" id="modal-supplier-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Supplier</h5>
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

            <form id="form-supplier-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama" :value="editVal.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">No.Telepon</label>
                        <input type="number" id="phone" name="phone" class="form-control form-control-solid"
                               placeholder="No Telepon" :value="editVal.phone"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Alamat</label>
                        <textarea class="form-control form-control-solid" name="address" id="address"
                                  data-kt-autosize="true" placeholder="Alamat" x-text="editVal.address"></textarea>
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
