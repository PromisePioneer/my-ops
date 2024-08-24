@php use Carbon\Carbon; @endphp
<div class="modal fade" tabindex="-1" id="modal-attendances-summary-detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    x-text="`(${usersDetail?.summary_data?.nik}) ${usersDetail?.summary_data?.name}`"></h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="bi bi-x-circle fs-2"></i>
                    </span>
                </div>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <h6>
                        Tanggal
                        : {{ Carbon::parse($year . '-' . $month . '-'. '01')->firstOfMonth()->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}
                        - {{ Carbon::parse($year . '-' . $month . '-'. '01')->endOfMonth()->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Tanggal</th>
                            <th class="min-w-125px">Check in</th>
                            <th class="min-w-125px">Check out</th>
                        </thead>
                        <tbody class="fw-bold">
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
                        <template x-if="!isLoading && usersDetail?.data?.length === 0">
                            <tr>
                                <td colspan="9">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                        </template>
                        <template x-for="users in usersDetail?.data?.data" :key="index">
                            <tr>
                                <td x-text="users.date"></td>
                                <td>
                                    <span class="badge bg-info" x-text="users.checkin_time"></span>
                                </td>
                                <td>
                                    <span class="badge bg-danger"
                                          x-text="users.checkout_time ?? 'Belum C/Out'"></span>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPageForUserSummary()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPageForUserSummary()">Next</button>
                        </li>
                    </ul>
                </div>

                <div class="mt-4">
                    <h6>
                        Total Kehadiran : <span x-text="usersDetail?.total_present"></span>
                    </h6>
                    <h6>
                        Total Terlambat : <span x-text="usersDetail?.summary_data?.totalMinutesLate"></span>
                    </h6>
                    <h6>
                        Cuti Dalam Bulan Ini : -
                    </h6>
                </div>

            </div>
        </div>
    </div>
</div>
