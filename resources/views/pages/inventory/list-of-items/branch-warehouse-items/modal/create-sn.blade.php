<div class="modal fade" tabindex="-1" id="modal-sn-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kode Barang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="fas fa-xmark-circle"></i>
                    </span>
                </div>
            </div>

            <form id="form-sn-create" @submit.prevent="generateCodeAndSN()">
                <div class="modal-body">
                        <div class="mb-10">
                            <label for="sn" class="required form-label">Serial Number</label>
                            <input type="text" id="sn-create" name="sn"
                                   class="form-control form-control-solid"
                                   placeholder="Serial Number" onfocus="true"/>
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
