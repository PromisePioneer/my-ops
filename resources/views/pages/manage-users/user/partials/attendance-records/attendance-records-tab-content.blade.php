<div class="card card-flush mb-6 mb-xl-9">
    <div class="card-header mt-6">
        <form id="form-filter-attendance-records" @submit.prevent="filterAttendanceRecords()">
            <div class="card-title flex-row">
                <input type="date" name="start_date" id="start_date_att_record"
                       class="form-control form-control-solid date me-4"
                       placeholder="Tanggal awal">
                <input type="date" name="end_date" id="end_date_att_record"
                       class="form-control form-control-solid date me-4"
                       placeholder="Tanggal akhir ">
                <button type="submit" class="btn btn-light-primary btn-sm">Filter</button>
            </div>
        </form>
    </div>

    <div class="card-body d-flex flex-column">
        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered" id="kt_table_users">
            <thead>
            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                <th class="min-w-125px">Tanggal</th>
                <th class="min-w-125px">Clock in</th>
                <th class="min-w-125px">Clock out</th>
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
            <template x-if="!isLoading && attendanceRecords.length === 0">
                <tr>
                    <td colspan="9">
                        <center>Data Tidak Ditemukan</center>
                    </td>
                </tr>
            </template>
            <template x-for="(attendance, index) in attendanceRecords" :key="index">
                <tr>
                    <td x-text="attendance.date_period"></td>
                    <td x-text="attendance.clock_in"></td>
                    <td x-text="attendance.clock_out"></td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</div>