<div class="modal fade" tabindex="-1" id="modal-export">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Import ODP</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <div class="modal-body">
                <form id="form-export" action="{{ url('operational/odp/export') }}">
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Awal</label>
                        <input type="date" id="start_date" name="start_date"
                               class="form-control form-control-solid date"
                               placeholder="Tanggal awal"/>
                    </div>
                    <div class="mb-10">
                        <label for="name" class="required form-label">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-solid date"
                               placeholder="Tanggal akhir"/>
                    </div>

                    <div class=" float-end">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Export'"></button>
                    </div>
                </form>

            </div>


        </div>
    </div>
</div>
