<div class="modal fade" tabindex="-1" id="modal-sub-category">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Kategori Akun</h5>
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

            <form id="form-sub-category" @submit.prevent="saveChild(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Kategori" :value="editVal?.parent?.name ?? editVal?.name" disabled/>
                    </div>
                    <div>
                        <div class="mb-10">
                            <label for="name" class="required form-label">Nama Sub Kategori</label>
                            <input type="text" id="name" name="name" class="form-control form-control-solid"
                                   placeholder="Nama Sub Kategori"
                                   :value="editVal?.parent_id ? editVal?.name : ''"/>
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
