<div class="modal fade" tabindex="-1" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Saldo Awal</h5>
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

            <form id="form-edit" @submit.prevent="update(editVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Cabang</label>
                        <select name="branch_id" id="selectedBranch"
                                class="form-select form-select-solid branches-select2"
                                data-dropdown-parent="#modal-edit">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal</label>
                        <input type="date" class="form-control form-control-solid date" id="date" name="date"
                               placeholder="Tanggal" :value="editVal.date">
                    </div>
                    <div class="mb-10">
                        <label for="account_id" class="required form-label">Akun</label>
                        <select name="account_id" id="selectedAccount"
                                class="form-select form-select-solid accounts-select2"
                                data-dropdown-parent="#modal-edit">
                            <option></option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Saldo</label>
                        <input type="number" id="amount" name="amount" class="form-control form-control-solid"
                               placeholder="Saldo" :value="editVal.amount"/>
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
