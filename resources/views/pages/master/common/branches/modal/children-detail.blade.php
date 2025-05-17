<div class="modal fade" tabindex="-1" id="modal-children-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" x-text="`${editVal.parent.name} - ${editVal.name}`"></h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                       <x-icons.close/>
                    </span>
                </div>
            </div>

            <form id="form-children-detail" @submit.prevent="updateChildren(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Sub Cabang</label>
                        <input type="text" name="name" id="name" class="form-control form-control-solid"
                               :value="editVal.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Alamat</label>
                        <textarea type="text" class="form-control form-control-solid" name="address" id="address"
                                  x-text="editVal.address"></textarea>
                    </div>
                </div>


                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light-danger btn-sm" @click="destroyChildren(editVal.id)" :disabled="buttonLoading">
                        <i class="ki-duotone ki-trash-square fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <span x-text="buttonLoading ? 'Loading...' : 'Hapus'"></span>
                    </button>
                    <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                        <x-icons.save/>
                        <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
