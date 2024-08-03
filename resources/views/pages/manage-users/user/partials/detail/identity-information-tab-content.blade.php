<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">No. Identitas</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.nik ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Tanggal Lahir</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.date_of_birth ?? '-'}`"></span>
    </div>
</div>
<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Tempat Lahir</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.place_of_birth ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Jenis Kelamin</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.gender ?? '-'}`"></span>
    </div>
</div>
<div class="row mb-7">
    <div class="col-md-6">
        <label class="col-lg-4 fw-bold fs-5 text-muted">Alamat</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.home_address ?? '-'}`"></span>
    </div>
    <div class="col-md-6">
        <label class="col-lg-6 fw-bold fs-5 text-muted">Status Perkawinan</label>
        <span class="fw-bolder fs-5 text-gray-800"
              x-text="`${identityInformation.married_status ?? '-'}`"></span>
    </div>

</div>
<div class="row mb-7">
    <label class="col-lg-4 fw-bold fs-5 text-muted">Foto KTP</label>
</div>
<div class="col-md-6 mb-4">
    <img :src="getImageURL(identityInformation.ktp_attachment ?? null)"
         @click="$dispatch('lightbox', `${getImageURL(identityInformation.ktp_attachment)}`)"
         height="100"/>
</div>
