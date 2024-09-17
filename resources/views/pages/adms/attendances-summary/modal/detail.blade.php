<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Karyawan</h5>
                <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                     aria-label="Close">
                    <span class="svg-icon svg-icon-2x">
                        <i class="ki-duotone ki-technology-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Tanggal</th>
                        <th class="min-w-125px">Waktu C/In</th>
                        <th class="min-w-125px">Waktu C/Out</th>
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
                    <template x-if="!isLoading && attendanceSummaryDetail.data?.length === 0">
                        <tr>
                            <td colspan="9">
                                <center>Data Tidak Ditemukan</center>
                            </td>
                        </tr>
                    </template>
                    <template x-for="(attendance, index) in attendanceSummaryDetail.data" :key="index">
                        <tr>
                            <td x-text="attendance.date"></td>
                            <td>
                                <span :class="attendance.check_in_timestamp
                                ? 'badge bg-success text-white fs-7'
                                : 'badge bg-danger text-white fs-7'"
                                      x-text="attendance.check_in_timestamp ?? 'Tidak Checkin'"></span>
                            </td>
                            <td>
                               <span :class="attendance.check_out_timestamp
                                ? 'badge bg-success text-white fs-7'
                                : 'badge bg-danger text-white fs-7'"
                                     x-text="attendance.check_out_timestamp ?? 'Tidak Checkin'"></span>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <ul class="pagination float-end mb-4 mt-4">
                    <template x-for="pagination in attendanceSummaryDetail.links">
                        <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                            <button class="page-link"
                                    @click="paginationEndPointForAttendanceSummaryDetail(pagination.url)"
                                    x-html="pagination.label">
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
</div>
