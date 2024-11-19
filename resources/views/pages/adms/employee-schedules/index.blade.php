@extends('layouts.template')
@section('page-title', 'ADMS - Jadwal Karyawan')
@section('content')
    @push('styles')
        <style>
            .table-scroll {
                position: relative;
                margin: auto;
                overflow: hidden;
            }

            .table-wrap {
                width: 100%;
                overflow: auto;
            }


            .clone {
                position: absolute;
                top: 0;
                left: 0;
                pointer-events: none;
            }

            .clone th, .clone td {
                visibility: hidden
            }


            .clone .fixed-side {
                visibility: visible;
            }

            .clone thead, .clone tfoot {
                background: transparent;
            }
        </style>
    @endpush
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
                    <div id="table-scroll" class="table-scroll">
                        <div class="table-wrap">
                            <table class="table align-middle fs-6 gy-lg-6 table-s main-table"
                                   id="kt_table_users">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px bg-dark text-white fixed-side border-0">Nama</th>
                                    <template x-if="employeeSchedules?.data.length > 0">
                                        <template x-for="date in employeeSchedules?.data[0].date"
                                                  :key="date.period_date">
                                            <th class="bg-danger border-0 text-center"
                                                x-text="formatDate(date.period_date)">
                                            </th>
                                        </template>
                                    </template>
                                </tr>
                                </thead>
                                <template x-for="employeeSchedule in employeeSchedules?.data"
                                          :key="employeeSchedule.id">
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td class="fixed-side bg-dark text-white border-0"
                                            x-text="employeeSchedule?.name"></td>
                                        <template x-for="dates in employeeSchedule?.date">
                                            <td>
                                                <template x-if="dates.schedules_date?.status === 'H'">
                                                    <button class="btn text-black btn-success btn-sm">
                                                        Hadir
                                                    </button>
                                                </template>
                                                <template x-if="dates.schedules_date?.status === 'L'">
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
                                </template>
                            </table>
                        </div>
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

        $(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');

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
                        year: "numeric",
                        month: "numeric"
                    };
                    return date.toLocaleDateString("id", options)
                },
            }
        }
    </script>
@endpush
