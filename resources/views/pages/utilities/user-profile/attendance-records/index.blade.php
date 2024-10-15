@extends('layouts.template')
@section('page-title', 'Informasi Identitas')
@section('content')
    @include('pages.utilities.user-profile.partials.header')
    <div x-data="userAttendanceRecordsData()">
        @include('pages.manage-users.user.modal.import')
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
                                           placeholder="Tanggal akhir ">
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
                    <div class="card-header">
                    </div>
                    <div class="card-body pt-0">
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Check In</th>
                                        <th class="text-center">Check Out</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                    <template x-if="isLoading">
                                        <tr>
                                            <td colspan="5">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="!isLoading && users.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(userAttendance, index) in attendanceRecords" :key="index">
                                        <tr>
                                            <td class="text-center" x-text="userAttendance.date_period"></td>
                                            <td class="text-center" x-text="userAttendance.clock_in"></td>
                                            <td class="text-center" x-text="userAttendance.clock_out"></td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-10">
                                <div class="col-sm-12  d-flex align-items-center justify-content-end">
                                    <template x-for="pagination in users.links">
                                        <ul class="pagination">
                                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                                <button
                                                        class="page-link"
                                                        @click="paginationEndPoint(pagination.url)"
                                                        x-html="pagination.label"></button>
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
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

        function userAttendanceRecordsData() {
            return {
                isLoading: false,
                attendanceRecords: [],
                async init() {
                    await this.getAttendanceRecords();
                },
                async filter() {
                    const startDate = document.getElementById('start_date')?.value ?? '';
                    const endDate = document.getElementById('end_date')?.value ?? '';
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/utility/user-profile/attendance-records/filter', {
                            params: {
                                start_date: startDate,
                                end_date: endDate,
                            }
                        });
                        this.attendanceRecords = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getAttendanceRecords() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/utility/user-profile/attendance-records/data');
                        this.attendanceRecords = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush