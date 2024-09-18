<div class="modal fade" tabindex="-1" id="modal-import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Import Data Bonus Sales</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <div class="modal-body">
                <form id="form-import" @submit.prevent="importData()">
                    <div class="mb-10">
                        <label for="name" class="required form-label">File</label>
                        <input type="file" id="file" name="file_import" class="form-control form-control-solid"
                               placeholder="File" accept=".xlsx"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Download Template</label>
                        <br>
                        <div class="d-grid gap-2">
                            <a href="{{ asset('assets/template-excel/bonus-sales.xlsx') }}"
                               class="btn btn-primary btn-sm">Download</a>
                            <span class="text-danger">Wajib menggunakan template ini.</span>
                        </div>
                    </div>

                    <div class="float-end">
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
</div>
