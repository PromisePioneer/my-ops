@extends('layouts.template')
@section('content')
    <div x-data="attendancesSummary()">
        @include('pages.adms.attendances-summary.drawer.filter')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <button id="kt_drawer_example_basic_button" class="btn btn-info btn-sm">Filter</button>
                </div>
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm mb-0">
                                                    <tr>
                                                        <td>Terlambat (Menit)</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_minutes_late"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Hadir (Hari)</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_present"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tdk Checkin</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_not_check_in"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tdk Checkout</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_not_check_out"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <table class="table table-sm text-center mb-0">
                                                    <tr>
                                                        <td>Cuti</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_leaves"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Izin</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_permission"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sakit</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_sick"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cuti Penting</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_important_leaves"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alfa</td>
                                                        <td>:</td>
                                                        <td x-text="attendance.total_absent"></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a :href="getDetailUrl(attendance.id)" class="btn btn-light-primary btn-sm">
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

        const startDate = "{{ $startDate }}";
        const endDate = "{{ $endDate }}";


        $(document).ready(function () {
            flatpickr(".date-picker", {
                mode: "range",
                dateFormat: "d/m/Y",
                defaultDate: [`${startDate}`, `${endDate}`],
            });
        });

        function attendancesSummary() {
            return {
                buttonLoading: false,
                isLoading: false,
                attendanceSummary: null,
                startIndex: null,
                search: '',
                months: [],
                date: document.getElementById('date')?.value,
                attendanceSummaryDetail: [],
                async init() {
                    await this.getAttendanceSummary();
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
                    try {
                        if (url) {
                            this.attendanceSummary = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                    end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                    branch_id: $('#branch_id').val(),
                                    role_id: $('#role_id').val()
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
                    try {
                        this.attendanceSummary = [];
                        this.isLoading = true;
                        const resp = await axios.get(`/adms/attendances-summary/filter`, {
                            params: {
                                search: this.search,
                                start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                branch_id: $('#branch_id').val(),
                                role_id: $('#role_id').val()
                            }
                        });
                        this.attendanceSummary = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
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
                async searchData() {
                    try {
                        this.attendanceSummary = [];
                        this.isLoading = true;
                        const response = await axios.get('/adms/attendances-summary/search', {
                            params: {
                                search: this.search,
                                start_date: this.formatDate(this.date.split('to').map(part => part.trim())[0]),
                                end_date: this.formatDate(this.date.split('to').map(part => part.trim())[1]),
                                branch_id: $('#branch_id').val(),
                                role_id: $('#role_id').val()
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
                formatDate(dateStr) {
                    if (!dateStr) {
                        return '';
                    }
                    const [day, month, year] = dateStr.split('/');
                    return `${year}-${month}-${day}`;
                },
                getDetailUrl(attendanceId) {
                    // Get the date value directly from the input element to ensure it's current
                    const dateValue = document.getElementById('date')?.value || '';
                    const dateParts = dateValue.split('to').map(part => part.trim());

                    const startDate = this.formatDate(dateParts[0]);
                    const endDate = this.formatDate(dateParts[1]);

                    return `/adms/attendances-summary/detail/${attendanceId}/${startDate}/${endDate}`;
                }
            }
        }
    </script>
@endpush
