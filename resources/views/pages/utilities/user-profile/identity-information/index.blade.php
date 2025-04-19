<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
         data-bs-target="#identityInformation" aria-expanded="true"
         aria-controls="identityInformation">
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">Informasi Identitas</h3>
        </div>
    </div>
    <div id="identityInformation" class="collapse show" style="">
        <div class="card-body border-top p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Tanggal Lahir</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.date_of_birth ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Tempat Lahir</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.place_of_birth ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Gender</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.gender ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Alamat</label>
                <div class="col-lg-8 fv-row">
                    <span class="fw-bold text-gray-800 fs-6"
                          x-text="`${identityInformation.home_address ?? '-'}`"></span>
                </div>
            </div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Foto KTP</label>
                <div class="col-lg-8 fv-row">
                    <img :src="getImageURL(identityInformation?.ktp_attachment ?? null)"
                         @click="$dispatch('lightbox', `${getImageURL(identityInformation?.ktp_attachment) ?? null}`)"
                         alt="Foto Karyawan" class="w-100"/>
                </div>
            </div>
        </div>
    </div>
</div>