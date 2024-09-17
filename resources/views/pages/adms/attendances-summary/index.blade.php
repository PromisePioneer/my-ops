@extends('layouts.template')
@section('content')

    <div x-data="attendancesSummary()">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <form id="form-filter-date" @submit.prevent="filterDate()">
                        <div class="row col-md-6 align-items-center">
                            <div class="col-md-4">
                                <input type="date" name="start_date" id="start_date"
                                       class="form-control form-control-solid date"
                                       placeholder="Tanggal Awal">
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="end_date" id="end_date"
                                       class="form-control form-control-solid date"
                                       placeholder="Tanggal Akhir">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-light-primary btn-sm">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center justify-content-center position-relative my-1">
                        <select name="" id="" class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Pilih Periode">
                            <option></option>
                            <template x-for="month in months" :key="month.value">
                                <option :value="month.value" x-text="month.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama Karyawan</th>
                                <th class="min-w-125px">Hadir</th>
                                <th class="min-w-125px">Terlambat</th>
                                <th class="min-w-125px">Cuti</th>
                                <th class="min-w-125px">Alpha</th>
                                <th class="min-w-125px">Sakit</th>
                                <th class="min-w-125px">Izin</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && attendanceSummary?.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(attendance, index) in attendanceSummary?.data" :key="index">
                                <tr>
                                    <td x-text="`(${attendance.user_nip}) ${attendance.user_name}`"></td>
                                    <td x-text="attendance.total_present"></td>
                                    p[kasdkasd
                                    <td x-text="`${attendance.total_late_in_minutes} Menit`"></td>
                                    <td x-text="`${attendance.total_leaves} Hari`"></td>
                                    <td x-text="`${attendance.total_absent} Hari`"></td>
                                    <td x-text="`${attendance.total_sick} Hari`"></td>
                                    <td x-text="`${attendance.total_permission} Hari`"></td>
                                    <td x-text="attendance.work_time?.name ?? 'Default'"></td>
                                    <td>
                                        <button class="btn btn-light-primary btn-sm">
                                            E
                                        </button>
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

@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function attendancesSummary() {
            return {
                isLoading: false,
                attendanceSummary: null,
                startIndex: null,
                search: '',
                months: [],
                filterDateForm: document.getElementById('form-filter-date'),
                async init() {
                    await this.getAttendanceSummary();
                    await this.getMonths();
                },
                getMonths() {
                    this.months = [
                        {"value": 1, "name": "Januari"},
                        {"value": 2, "name": "Februari"},
                        {"value": 3, "name": "Maret"},
                        {"value": 4, "name": "April"},
                        {"value": 5, "name": "Mei"},
                        {"value": 6, "name": "Juni"},
                        {"value": 7, "name": "Juli"},
                        {"value": 8, "name": "Agustus"},
                        {"value": 9, "name": "September"},
                        {"value": 10, "name": "Oktober"},
                        {"value": 11, "name": "November"},
                        {"value": 12, "name": "Desember"}
                    ]
                },
                async getAttendanceSummary() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/attendances-summary/data');
                        this.attendanceSummary = resp.data;
                        this.startIndex = this.attendanceSummary.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filterDate() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.post('/adms/attendances-summary/filter-date', new FormData(this.filterDateForm));
                        this.attendanceSummary = resp.data;
                        this.startIndex = this.attendanceSummary.from;
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.isLoading = false;
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    console.log(endDate);
                    try {
                        const response = await axios.get('/adms/attendances-summary/search', {
                            params: {
                                search: this.search,
                                start_date: startDate,
                                end_date: endDate,
                            },
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.attendanceSummary = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                formatDate(val) {
                    const [month, year] = val.split('-');
                    const date = new Date(year, month - 1, 1);
                    return `${this.getMonthName(date.getMonth())} ${date.getFullYear()}`;
                },
                async filter() {

                },
                getMonthName(monthIndex) {
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "December"
                    ];
                    return monthNames[monthIndex];
                },
                async nextPage() {
                    if (this.attendanceSummary.next_page_url) {
                        const resp = await axios.get(`${this.attendanceSummary.next_page_url}`);
                        this.startIndex = this.attendanceSummary.from
                        this.attendanceSummary = resp.data
                    }
                },
                async previousPage() {
                    if (this.attendanceSummary.prev_page_url) {
                        const resp = await axios.get(`${this.attendanceSummary.prev_page_url}`);
                        this.startIndex = this.attendanceSummary.from
                        this.attendanceSummary = resp.data
                    }
                },
            }
        }
    </script>
@endpush