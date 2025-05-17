<div class="modal fade" tabindex="-1" id="modal-skl">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Syarat Ketentuan Layanan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                       <x-icons.close/>
                    </span>
                </div>
            </div>

            <form id="form-skl" @submit.prevent="saveSKL(editVal?.id ?? null)">
                <div class="modal-body">
                    <div>
                        <label for="name" class="required form-label">Syarat & Ketentuan Layananan</label>
                        <textarea name="name" id="name" data-kt-autosize="true"
                                  class="form-control form-control-solid" x-text="editVal?.name ?? ''"></textarea>
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
