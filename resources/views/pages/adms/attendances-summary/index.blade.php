@extends('layouts.template')
@section('content')

    <div x-data="attendancesSummary()">
        @include('pages.adms.attendances-summary.modal.detail')
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
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">

                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama Karyawan</th>
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
                                    <td>
                                        <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-detail" @click="show(attendance.id)">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <template x-for="pagination in attendanceSummary.links">
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
                filterDateForm: document.getElementById('form-filter-date'),
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
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.attendanceSummary = resp.data
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
                async show(id) {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    const resp = await axios.get(`/adms/attendances-summary/detail/${id}`, {
                        params: {
                            start_date: startDate,
                            end_date: endDate,
                        },
                    });
                    this.attendanceSummaryDetail = resp.data
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
                }
                ,
                async searchData() {
                    this.isLoading = true;
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
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
                }
                ,
                formatDate(val) {
                    const [month, year] = val.split('-');
                    const date = new Date(year, month - 1, 1);
                    return `${this.getMonthName(date.getMonth())} ${date.getFullYear()}`;
                }
                ,
                async filter() {

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