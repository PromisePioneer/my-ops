<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kategori Layanan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama Layanan</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Nama Kode" x-bind:value="editVal.name"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kapasitas</label>
                        <input type="number" id="capacity" name="capacity" class="form-control form-control-solid" placeholder="Nama Kapatitas" x-bind:value="editVal.capacity"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading" x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
