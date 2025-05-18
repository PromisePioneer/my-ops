<div class="modal fade" tabindex="-1" id="modal-create-children">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Sub Cabang</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <x-icons.close/>
                </div>
            </div>

            <form id="form-create-children" @submit.prevent="saveChildren()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Cabang</label>
                        <input type="hidden" name="parent_id" id="parent_id" :value="editVal.id">
                        <input type="text" class="form-control form-control-solid"
                               :value="editVal.name" disabled/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Sub Cabang</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Sub Cabang"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Alamat</label>
                        <textarea class="form-control form-control-solid" name="address" id="address"
                                  data-kt-autosize="true" placeholder="Alamat"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-primary btn-sm" :disabled="buttonLoading">
                        <x-icons.save/>
                        <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
