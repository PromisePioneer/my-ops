<div class="modal fade" tabindex="-1" id="modal-initial-balance">
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

            <form id="form-initial-balance" @submit.prevent="save(editVal.id)">
                <div class="modal-body">
                    @if(empty(Auth::user()->branch_id))
                        <div class="mb-10">
                            <label for="account_id" class="required form-label">Cabang</label>
                            <input type="hidden" name="branch_id" :value="branchVal.id">
                            <input type="text" class="form-control form-control-solid" :value="branchVal.name" disabled>
                        </div>
                    @endif
                    <div class="mb-10">
                        <label for="account_id" class="required form-label">Akun</label>
                        <input type="hidden" name="account_id" :value="accountVal.id">
                        <input type="text" class="form-control form-control-solid" :value="accountVal.name" disabled>
                    </div>
                    <div class="mb-10">
                        <label for="account_id" class="required form-label">Tipe</label>
                        <input type="text" class="form-control form-control-solid" name="entries_type"
                               :value="entriesType" readonly>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Saldo</label>
                        <input type="text" id="amount" name="amount" class="form-control form-control-solid"
                               placeholder="Saldo" :value="editVal.amount"/>
                    </div>


                    <div class="mb-10">
                        <input type="hidden" :value="year" name="year">
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
