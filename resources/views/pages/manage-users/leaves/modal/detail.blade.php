<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengajuan Cuti</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>
            <div class="modal-body">
                <div class="mb-10">
                    <label for="name" class="required form-label">Tanggal Mulai</label>
                    <input type="text" class="form-control form-control-solid" :value="detailValue?.start_date" disabled>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Tanggal Selesai</label>
                    <input type="text" class="form-control form-control-solid" :value="detailValue?.end_date" disabled>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Karyawan</label>
                    <input type="text" class="form-control form-control-solid" :value="detailValue.user?.name" disabled>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Type Cuti</label>
                    <input type="text" class="form-control form-control-solid" :value="detailValue.leaves_status"
                           disabled>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Status Konfirmasi</label>
                    <input type="text" class="form-control form-control-solid" :value="detailValue.confirmation_status"
                           disabled>
                </div>
                <div class="mb-10">
                    <label for="name" class="required form-label">Alasan Cuti</label>
                    <textarea class="form-control form-control-solid" name="confirmation_reason"
                              data-kt-autosize="true" x-text="detailValue.reason" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
