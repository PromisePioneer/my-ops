@extends('layouts.template')
@section('page-title', 'Detail Riwayat Absensi'. ' [' . $user->nip . '] '. $user->name)
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
                    <div class="card-header pt-6 mb-4">
                        <div class="card-title">
                            <a href="{{ url('adms/attendances-summary/') }}"
                               class="btn btn-light btn-light-danger btn-sm mx-1">
                                <i class="bi bi-backspace"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="card-toolbar">
                            <button class="btn btn-light-info btn-sm" @click="init()">
                                <i class="bi bi-arrow-clockwise"></i>
                                Reload
                            </button>
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
                                    <template x-if="Number(correctionPermission) === 1">
                                        <th class="text-center">Action</th>
                                    </template>
                                </tr>
                                </thead>
                                <template x-if="isLoading">
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td colspan="6">
                                            <div style="text-align: center;">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-if="!isLoading && attendancesSummaryRecords.length === 0">
                                    <tbody>
                                    <tr>
                                        <td colspan="9">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-for="(attendance, index) in attendancesSummaryRecords" :key="index">
                                    <tbody class="fw-bolder">
                                    <template x-if="attendance.leaves?.status === 'Cuti'">
                                        <tr class="bg-success text-center">
                                            <td x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="6">CUTI</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance.permission?.status === 'Izin'">
                                        <tr class="bg-danger text-white text-center">
                                            <td x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="6">IZIN</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance?.sick?.status === 'Sakit'">
                                        <tr class="bg-primary text-center">
                                            <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="6">SAKIT</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance.schedule === 'L'">
                                        <tr class="bg-warning text-center">
                                            <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="6">LIBUR</td>
                                        </tr>
                                    </template>

                                    <template x-if="attendance.attendanceManualRequest?.status === 'Pengecualian'">
                                        <tr class="text-white text-center" style="background-color: #0dcaf0">
                                            <td x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="6"
                                                x-text="`Pengecualian : ${attendance.attendanceManualRequest?.reason}`"></td>
                                        </tr>
                                    </template>
                                    <template
                                        x-if="!attendance?.leaves && !attendance?.permission && !attendance.sick && attendance.schedule === 'H' && !attendance.attendanceManualRequest">
                                        <tr>
                                            <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                            <td class="text-center" x-text="attendance.clock_in"></td>
                                            <td class="text-center" x-text="attendance.clock_out"></td>
                                            <td class="text-center" x-text="attendance.late"></td>
                                            <td class="text-center" x-text="attendance.work_time"></td>
                                            <template x-if="Number(correctionPermission) === 1">
                                                <td class="text-center">
                                                    <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#modal-attendance-correction"
                                                            @click="correction(attendance.date_period)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                    </tbody>
                                </template>
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
                correctionPermission: "{{ request()->user()->can('Koreksi Data Riwayat Absensi') }}",
                buttonLoading: false,
                isLoading: false,
                attendancesSummaryRecords: [],
                startDates: "{{ $startDate }}",
                endDates: "{{ $endDate }}",
                id: "{{ $user->id }}",
                correctionVal: null,
                runningCommands: [],
                formCorrection: document.getElementById('form-attendance-correction'),
                modalCorrection: new bootstrap.Modal(document.getElementById('modal-attendance-correction')),
                async init() {
                    await this.getAttendanceSummaryRecords();
                    await this.getWorkTimes();
                },
                async getAttendanceSummaryRecords() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/data/${this.id}/${this.startDates}/${this.endDates}`);
                    this.attendancesSummaryRecords = resp.data;
                },
                async filter() {
                    const startDate = document.getElementById('start_date')?.value ?? null;
                    const endDate = document.getElementById('end_date')?.value ?? null;
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/adms/attendances-summary/detail/filter/${this.id}`, {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                            }
                        });
                        this.attendancesSummaryRecords = resp.data;
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.isLoading = false;
                    }
                },
                async correction(datePeriod) {
                    const resp = await axios.get(`/adms/attendances-summary/detail/correction/${datePeriod}/${this.id}`);
                    this.correctionVal = resp.data;
                    await this.selectedWorkTime();
                },
                formatDate(val) {
                    const date = new Date(val);
                    return date.toLocaleDateString("id", {
                        weekday: "short",
                        year: "numeric",
                        month: "2-digit",
                        day: "numeric",
                    });
                },
                async selectedWorkTime() {
                    const selectedWorkTime = $('#selected-work-time');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-work-time/${this.correctionVal.work_time_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedWorkTime.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getWorkTimes() {
                    $(".work-times-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Jam Kerja",
                        ajax: {
                            url: '/select2/work-times-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveCorrection(datePeriod) {
                    this.buttonLoading = true;
                    const startDate = document.getElementById('start_date')?.value ?? null;
                    const endDate = document.getElementById('end_date')?.value ?? null;

                    try {
                        await axios.post(`/adms/attendances-summary/detail/correction/save/${this.id}/${datePeriod}`, new FormData(this.formCorrection)).then(async res => {
                            await showAlert('success', 'Data berhasil disimpan');
                            this.formCorrection.reset();
                            this.modalCorrection.hide();
                            if (startDate !== '' && endDate !== '') {
                                const resp = await axios.get(`/adms/attendances-summary/detail/filter/${this.id}`,
                                    {
                                    params: {
                                        start_date: startDate,
                                        end_date: endDate,
                                    }
                                });
                                this.attendancesSummaryRecords = resp.data;
                            } else {
                                await this.init();
                            }
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            };
        }
    </script>
@endpush
