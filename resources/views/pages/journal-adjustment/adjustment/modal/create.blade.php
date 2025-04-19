<div class="modal fade" tabindex="-1" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Jurnal Penyesuaian</h5>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x"></span>
                </div>
            </div>

            <form id="form-create" @submit.prevent="save()">
                <div class="modal-body">
                    <div class="mb-10">
                        <label for="description" class="required form-label">Tanggal Pembayaran</label>
                        <input type="date" class="form-control form-control-solid" name="payment_date">
                        <p class="text-danger">tanggal ini di automasi tiap bulannya sampai saldo habis.</p>
                    </div>
                    <div class="mb-10">
                        <label for="" class="required form-label">Jurnal Awal</label>
                        <select name="initial_journal_id" id=""
                                class="form-select form-select-solid initial-journal-select2"
                                data-dropdown-parent="#modal-create">
                            <option value="0">Pilih Jurnal Awal</option>
                        </select>
                    </div>
                    <div class="mb-10">
                        <label for="description" class="required form-label">Deskripsi</label>
                        <textarea name="description" data-kt-autosize="true"
                                  class="form-control form-control-solid"></textarea>
                    </div>
                    <div class="mb-10">
                        <label for="akun" class="required form-label">Nominal</label>
                       <input type="text" name="total_payment_per_month" class="form-control form-control-solid">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="buttonLoading"
                            x-text="buttonLoading ? 'Loading...' : 'Save'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
