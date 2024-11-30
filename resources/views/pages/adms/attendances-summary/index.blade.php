@extends('layouts.template')
@section('content')

    <div x-data="attendancesSummary()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center justify-content-center">
                        <input type="date" class="form-control form-control-solid me-3 date" id="start_date"
                               name="start_date" placeholder="Tanggal awal">
                        <input type="date" class="form-control form-control-solid me-3 date" id="end_date"
                               name="end_date" placeholder="Tanggal akhir">
                        <button class="btn btn-light-primary btn-sm" @click="filter()">Filter</button>
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
                                <th class="min-w-125px">Jabatan</th>
                                <th class="min-w-125px">Terlambat</th>
                                <th class="min-w-125px">Total Hadir</th>
                                <th class="min-w-125px">Tidak CheckIn</th>
                                <th class="min-w-125px">Tidak Checkout</th>
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
                                    <td>
                                        <a :href="`/manage-users/users/detail/${attendance.id}`"
                                           x-text="`(${attendance.user_nip}) ${attendance.user_name}`"></a>
                                    </td>
                                    <td x-text="`${attendance.role}`"></td>
                                    <td x-text="`${attendance.total_minutes_late} Menit`"></td>
                                    <td x-text="`${attendance.total_present} Hari`"></td>
                                    <td x-text="`${attendance.total_not_check_in}`"></td>
                                    <td x-text="`${attendance.total_not_check_out}`"></td>
                                    <td>
                                        <a :href="`/adms/attendances-summary/detail/${attendance.id}`"
                                           class="btn btn-light-primary btn-sm">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in attendanceSummary?.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link"
                                        @click="paginationEndPointForAttendanceSummary(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
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
                attendanceSummaryDetail: [],
                async init() {
                    await this.getAttendanceSummary();
                    await this.getMonths();
                },
                async paginationEndPointForAttendanceSummaryDetail(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.attendanceSummaryDetail = resp.data
                    }
                },
                async paginationEndPointForAttendanceSummary(url) {
                    const startDate = document.getElementById('start_date')?.value ?? '';
                    const endDate = document.getElementById('end_date')?.value ?? '';


                    if (url) {
                        const resp = await axios.get(`${url}`, {
                            params: {
                                start_date: startDate,
                                end_date: endDate
                            }
                        });
                        this.attendanceSummary = resp.data
                    }
                },
                async filter() {
                    const startDate = document.getElementById('start_date')?.value ?? '';
                    const endDate = document.getElementById('end_date')?.value ?? '';
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/adms/attendances-summary/filter`, {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                            }
                        });
                        this.attendanceSummary = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
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
                    const startDate = document.getElementById('start_date');
                    const endDate = document.getElementById('end_date');
                    try {
                        const resp = await axios.get('/adms/attendances-summary/filter-date', {
                            params: {
                                search: this.search,
                                start_date: startDate,
                                end_date: endDate,
                            }
                        });
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

                    const startDate = document.getElementById('start_date')?.value;
                    const endDate = document.getElementById('end_date')?.value;
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
                getMonthName(monthIndex) {
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "December"
                    ];
                    return monthNames[monthIndex];
                },
            }
        }
    </script>
@endpush
