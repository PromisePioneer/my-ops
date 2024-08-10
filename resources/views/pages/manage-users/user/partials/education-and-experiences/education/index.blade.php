<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">Riwayat Pendidikan</h2>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#education-update-modal" @click="add()">
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
                        <td>Tingkat Pendidikan</td>
                        <td x-text="education.level ?? '-'"></td>
                    </tr>
                    <tr>
                        <td>Institusi Pendidikan</td>
                        <td x-text="education.institution ?? '-'"></td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td x-text="education.major ?? '-'"></td>
                    </tr>
                    <tr>
                        <td>Tahun lulus</td>
                        <td x-text="education.graduation_year ?? '-'"></td>
                    </tr>
                    <tr>
                        <td>Ijazah</td>
                        <td>
                            <a :href="`/manage-users/educations/view-file/${userId}`" class="btn btn-primary btn-sm"
                               :disabled="!education.certificate_of_graduation">
                                File
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>