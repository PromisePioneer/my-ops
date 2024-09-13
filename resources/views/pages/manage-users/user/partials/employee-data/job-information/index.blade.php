<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <div class="card-title flex-column">
            <h2 class="mb-1">Informasi Pekerjaan</h2>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#job-information-update-modal" @click="add()">
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
                        <td>Gaji Pokok</td>
                        <td x-text="`Rp. ${jobInformation.fixed_salary ?? ''}`"></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Jabatan</td>
                        <td x-text="`Rp. ${jobInformation.position_allowance ?? ''}`"></td>
                    </tr>
                    <tr>
                        <td>Tunjangan Makan</td>
                        <td x-text="`Rp. ${jobInformation.meal_allowance ?? ''}`"></td>
                    </tr>
                    <tr>
                        <td>Status Kontrak</td>
                        <td x-text="jobInformation.contract_status ?? ''"></td>
                    </tr>
                    <tr>
                        <td>Nomor Rekening</td>
                        <td x-text="jobInformation.bank_account_number ?? ''"></td>
                    </tr>
                    <tr>
                        <td>BPJS Kesehatan</td>
                        <td x-text="jobInformation.bpjs_kes ?? ''"></td>
                    </tr>
                    <template x-if="jobInformation.bpjs_kes === 'ya'">
                        <tr>
                            <td>Nomor KIS</td>
                            <td x-text="jobInformation.no_kis ?? ''"></td>
                        </tr>
                    </template>
                    <tr>
                        <td>BPJS Ketenagakerjaan</td>
                        <td x-text="jobInformation.bpjs_ket ?? ''"></td>
                    </tr>
                    <template x-if="jobInformation.bpjs_ket === 'ya'">
                        <tr>
                            <td>Nomor KPJ</td>
                            <td x-text="jobInformation.no_kpj ?? ''"></td>
                        </tr>
                    </template>
                    <tr>
                        <td>Dokumen SK & Kontrak</td>
                        <template x-if="jobInformation.contract_end_date">
                            <td>
                                <a :href="`/manage-users/job-information/contract-file/${userId}`"
                                   class="btn btn-danger btn-sm">
                                    <i class="bi bi-file-pdf-fill"></i>
                                </a>
                            </td>
                        </template>
                        <template x-if="!jobInformation.contract_end_date">
                            <td>
                                <button class="btn btn-danger btn-sm" disabled>
                                    <i class="bi bi-file-pdf-fill"></i>
                                </button>
                            </td>
                        </template>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>