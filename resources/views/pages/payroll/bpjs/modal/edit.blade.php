<div class="modal fade" tabindex="-1" id="modal-bpjs-ket-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form BPJS Ketenagakerjaan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-bpjs-ket-edit" @submit.prevent="saveBPJSKet(bpjsKetEditVal.id)">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="nama" class="required form-label">Nama</label>
                        <input type="text" class="form-control form-control-solid" name="name"
                               :value="bpjsKetEditVal.name">
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Company Rate</label>
                        <input type="number" step="any" id="company_rate" name="company_rate"
                               class="form-control form-control-solid"
                               placeholder="Company rate" :value="bpjsKetEditVal.company_rate"/>
                    </div>

                    <div class="mb-10">
                        <label for="amount" class="required form-label">Employee Rate</label>
                        <input type="number" class="form-control form-control-solid" name="employee_rate"
                               id="employee_rate"
                               data-kt-autosize="true" step="any" placeholder="Employee Rate"
                               :value="bpjsKetEditVal.employee_rate"/>
                    </div>
                </div>

                <div class=" modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Simpan'">
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
