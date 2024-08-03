<div class="modal fade" id="payment-complete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)"
                                  fill="black"/>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form class="form" id="paymentCompleteForm" @submit.prevent="savePaymentComplete()">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">Konfirmasi Pembayaran</h1>
                        <div class="text-muted fw-bold fs-5">Jika invoice sudah terbayar mohon konfirmasi pembayaran
                            <a href="{{ url('/transaction/invoice/detail/' . $invoice->id) }}"
                               class="fw-bolder link-primary">Invoice</a>.
                        </div>
                    </div>
                    <div class="d-flex flex-stack mb-8">
                        <div class="me-5">
                            <label class="fs-6 fw-bold">Masukkan PPH 23</label>
                            <div class="fs-7 fw-bold text-muted">Masukkan jika ada Pph 23 yang nanti akan muncul di
                                jurnal entry
                            </div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="pph23" x-model="openPph23Form"/>
                            <span class="form-check-label fw-bold text-muted">Approve</span>
                        </label>

                    </div>

                    <div class="mb-4" x-show="openPph23Form">
                        <label for="name" class="required form-label">Pph 23</label>
                        <input type="number" id="pph23_form" :name="`${openPph23Form ? 'pph23_form' : ''}`"
                               class="form-control form-control-solid" placeholder="Silahkan Input nominal"/>
                    </div>

                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light btn-sm me-3">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
