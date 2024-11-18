@extends('layouts.template')
@section('page-title', 'ADMS - Jadwal Karyawan')
@section('content')

    <div x-data="employeeScheduleData()">
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
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-light-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-create">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="col-12 ">
                </div>
                <div class="py-5">
                    <div class="table-responsive">
                        <template x-for="employeeSchedule in employeeSchedules?.data" :key="employeeSchedule.id">
                            <table class="table align-middle fs-6 gy-lg-6 table-bordered" id="kt_table_users">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px"></th>
                                    <template x-for="date in employeeSchedule.date">
                                        <th class="bg-danger border-0" x-text="formatDate(date.period_date)"></th>
                                    </template>
                                </tr>
                                </thead>
                                <tbody class="fw-bold">
                                <tr>
                                    <td x-text="employeeSchedule?.name"></td>
                                    <template x-for="dates in employeeSchedule?.date">
                                        <td>
                                            <template x-if="dates.schedules_date.status === 'H'">
                                                <button class="btn text-black btn-success btn-sm">
                                                    Hadir
                                                </button>
                                            </template>
                                            <template x-if="dates.schedules_date.status === 'L'">
                                                <button class="btn text-black btn-warning btn-sm">
                                                    Libur
                                                </button>
                                            </template>
                                            <template x-if="dates?.schedules_date === null">
                                                <button class="btn text-black btn-secondary btn-sm">
                                                    Kosong
                                                </button>
                                            </template>
                                        </td>
                                    </template>
                                </tr>
                                </tbody>
                            </table>
                        </template>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in employeeSchedules?.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')

@endsection
@push('script')
    <script>
        function employeeScheduleData() {
            return {
                search: '',
                isLoading: false,
                employeeSchedules: null,
                async init() {
                    const resp = await axios.get('/adms/employee-schedules/data');
                    this.employeeSchedules = resp.data
                },
                formatDate(val) {
                    const date = new Date(val);

                    const options = {
                        day: "numeric",
                    };
                    return date.toLocaleDateString("id", options)
                },
            }
        }
    </script>
@endpush
