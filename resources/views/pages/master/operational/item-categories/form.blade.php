<div class="modal fade" tabindex="-1" id="modal-item-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kategori Barang</h5>
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

            <form id="form-item-category" @submit.prevent="saveItemCategories(editVal?.id ?? null)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama" :value="editVal?.name ?? ''"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Deskripsi</label>
                        <textarea type="text" id="description" name="description"
                                  class="form-control form-control-solid" data-kt-autosize="true"
                                  placeholder="Deskripsi" x-text="editVal?.description"></textarea>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Catatan</label>
                        <textarea type="text" id="notes" name="notes"
                                  class="form-control form-control-solid" data-kt-autosize="true"
                                  placeholder="Keterangan" x-text="editVal?.notes"></textarea>
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
