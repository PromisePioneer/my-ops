<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Department</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.department?.name ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-6 fw-bold fs-5 text-muted">Tanggal Mulai Bekerja</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.join_date ?? '-'}`"></span>
    </div>
</div>
<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Gaji Pokok</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.fixed_salary ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">No.Rekening</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.bank_account_number ?? '-'}`"></span>
    </div>
</div>

<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">BPJS Kesehatan</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.bpjs_kes ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-6 fw-bold fs-5 text-muted">BPJS Ketenagakerjaan</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.bpjs_ket ?? '-'}`"></span>
    </div>
</div>
<div class="row mb-10">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Status Kontrak</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.contract_status ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Penempatan</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${jobInformation.placement?.name ?? '-'}`"></span>
    </div>
</div>


<div class="row mb-10">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">File</label>
        <template x-if="jobInformation.sk_file && jobInformation.contract_file">
            <a href="{{ url('/manage-users/users/job-information/view-file/' . $user->id)  }}"
               class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i>
            </a>
        </template>
    </div>
</div>
