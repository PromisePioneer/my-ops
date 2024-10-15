@extends('layouts.template')
@section('page-title', 'Detail Riwayat Absensi')
@section('content')
    <div x-data="attendancesSummaryDetail()">
        @include('pages.manage-users.user.modal.import')
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-300px mb-10">
                <div class="card card-flush">
                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="mb-0">Filter</h2>
                        </div>
                    </div>
                    <form id="form-filter" @submit.prevent="filter()">
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column text-gray-600">
                                <div class="d-flex align-items-center py-2">
                                    <input type="date" name="start_date" id="start_date"
                                           class="form-control form-control-solid date"
                                           placeholder="Tanggal awal">
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <input type="date" name="end_date" id="end_date"
                                           class="form-control form-control-solid date"
                                           placeholder="Tanggal akhir">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pt-4 text-end">
                            <button type="submit" class="btn btn-light btn-active-primary btn-sm">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex-lg-row-fluid ms-lg-10">
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-header pt-5 mb-4">
                        <div class="card-title">
                            <a href="{{ url('adms/attendances-summary/') }}"
                               class="btn btn-light btn-light-danger btn-sm mx-1">
                                <i class="bi bi-backspace"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-bordered fs-6"
                                   id="kt_roles_view_table">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Clock In</th>
                                    <th class="text-center">Clock Out</th>
                                    <th class="text-center">Terlambat</th>
                                    <th class="text-center">Jam Kerja</th>
                                </tr>
                                </thead>
                                <tbody class="fw-bold">
                                <template x-if="isLoading">
                                    <tr>
                                        <td colspan="5">
                                            <div style="text-align: center;">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!isLoading && attendancesSummaryRecords.length === 0">
                                    <tr>
                                        <td colspan="9">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                </template>
                                <template x-for="(attendance, index) in attendancesSummaryRecords" :key="index">
                                    <tr>
                                        <td class="text-center" x-text="attendance.date_period"></td>
                                        <td class="text-center" x-text="attendance.clock_in"></td>
                                        <td class="text-center" x-text="attendance.clock_out"></td>
                                        <td class="text-center" x-text="attendance.late"></td>
                                        <td class="text-center" x-text="attendance.work_time"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.toast')
    </div>
@endsection

@push('script')
    <script>
        $('.date').flatpickr();

        function attendancesSummaryDetail() {
            return {
                isLoading: false,
                attendancesSummaryRecords: [],
                id: "{{ $user->id }}",
                async init() {
                    await this.getAttendanceSummaryRecords();
                },
                async getAttendanceSummaryRecords() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/data/${this.id}`);
                    this.attendancesSummaryRecords = resp.data
                },
                async filter() {
                    const startDate = document.getElementById('start_date')?.value ?? '';
                    const endDate = document.getElementById('end_date')?.value ?? '';
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/adms/attendances-summary/detail/filter/${this.id}`, {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                            }
                        });
                        this.attendancesSummaryRecords = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush