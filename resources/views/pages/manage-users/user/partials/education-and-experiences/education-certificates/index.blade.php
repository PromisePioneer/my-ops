<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">Riwayat Sertifikat Atau Pelatihan</h2>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#education-certificate-create-modal" @click="add()">
                <i class="fas fa-edit"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-9 pt-4">
        <div class="tab-content">
            <div class="py-5 table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th>Organisasi Penerbit</th>
                        <th>Nama Sertifikat</th>
                        <th>Tahun</th>
                        <th>File</th>
                        <th>Actions</th>
                    </thead>
                    <tbody class="text-gray-600 fw-bold">
                    <template x-if="isLoading">
                        <tr>
                            <td colspan="9">
                                <div style="text-align: center;">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!isLoading && educationCertificates.length === 0">
                        <tr>
                            <td colspan="9">
                                <center>Data Tidak Ditemukan</center>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(educationCertificate,index) in educationCertificates"
                              :key="educationCertificate.id">
                        <tr>
                            <td class="d-flex align-items-center" x-text="educationCertificate.organization"></td>
                            <td x-text="educationCertificate.name"></td>
                            <td x-text="`${educationCertificate.year}`"></td>
                            <td>
                                <a :href="`/manage-users/education-certificates/view-file/${educationCertificate.id}`"
                                   class="btn btn-info btn-sm"
                                   target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                        @click="educationCertificatesEdit(educationCertificate.id)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#education-certificate-edit-modal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        @click="educationCertificatesDestroy(educationCertificate.id)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>