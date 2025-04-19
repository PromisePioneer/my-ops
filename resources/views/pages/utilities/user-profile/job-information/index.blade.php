<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
         data-bs-target="#jobInformation" aria-expanded="true"
         aria-controls="jobInformation">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Informasi Pekerjaan</h3>
        </div>
    </div>
    <div id="jobInformation" class="collapse show" style="">
        <div class="card-body border-top p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Gaji Pokok</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.fixed_salary ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Status Kontrak</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.contract_status ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">No. Rekening</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${jobInformation.bank_account_number ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">BPJS Ketenagakerjaan</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6 text-uppercase"
                          x-text="`${jobInformation.no_kpj ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">BPJS Kesehatan</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6 text-uppercase"
                          x-text="`${jobInformation.no_kis ?? '-'}`"></span>
                </div>
            </div>
        </div>
    </div>
</div>