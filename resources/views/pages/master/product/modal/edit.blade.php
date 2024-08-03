<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Product</h5>

                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <div class="modal-body">
                <form id="form-edit" @submit.prevent="update(editVal.id)">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid" placeholder="Kode" :value="editVal.code"/>
                    </div>


                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid" placeholder="Nama Produk" :value="editVal.name" />
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Kategori</label>
                        <input type="text" id="category" name="category" class="form-control form-control-solid" placeholder="Kategori" x-bind:value="editVal.category"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">Harga Satuan</label>
                        <input type="number" id="unit_price" name="unit_price" class="form-control form-control-solid" placeholder="Kategori" x-bind:value="editVal.unit_price"/>
                    </div>
                    <div class="float-end">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
