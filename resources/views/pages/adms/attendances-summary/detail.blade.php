@php use Carbon\Carbon; @endphp
@extends('layouts.template')
@section('content')
    @push('styles')
        <script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    @endpush
    <div x-data="attendancesSummary()">
        @include('pages.adms.attendances-summary.modal.sp')
        @include('pages.adms.attendances-summary.modal.attendances-summary-detail')
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="d-flex card-header border-0 align-items-center justify-content-between">
                <form id="form-filter-date" @submit.prevent="filterDate()">
                    <div class="d-flex my-1 align-items-center gap-4">
                        <input type="date" class="form-control form-control-solid" placeholder="Tanggal awal"
                               id="startDate" name="start_date"/>
                        <input type="date" class="form-control form-control-solid" placeholder="Tanggal akhir"
                               id="endDate" name="end_date"/>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </div>
                </form>
                <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                    <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                           class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                </div>
            </div>
        </div>
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="d-flex card-header border-0 pt-6 align-items-center">
                <div class="card-title">
                    <a href="{{ url('adms/attendances-summary') }}" class="btn btn-info btn-sm">Kembali</a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Cabang</th>
                                <th class="min-w-125px">NIK</th>
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">Total Kehadiran</th>
                                <th class="min-w-125px">Total Terlambat</th>
                                <th class="min-w-125px">Actions</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
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
                            <template x-if="!isLoading && attendances?.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(attendance, index) in attendances.data" :key="index">
                                <tr>
                                    <td x-text="attendance.branch"></td>
                                    <td x-text="attendance.nip"></td>
                                    <td x-text="attendance.name"></td>
                                    <td x-text="attendance.total_hadir"></td>
                                    <td x-text="`${attendance.total_menit_terlambat} Menit`"></td>
                                    <td>
                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                           data-bs-target="#modal-sp" @click="detail(attendance.employee_id)">
                                            SP
                                        </a>
                                        <a class="btn btn-info btn-sm" data-bs-toggle="modal"
                                           data-bs-target="#modal-attendances-summary-detail"
                                           @click="detail(attendance.employee_id)">
                                            <i class="fas fa-info"></i>
                                        </a>
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
    @include('components.toast')
@endsection
@push('script')
    <script>
        let startOfMonth = "{{ Carbon::parse($year . '-' . $month . '-'. '01')->firstOfMonth() }}";
        let endOfMonth = "{{ Carbon::parse($year . '-' . $month . '-'. '01')->endOfMonth() }}";

        $("#startDate").flatpickr({
            minDate: startOfMonth,
            maxDate: endOfMonth
        });
        $("#endDate").flatpickr({
            minDate: startOfMonth,
            maxDate: endOfMonth
        });


        tinymce.init({
            selector: 'textarea#description',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            editimage_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: "undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            importcss_append: true,
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        function attendancesSummary() {
            return {
                buttonLoading: false,
                isLoading: false,
                attendances: [],
                startIndex: null,
                month: "{{ $month }}",
                year: "{{ $year }}",
                search: '',
                userId: "",
                formFilterDate: document.getElementById('form-filter-date'),
                modalSp: new bootstrap.Modal(document.getElementById('modal-sp')),
                modalAttendanceSummaryDetail: new bootstrap.Modal(document.getElementById('modal-attendances-summary-detail')),
                formSp: document.getElementById('form-sp'),
                usersDetail: [],
                async init() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/data/01-${this.month}-${this.year}`);
                    this.attendances = resp.data;
                    this.startIndex = this.attendances.from
                },
                async searchData() {
                    try {
                        this.attendances = await axios.get(`/adms/attendances-summary/detail/data/search/01-${this.month}-${this.year}`, {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                    } catch (error) {
                        console.log(error);
                    }
                },
                async nextPage() {
                    if (this.attendances.next_page_url) {
                        const resp = await axios.get(`${this.attendances.next_page_url}`);
                        this.attendances = resp.data
                        this.startIndex = this.attendances.from
                    }
                },
                async previousPage() {
                    if (this.attendances.prev_page_url) {
                        const resp = await axios.get(`${this.attendances.prev_page_url}`);
                        this.attendances = resp.data
                        this.startIndex = this.attendances.from
                    }
                },
                async filterDate() {
                    const resp = await axios.post(`/adms/attendances-summary/detail/data/filter-date/${this.month}/${this.year}`, new FormData(this.formFilterDate));
                    this.attendances = resp.data;
                },
                async detail(id) {
                    this.userId = id
                    await this.getAttendanceSummaryDetailForEachUser();
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/adms/attendances-summary/detail/data/assign-sp/${this.userId}`, new FormData(this.formSp))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formSp.reset();
                        this.modalSp.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async getAttendanceSummaryDetailForEachUser() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/data/user/detail/${this.month}/${this.year}/${this.userId}`);
                    this.usersDetail = resp.data;
                    console.log(usersDetail?.total_present);
                },

                async nextPageForUserSummary() {
                    if (this.usersDetail.next_page_url) {
                        const resp = await axios.get(`${this.usersDetail.next_page_url}`);
                        this.usersDetail = resp.data.data
                        this.startIndex = this.usersDetail.from
                    }
                },
                async previousPageForUserSummary() {
                    if (this.usersDetail.prev_page_url) {
                        const resp = await axios.get(`${this.usersDetail.prev_page_url}`);
                        this.usersDetail = resp.data.data
                        this.startIndex = this.usersDetail.from
                    }
                },

            }
        }
    </script>
@endpush