<div class="modal fade" tabindex="-1" id="modal-department">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Departemen</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <x-icons.close/>
                    </span>
                </div>
            </div>

            <form id="form-department" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="code" class="required form-label">Kode</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid"
                               placeholder="Kode Departemen" :value="editVal?.code"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Departemen" :value="editVal?.name"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-light-primary btn-sm"
                            :disabled="buttonLoading">
                        <x-icons.save/>
                        <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
