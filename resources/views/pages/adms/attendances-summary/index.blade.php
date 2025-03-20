@extends('layouts.template')
@section('content')
    <div x-data="attendancesSummary()">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title">Filter</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4 justify-content-center">
                    <div class="col-lg-4">
                        @can('Filter Data Riwayat Absensi Berdasarkan Cabang')
                            <label for="name" class="form-label">Cabang</label>
                            <select class="form-select form-select-solid form-select-sm main-branches-select2"
                                    name="branch_id"
                                    id="branch_id">
                                <option></option>
                            </select>
                        @endcan
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
                        <input type="date" x-model="startDates"
                               class="form-control form-control-solid form-control-sm me-3 date"
                               id="start_dates"
                               name="start_date" placeholder="Tanggal awal">
                    </div>
                    <div class="col-lg-4">
                        <label for="name" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control form-control-solid form-control-sm me-3 date"
                               id="end_dates" x-model="endDates"
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
{{--                <div class="card-toolbar">--}}
{{--                    <div class="d-flex justify-content-end align-items-center" data-kt-user-table-toolbar="base">--}}
{{--                        <a href="{{ url('adms/attendances-summary/attendance-manual-requests') }}"--}}
{{--                           class="btn btn-light-info btn-sm">--}}
{{--                            Pengajuan Absensi (Manual)--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5"
                               id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="min-w-125px">Nama Karyawan</th>
                                <th>Detail</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class=" fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="11">
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
                                <tr class="text-center">
                                    <td>
                                        <div class="d-flex align-self-center justify-content-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="#" @click="openImage(attendance.profile_pic)">
                                                    <div class="symbol-label">
                                                        <img :src="getImageURL(attendance.profile_pic ?? null)"
                                                             alt="Foto Karyawan" class="w-100"/>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#"
                                                   class="text-gray-800 text-hover-primary mb-1">
                                                    <span x-text="attendance.user_name"></span>
                                                </a>
                                                <span class="badge badge-light-info fw-bolder fs-8"
                                                      x-text="attendance?.role ?? ''">
                                                    </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="table-responsive">
                                                <table class="table table-row-bordered">
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Terlambat (Menit)</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_minutes_late}`"></td>
                                                    </tr>
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Total Hadir (Hari)</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_present}`"></td>
                                                    </tr>
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Tdk Checkin</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_not_check_in}`"></td>
                                                    </tr>
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Tdk Checkout</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_not_check_out}`"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-row-bordered">
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Cuti</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_leaves}`"></td>
                                                </tr>
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Izin</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_permission}`"></td>
                                                </tr>
                                                    <tr class="bg-gray-100 text-center">
                                                        <td class="min-w-125px">Sakit</td>
                                                        <td class="min-w-125px">:</td>
                                                        <td class="min-w-125px"
                                                            x-text="`${attendance.total_sick}`"></td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a :href="`/adms/attendances-summary/detail/${attendance.id}/${startDates}/${endDates}`"
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
                startDates: null,
                endDates: null,
                buttonLoading: false,
                isLoading: false,
                attendanceSummary: null,
                startIndex: null,
                search: '',
                months: [],
                attendanceSummaryDetail: [],
                async init() {
                    await this.getAttendanceSummary();
                    await this.getMonths();
                    await this.getMainBranches();
                    await this.getRoles();
                    await this.getDepartmentData();
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
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async getRoles() {
                    $(".roles-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Jabatan",
                        ajax: {
                            url: '/select2/roles-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async paginationEndPointForAttendanceSummary(url) {
                    const startDate = document.getElementById('start_dates')?.value ?? '';
                    const endDate = document.getElementById('end_dates')?.value ?? '';
                    const branchId = $('#branch_id').val();
                    const roleId = $('#role_id').val();

                    try {
                        if (url) {
                            this.attendanceSummary = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    start_date: startDate,
                                    end_date: endDate,
                                    branch_id: branchId,
                                    role_id: roleId
                                }
                            });
                            this.attendanceSummary = resp.data
                        }
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                async filter() {
                    const startDate = document.getElementById('start_dates')?.value ?? '';
                    const endDate = document.getElementById('end_dates')?.value ?? '';
                    const branch_id = $('#branch_id').val();
                    const role_id = $('#role_id').val()
                    try {
                        this.attendanceSummary = [];
                        this.isLoading = true;
                        const resp = await axios.get(`/adms/attendances-summary/filter`, {
                            params: {
                                search: this.search,
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
                    const startDate = document.getElementById('start_dates')?.value ?? '';
                    const endDate = document.getElementById('end_dates')?.value ?? '';
                    const branch_id = $('#branch_id').val();
                    const role_id = $('#role_id').val()
                    try {
                        const resp = await axios.get('/adms/attendances-summary/additional-filter', {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                                department_id: department,
                                branch_id: branch_id,
                                role_id: role_id,
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
                    const role_id = $('#role_id').val();
                    try {
                        const resp = await axios.get('/adms/attendances-summary/filter-date', {
                            params: {
                                search: this.search,
                                start_date: startDate,
                                end_date: endDate,
                                department: department,
                                branch_id: branch_id,
                                role: role_id,
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
                    const startDate = document.getElementById('start_dates')?.value;
                    const endDate = document.getElementById('end_dates')?.value;
                    const department = $('#department_id').val();
                    const branch_id = $('#branch_id').val();
                    const role_id = $('#role_id').val();
                    try {
                        this.attendanceSummary = [];
                        this.isLoading = true;
                        const response = await axios.get('/adms/attendances-summary/search', {
                            params: {
                                search: this.search,
                                start_date: startDate,
                                end_date: endDate,
                                department: department,
                                branch_id: branch_id,
                                role_id: role_id
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
                getImageURL(imagePath) {
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        return "{{ asset('') }}" + placeholders;
                    }
                    return imagePath ? "{{ Storage::url('') }}" + imagePath : '';
                },
                openImage(imagePath) {
                    const lightbox = new FsLightbox();
                    console.log(lightbox);
                    if (imagePath === null) {
                        const placeholders = 'assets/media/avatars/blank.png'
                        const image = "{{ asset('') }}" + placeholders
                        lightbox.props.sources = [image, image];
                        lightbox.open();
                    } else {
                        const image = "{{ Storage::url('') }}" + imagePath;
                        lightbox.props.sources = [image];
                        lightbox.open();
                    }
                },
            }
        }
    </script>
@endpush
