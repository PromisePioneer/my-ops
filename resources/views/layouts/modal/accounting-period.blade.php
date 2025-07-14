<div class="modal fade" tabindex="-1" id="modal-accounting-period">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Akun</h5>
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

            <form id="form-accounting-period" @submit.prevent="saveAccountingPeriod()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="number" name="year" class="form-control form-control-solid"
                               :value="accountingPeriod"/>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="current_company_session" class="required form-label">Perusahaan</label>
                        <select name="current_company_session" id="current_company_session"
                                class="form-select form-select-solid companies-select2"
                                data-dropdown-parent="#modal-accounting-period"></select>
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
