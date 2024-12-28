@extends('layouts.template')
@section('content')
    <div x-data="attendancesSummary()">
        @include('pages.adms.attendances-summary.modal.get-attendance-data')
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title">Filter</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4 justify-content-center">
                    <div class="col-lg-4">
                        <label for="name" class="form-label">Cabang</label>
                        <select class="form-select form-select-solid form-select-sm branch-select2" name="branch_id"
                                id="branch_id">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="name" class="form-label">Jabatan</label>
                        <select class="form-select form-select-solid form-select-sm roles-select2" name="role_id"
                                id="role_id"></select>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <label for="name" class="form-label">Tanggal Awal</label>
                        <input type="date" class="form-control form-control-solid form-control-sm me-3 date"
                               id="start_dates"
                               name="start_date" placeholder="Tanggal awal">
                    </div>
                    <div class="col-lg-4">
                        <label for="name" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control form-control-solid form-control-sm me-3 date"
                               id="end_dates"
                               name="end_date" placeholder="Tanggal akhir">
                    </div>
                </div>
                <div class="float-end mt-10">
                    <button class="btn btn-light-primary btn-sm" @click="filter()">Filter</button>
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
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                        <button type="button" data-bs-toggle="modal"
                                data-bs-target="#modal-get-attendances-data"
                                class="btn btn-primary btn-sm"
                        >
                            Import Data Absen
                        </button>
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
                                    <td x-text="`${attendance.total_present}`"></td>
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
                buttonLoading: false,
                isLoading: false,
                attendanceSummary: null,
                startIndex: null,
                search: '',
                months: [],
                getAttendaceDataModal: new bootstrap.Modal(document.getElementById('modal-get-attendances-data')),
                attendanceSummaryDetail: [],
                async init() {
                    await this.getAttendanceSummary();
                    await this.getMonths();
                    await this.getBranchData();
                    await this.getRoleData();
                    await this.getDepartmentData();
                    await this.getFpDeviceData();
                },
                async getDepartmentData() {
                    $(".departments-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Departement",
                        ajax: {
                            url: '/adms/attendances-summary/department/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/adms/attendances-summary/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getRoleData() {
                    $(".roles-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Jabatan",
                        ajax: {
                            url: '/adms/attendances-summary/roles/data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async paginationEndPointForAttendanceSummaryDetail(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.attendanceSummaryDetail = resp.data
                    }
                },
                async paginationEndPointForAttendanceSummary(url) {
                    const startDate = document.getElementById('start_dates')?.value ?? '';
                    const endDate = document.getElementById('end_dates')?.value ?? '';

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
                    const startDate = document.getElementById('start_dates')?.value ?? '';
                    const endDate = document.getElementById('end_dates')?.value ?? '';

                    // console.log(startDate);

                    const branch_id = $('#branch_id').val();
                    const role_id = $('#role_id').val()
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/adms/attendances-summary/filter`, {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                                branch_id: branch_id,
                                role_id: role_id
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
                async additionalFilter() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/attendances-summary/additional-filter', {
                            params: {
                                department_id: department,
                                branch_id: branch_id,
                                role: role,
                            }
                        })

                        this.attendanceSummary = resp.data
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async filterDate() {
                    this.isLoading = true;
                    const startDate = document.getElementById('start_dates');
                    const endDate = document.getElementById('end_dates');
                    const department = $('#department_id').val();
                    const branch_id = $('#branch_id').val();
                    const role = $('#role_id').val();
                    try {
                        const resp = await axios.get('/adms/attendances-summary/filter-date', {
                            params: {
                                search: this.search,
                                start_date: startDate,
                                end_date: endDate,
                                department: department,
                                branch_id: branch_id,
                                role: role,
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
                    const startDate = document.getElementById('start_dates')?.value;
                    const endDate = document.getElementById('end_dates')?.value;
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
                async getAttendanceData() {
                    this.buttonLoading = true;
                    const serial_number = $('#serial_number').val();
                    const startDate = document.getElementById('start_date').value ?? '';
                    const endDate = document.getElementById('end_date').value ?? '';
                    try {
                        await axios.get('/iclock/getrequest', {
                            params: {
                                SN: serial_number,
                            },
                            headers: {
                                'startDate': startDate,
                                'endDate': endDate,
                            },
                        })
                    } catch (e) {
                        console.log(e)
                    } finally {
                        setTimeout(() => {
                            this.buttonLoading = false;
                        }, 12000);
                    }
                },
                async getFpDeviceData() {
                    $(".devices-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih mesin.',
                        ajax: {
                            url: '/adms/attendances-summary/get-fp-devices',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                }
            }
        }
    </script>
@endpush
