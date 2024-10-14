<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                <table class="table align-middle fs-6 gy-5 table-bordered" id="kt_table_users">
                    <thead>
                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px text-center">Tanggal</th>
                        <th class="min-w-125px text-center">Waktu C/In</th>
                        <th class="min-w-125px text-center">Waktu C/Out</th>
                        <th class="min-w-125px text-center">Terlambat</th>
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
                    <template x-for="(attendance, index) in attendanceSummaryDetail" :key="index">
                        <tr>
                            <td class="text-center" x-text="attendance.date_period"></td>
                            <td class="text-center" x-text="attendance.clock_in"></td>
                            <td class="text-center" x-text="attendance.clock_out"></td>
                            <td class="text-center" x-text="`${attendance.late ?? ' '}`"></td>
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
