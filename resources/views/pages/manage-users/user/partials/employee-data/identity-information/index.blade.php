<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">Informasi Identitas</h2>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#identity-information-update-modal" @click="add()">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-9 pt-4">
        <div class="tab-content">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                    <tbody class="fs-6 fw-bold text-gray-600">
                    <tr>
                        <td>Nomor Identitas</td>
                        <td x-text="identityInformation.nik ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Tempat Dan Tanggal Lahir</td>
                        <td x-text="`${identityInformation.place_of_birth ?? ''}, ${identityInformation.date_of_birth ?? ''}`"></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td x-text="identityInformation.gender ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Nomor Telepon</td>
                        <td x-text="identityInformation.phone_number ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Status Perkawinan</td>
                        <td x-text="identityInformation.married_status ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td x-text="identityInformation.home_address ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Foto Identitas</td>
                        <td>
                            <img :src="getImageURL(identityInformation.ktp_attachment ?? null)"
                                 @click="$dispatch('lightbox', `${getImageURL(identityInformation.ktp_attachment) ?? null}`)"
                                 height="100"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>