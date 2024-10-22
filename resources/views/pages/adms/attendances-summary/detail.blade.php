@extends('layouts.template')
@section('page-title', 'Detail Riwayat Absensi')
@section('content')
    <div x-data="attendancesSummaryDetail()">
        @include('pages.adms.attendances-summary.modal.correction')
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
                        <div class="card-toolbar">
                            <h6>({{ $user->nip }}) {{ $user->name }}</h6>
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
                                    <th class="text-center">Action</th>
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
                                        <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                        <td class="text-center" x-text="attendance.clock_in"></td>
                                        <td class="text-center" x-text="attendance.clock_out"></td>
                                        <td class="text-center" x-text="attendance.late"></td>
                                        <td class="text-center" x-text="attendance.work_time"></td>
                                        <td class="text-center">
                                            <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modal-attendance-correction"
                                                    @click="correction(attendance.date_period)">
                                                <i class="ki-duotone ki-setting-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
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
        </div>
        @include('components.toast')
    </div>
@endsection

@push('script')
    <script>
        $('.date').flatpickr();

        function attendancesSummaryDetail() {
            return {
                buttonLoading: false,
                isLoading: false,
                attendancesSummaryRecords: [],
                id: "{{ $user->id }}",
                correctionVal: null,
                formCorrection: document.getElementById('form-attendance-correction'),
                modalCorrection: new bootstrap.Modal(document.getElementById('modal-attendance-correction')),
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
                },
                async correction(datePeriod) {
                    const resp = await axios.get(`/adms/attendances-summary/detail/correction/${datePeriod}`);
                    this.correctionVal = resp.data;
                    console.log(this.correctionVal);
                },
                formatDate(val) {
                    const [year, month, day] = val.split('-');
                    const date = new Date(year, month, day);
                    let formatYear = new Intl.DateTimeFormat('en', {year: 'numeric'}).format(date);
                    let formatMonth = new Intl.DateTimeFormat('en', {month: 'short'}).format(date);
                    let formatDay = new Intl.DateTimeFormat('en', {day: '2-digit'}).format(date);

                    return `${formatDay}/${formatMonth}/${formatYear}`
                },
                async saveCorrection(datePeriod) {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/attendances-summary/detail/correction/save/${this.id}/${datePeriod}`, new FormData(this.formCorrection))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCorrection.reset();
                        this.modalCorrection.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>
@endpush