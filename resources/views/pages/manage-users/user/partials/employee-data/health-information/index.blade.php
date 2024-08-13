<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">Informasi Kesehatan</h2>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#health-information-update-modal" @click="add()">
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
                        <td>Riwayat Penyakit</td>
                        <td>
                            <ol class="align-items-center">
                                <template x-for="(disease, index) in healthInformation.disease" :key="index">
                                    <li x-text="disease.name"></li>
                                </template>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>