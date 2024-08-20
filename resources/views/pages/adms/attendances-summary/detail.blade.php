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
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
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
                                <th class="min-w-125px">No</th>
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
                                    <td x-text="index + 1"></td>
                                    <td x-text="attendance.branch"></td>
                                    <td x-text="attendance.nip"></td>
                                    <td x-text="attendance.name"></td>
                                    <td x-text="attendance.total_hadir"></td>
                                    <td x-text="`${attendance.total_menit_terlambat} Menit`"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function attendancesSummary() {
            return {
                isLoading: false,
                attendances: [],
                startIndex: null,
                month: "{{ $month }}",
                year: "{{ $year }}",
                search: '',
                async init() {
                    const resp = await axios.get(`/adms/attendances-summary/detail/data/01-${this.month}-${this.year}`);
                    this.attendances = resp.data;
                },
                async searchData() {
                    try {
                        this.attendances = await axios.get('/adms/attendances-summary/detail/data/search', {
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
                        this.startIndex = this.attendances.from
                        this.attendances = resp.data
                    }
                },
                async previousPage() {
                    if (this.attendances.prev_page_url) {
                        const resp = await axios.get(`${this.attendances.prev_page_url}`);
                        this.startIndex = this.attendances.from
                        this.attendances = resp.data
                    }
                },
            }
        }
    </script>
@endpush