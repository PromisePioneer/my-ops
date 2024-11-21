@extends('layouts.template')
@section('page-title', 'ADMS - Jadwal Karyawan')
@section('content')
    @push('styles')
        <style>
            .wrapper {
                overflow-x: scroll;
                width: 100%;
            }

            table {
                table-layout: fixed;
                width: 100%;
                border-collapse: collapse;
                background: white;
            }

            tr {
                border-top: 1px solid #ccc;
            }

            td, th {
                vertical-align: top;
                text-align: left;
                width: 150px;
                padding: 5px;
            }

            .fix {
                position: sticky;
                background: white;
            }

            .fix:first-child {
                left: 0;
                width: 180px;
            }

            .fix:last-child {
                right: 0;
                width: 120px;
            }
        </style>
    @endpush
    <div x-data="employeeScheduleData()">
        @include('pages.adms.employee-schedules.modal.create')
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
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div id="table-scroll" class="table-scroll">
                        <div class="table-responsive">
                            <table class="table align-middle gy-5 table-scroll fs-6">
                                <thead>
                                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-200px bg-light border border-black px-5 text-white fix">

                                    </th>
                                    <template x-if="employeeSchedules?.data.length > 0">
                                        <template x-for="date in employeeSchedules?.data[0].date"
                                                  :key="date.period_date">
                                            <th class="min-w-325px bg-light text-center text-black border border-black"
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
                                        <td class="bg-dark border border-black text-white px-2 fix"
                                            x-text="employeeSchedule?.name"></td>
                                        <template x-for="dates in employeeSchedule?.date">
                                            <td :class="`${dates.schedules_date?.status === 'L' ? 'border border-black text-center bg-warning' : dates.schedules_date?.status === 'H' ? 'border border-black text-center bg-primary' : 'border border-black text-center bg-light'}`">
                                                <div>
                                                    <a href="#" data-bs-toggle="modal"
                                                       data-bs-target="#modal-create"
                                                       :class="`${dates.schedules_date?.status === 'L' ? 'text-black' : dates.schedules_date?.status === 'H' ? 'text-black' : 'text-black'}`"
                                                       @click="getSchedules(employeeSchedule.absent_id, dates.schedules_date?.date ??  dates.period_date )"
                                                       x-text="`${dates?.work_time_schedules} ${dates.schedules_date?.status === 'L' ? 'Libur' : dates.schedules_date?.status === 'H' ? 'Hadir' : ''}`  ?? '-'"></a>
                                                </div>
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
        $('.date').flatpickr();

        function employeeScheduleData() {
            return {
                search: '',
                isLoading: false,
                employeeSchedules: null,
                buttonLoading: false,
                schedulesValue: null,
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                async init() {
                    const resp = await axios.get('/adms/employee-schedules/data');
                    this.employeeSchedules = resp.data
                    await this.getWorkTimeData();
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
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.employeeSchedules = resp.data
                    }
                },
                async getSchedules(employeeId, date) {
                    console.log(employeeId);

                    const resp = await axios.get(`/adms/employee-schedules/get-schedules/${date}/${employeeId}`);
                    if (Object.keys(resp.data).length) {
                        this.schedulesValue = resp.data;
                    } else {
                        this.schedulesValue = {date: date, employee_id: employeeId};
                    }
                    await this.getWorkTimeData();
                    await this.selectedWorkTime()
                },
                async getWorkTimeData() {
                    $(`.work-time-select2`).select2({
                        placeholder: "Pilih Jam Kerja",
                        allowClear: true,
                        ajax: {
                            url: '/adms/employee-schedules/work-time/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('select2:select', function (resp) {
                        const data = resp.params.data;
                        response.service_category_id = data.id
                    });
                },
                async selectedWorkTime() {
                    const selectedWorkTime = $('#selectedWorkTime');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/adms/employee-schedules/work-time/selected/${this.schedulesValue.id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedWorkTime.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/adms/employee-schedules/', new FormData(this.formCreate))
                        await showAlert('success', 'Data berhasil disimpan')
                        this.formCreate.reset();
                        this.modalCreate.hide();
                        await this.init();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
