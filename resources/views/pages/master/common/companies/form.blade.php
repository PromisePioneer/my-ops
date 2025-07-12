<div class="modal fade" tabindex="-1" id="modal-company">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Perusahaan</h5>
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

            <form id="form-company" @submit.prevent="save(editVal?.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Kode</label>
                        <input type="text" id="code" name="code" class="form-control form-control-solid"
                               placeholder="Kode Perusahaan" :value="editVal?.code"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Perusahaan" :value="editVal?.name"/>
                    </div>

                    <div class="mb-10">
                        <label for="name" class="required form-label">No.Telp</label>
                        <input type="number" id="phone" name="phone" class="form-control form-control-solid"
                               placeholder="Telepon" :value="editVal?.phone"/>
                    </div>


                    <div class="mb-10">
                        <label for="address" class="required form-label">Logo</label>
                        <input type="file" class="form-control form-control-solid" name="image" id="image"
                               accept=".jpg,.png,.jpeg">
                    </div>

                    <div class="mb-10">
                        <label for="address" class="required form-label">Alamat</label>
                        <textarea id="address" name="address" class="form-control form-control-solid"
                                  data-kt-autosize="true" x-text="editVal?.address"
                                  placeholder="Alamat lengkap"></textarea>
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
