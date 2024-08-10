<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Permission</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-edit" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="code" class="required form-label">Nama Menu</label>
                        <input type="text" id="name" name="name" class="form-control form-control-solid"
                               placeholder="Nama Menu"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Link</label>
                        <input type="text" id="link" name="link" class="form-control form-control-solid"
                               placeholder="Link Menu"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Icon</label>
                        <input type="text" id="link" name="link" class="form-control form-control-solid"
                               placeholder="Icon Menu"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Sub Menu</label>
                        <select name="sub_menu" id="sub_menu" class="form-select form-select-solid">
                            <template x-for="menu in menus.data" :key="menus.id">
                                <option :value="menu.id" x-text="menu.description"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">No urut</label>
                        <input type="text" id="link" name="link" class="form-control form-control-solid"
                               placeholder="Link Menu"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm"
                            :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
