@extends('layouts.template')
@section('page-title', 'Data Kehadiran')
@section('content')

    <div x-data="attendanceData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Jam Kerja</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Waktu C/In</th>
                                <th class="min-w-125px">Terlambat</th>
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
                            <template x-if="!isLoading && attendanceLog.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(attendance, index) in attendanceLog.data" :key="index">
                                <tr>
                                    <td x-text="attendance.name"></td>
                                    <td x-text="attendance.work_time ?? 'Default'"></td>
                                    <td x-text="attendance.date"></td>
                                    <td>
                                        <template x-if="attendance.checkin_time === null">
                                            <span class="badge bg-danger">-</span>
                                        </template>
                                        <template x-if="attendance.checkin_time">
                                            <span class="badge bg-info" x-text="attendance.checkin_time"></span>
                                        </template>
                                    </td>
                                    <td>
                                        <span class="badge bg-info" x-text="`${attendance.late_checkin} menit`"></span>
                                    </td>
                                    <td>
                                        <template x-if="attendance.checkout_time === null">
                                            <span class="badge bg-danger">Belum Checkout</span>
                                        </template>
                                        <template x-if="attendance.checkout_time">
                                            <span class="badge bg-info" x-text="attendance.checkout_time"></span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script>
        function attendanceData() {
            return {
                isLoading: false,
                attendanceLog: [],
                async init() {
                    await this.getAttendanceLog();
                },
                async getAttendanceLog() {
                    const resp = await axios.get('/adms/attendances/data');
                    this.attendanceLog = resp.data;
                    this.startIndex = this.attendanceLog.from;
                },
                async nextPage() {
                    if (this.attendanceLog.next_page_url) {
                        const resp = await axios.get(`${this.attendanceLog.next_page_url}`);
                        this.attendanceLog = resp.data
                    }
                },
                async previousPage() {
                    if (this.attendanceLog.prev_page_url) {
                        const resp = await axios.get(`${this.attendanceLog.prev_page_url}`);
                        this.startIndex = this.attendanceLog.from
                        this.attendanceLog = resp.data
                    }
                },
            }
        }
    </script>
@endsection