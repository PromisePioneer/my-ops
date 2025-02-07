@extends('layouts.template')
@section('page-title', 'Detail Riwayat Absensi'. ' [' . $user->nip . '] '. $user->name)
@section('content')
    <div x-data="attendancesSummaryDetail()">
        @include('pages.adms.attendances-summary.modal.correction')
        @include('pages.adms.attendances-summary.modal.query-data')
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
                                        <td colspan="5">
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
                                            <td colspan="5">CUTI</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance.permission?.status === 'Izin'">
                                        <tr class="bg-danger text-white text-center">
                                            <td x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="5">IZIN</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance?.sick?.status === 'Sakit'">
                                        <tr class="bg-primary text-center">
                                            <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="5">SAKIT</td>
                                        </tr>
                                    </template>
                                    <template x-if="attendance.schedule === 'L'">
                                        <tr class="bg-warning text-center">
                                            <td class="text-center" x-text="formatDate(attendance.date_period)"></td>
                                            <td colspan="5">LIBUR</td>
                                        </tr>
                                    </template>
                                    <template
                                            x-if="attendance.schedule === 'H' && !attendance.leaves && !attendance.sick && !attendance.permission || attendance.schedule === null">
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
                            <a href="{{ url('adms/attendances-summary/') }}"
                               class="btn btn-light btn-light-danger btn-sm mx-1">
                                <i class="bi bi-backspace"></i>
                                Kembali
                            </a>
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
                modalQueryData: new bootstrap.Modal(document.getElementById('modal-attendance-query-data')),
                formQueryData: document.getElementById('form-attendance-query-data'),
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
                    await this.getWorkTimeData();
                    await this.getFpDeviceData();
                    await this.getRunningCommands();
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
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getRunningCommands() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/running-commands/${this.id}`);
                    this.runningCommands = resp.data;
                },
                async deactivateRunningCommands() {
                    showConfirmModal("Anda yakin?", "Tarik data akan dihentikan.", "Ya, Hentikan!", async () => {
                        try {
                            await axios.get(`/adms/attendances-summary/detail/deactivate-active-commands/${this.id}`);
                            await showAlert('success', 'Tarik Data Sukses Dihentikan');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
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
                    const selectedWorkTime = $('#selectedWorkTime');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/adms/attendances-summary/detail/correction/work-time/selected/${this.correctionVal.work_time_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedWorkTime.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async getWorkTimeData() {
                    $(".work-time-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Jam Kerja",
                        ajax: {
                            url: '/adms/attendances-summary/detail/correction/work-time/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getFpDeviceData() {
                    $(".devices-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Mesin",
                        ajax: {
                            url: '/adms/attendances-summary/detail/get-fp-devices',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async saveCommand() {
                    const deviceSN = $('#device-id').text();
                    const getSN = deviceSN.split('-')[0].trim();
                    this.buttonLoading = true;
                    try {
                        await axios.get('/iclock/getrequest', {
                            params: {
                                SN: getSN
                            }
                        })
                        await axios.post(`/adms/attendances-summary/detail/query-data/${this.id}`, new FormData(this.formQueryData));
                        await showAlert('success', 'Data berhasil disimpan');
                        this.formQueryData.reset();
                        this.modalQueryData.hide();
                        this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]));
                    } finally {
                        this.buttonLoading = false;
                    }
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
