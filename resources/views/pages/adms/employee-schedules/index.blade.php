@extends('layouts.template')
@section('page-title', 'ADMS - Jadwal Karyawan')
@section('content')
    @push('styles')
        <style>
            table {
                table-layout: fixed;
                width: 100%;
                border-collapse: collapse;
                background: white;
            }

            tr th, td {
                text-align: center;
                padding: 3px;
                white-space: nowrap; /* Prevents text wrapping */
                border: 1px solid #000;
            }

            /* Color codes for different schedule types */
            .shift-p {
                background-color: #5C95FF !important; /* Green for P shift */
            }

            .shift-s {
                background-color: #FFA9A3 !important; /* Red for S shift */
            }

            .shift-m {
                background-color: #7E6C6C !important; /* Yellow for M shift */
            }

            .libur {
                background-color: #0000ff !important; /* Blue for Libur/Off days */
                color: white;
            }

            .fix:first-child {
                position: sticky;
                left: 0;
                width: 200px;
                background-color: white;
            }

            .date-header {
                font-weight: bold;
                background-color: #009900; /* Green header background */
                color: white;
                border: 1px solid #000 !important;
            }

            .table-title {
                background-color: #123458;
                color: white;
                text-align: center;
                font-weight: bold;
                padding: 8px;
                margin-bottom: 0;
            }

            .holiday-note {
                background-color: #123458;
                padding: 5px;
                margin-top: 10px;
                border: 1px solid #D4C9BE;
            }
        </style>
    @endpush
    <div x-data="employeeScheduleData()">
        @include('pages.adms.employee-schedules.modal.create')
        <div class="card card-xl-stretch">
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
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <label for="name" class="form-label mt-3">Filter Jadwal :</label>
                        </div>
                        <div class="me-3">
                            <input type="date" class="form-control form-control-solid date" name="start_dates"
                                   id="start_dates" placeholder="Pilih Tanggal Awal"/>
                        </div>
                        <div class="me-3">
                            <input type="date" class="form-control form-control-solid date" id="end_date"
                                   placeholder="Pilih Tanggal Akhir" name="end_date"/>
                        </div>
                        <div>
                            <button type="button" @click="filterByDate()" class="btn btn-sm btn-light-info">Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <h3 class="table-title"
                    x-text="`${isLoading ? `Loading...` : `JADWAL LIBUR KARYAWAN BULAN ${formatDateToMonth(employeeSchedules?.data?.[0]?.date?.at(-1)?.period_date).toUpperCase()}` }`"></h3>
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table fs-6">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 fs-9">
                                <th :class="`${isLoading ? 'bg-light border border-black px-5 text-dark fix d-none' : 'bg-light border border-black px-5 text-dark fix'}`">
                                    Nama
                                </th>
                                <template x-if="employeeSchedules?.data.length > 0">
                                    <template x-for="date in employeeSchedules?.data[0].date" :key="date.period_date">
                                        <th class=" bg-light text-center text-black date-header"
                                            x-text="formatDate(date.period_date)">
                                        </th>
                                    </template>
                                </template>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="6">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="employeeSchedule in employeeSchedules?.data"
                                      :key="employeeSchedule.id">
                                <tbody class="fw-bold p-0">
                                <tr>
                                    <td class="text-white fix fs-9"
                                        x-text="employeeSchedule?.name" style="background-color: #123458"></td>
                                    <template x-for="dates in employeeSchedule?.date">
                                        <td :class="getTdClass(dates)">
                                            <div>
                                                <template
                                                    x-if="!dates.leaves && !dates.sick && !dates.permission">
                                                    <a href="#"
                                                       class="btn btn-link btn-sm text-decoration-underline"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-create"
                                                       :disabled="Number(createPermission) !== 1"
                                                       @click="getSchedules(employeeSchedule.absent_id, dates.schedules_date?.period_dates ??  dates.period_date )"
                                                    >

                                                        <template
                                                            x-if="dates.schedules_date?.status === 'H' && dates.is_holiday === null">
                                                            <span
                                                                x-text="`${dates.schedules_date?.work_time?.name.charAt(0)}`"></span>
                                                        </template>


                                                        <template
                                                            x-if="dates.is_holiday === 1 || dates.schedules_date?.status === 'L'">
                                                            <span>L</span>
                                                        </template>

                                                        <template
                                                            x-if="!dates?.work_time_schedules && !dates.is_holiday && !dates.schedules_date">
                                                            <span>
                                                               P
                                                            </span>
                                                        </template>
                                                    </a>
                                                </template>
                                            </div>
                                            <div>
                                                <template x-if="dates.leaves">
                                                    <span
                                                        class="text-black p-0">
                                                        C
                                                    </span>
                                                </template>
                                            </div>
                                            <div>
                                                <template x-if="dates.sick">
                                                    <span
                                                        class="text-black p-0">
                                                        S
                                                    </span>
                                                </template>
                                            </div>
                                            <div>
                                                <template x-if="dates.permission">
                                                    <span
                                                        class="fw-bolder">
                                                        I
                                                    </span>
                                                </template>
                                            </div>
                                        </td>
                                    </template>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <div class="holiday-note mt-3">
                        <template x-for="holiday in nationalHolidays" :key="holiday.id">

                            <p class="mb-1 text-white" x-text="`${holiday.date} : ${holiday.description}`"></p>
                        </template>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-danger fs-9">
                                Note : Harap Isi Cuti, Izin, Sakit di Menu Manejemen Cuti Terlebih dahulu per periode.
                            </span>
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
    </div>
    @include('components.toast')

@endsection
@push('script')
    <script>
        $('.date').flatpickr();

        function employeeScheduleData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah / Ubah Data Jadwal Libur') }}",
                search: '',
                isLoading: false,
                employeeSchedules: null,
                buttonLoading: false,
                schedulesValue: null,
                modalCreate: new bootstrap.Modal(document.getElementById('modal-create')),
                formCreate: document.getElementById('form-create'),
                isCrossMidnightShift: false,
                dayCount: 1,
                isDayCount: false,
                nationalHolidays: [],
                async init() {
                    await this.getEmployeeSchedules();
                    await this.getWorkTimeData();
                    await this.getNationalHoliday();
                },
                async getEmployeeSchedules() {
                    const start_date = document.getElementById('start_dates')?.value ?? null;
                    const end_date = document.getElementById('end_date')?.value ?? null;
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/employee-schedules/data', {
                            params: {
                                startDate: start_date,
                                endDate: end_date
                            }
                        });
                        this.employeeSchedules = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                async getNationalHoliday() {
                    const start_date = document.getElementById('start_dates')?.value ?? null;
                    const end_date = document.getElementById('end_date')?.value ?? null;
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/adms/employee-schedules/national-holidays', {
                            params: {
                                startDate: start_date,
                                endDate: end_date
                            }
                        });
                        this.nationalHolidays = resp.data
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false
                    }
                },
                async filterByDate() {
                    const startDate = document.getElementById('start_dates')?.value ?? null;
                    const endDate = document.getElementById('end_date')?.value ?? null;

                    const resp = await axios.get('/adms/employee-schedules/filter', {
                        params: {
                            start_date: startDate,
                            end_date: endDate,
                        }
                    });
                    this.employeeSchedules = resp.data;
                },
                formatDate(val) {
                    const date = new Date(val);
                    return date.toLocaleDateString("id", {
                        day: "numeric",
                    });
                },
                formatDateToMonth(val) {
                    const date = new Date(val);
                    return date.toLocaleDateString("id", {
                        month: "long",
                        year: "numeric"
                    });
                },
                async paginationEndPoint(url) {
                    const startDate = document.getElementById('start_dates').value;
                    const endDate = document.getElementById('end_date').value;
                    if (url) {
                        try {
                            this.employeeSchedules = [];
                            this.isLoading = true;
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    start_date: startDate,
                                    end_date: endDate,
                                }
                            });
                            this.employeeSchedules = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false;
                        }
                    }
                },
                async searchData() {
                    try {
                        const start_date = document.getElementById('start_dates').value;
                        const end_date = document.getElementById('end_date').value;
                        const resp = await axios.get('/adms/employee-schedules/search', {
                            params: {
                                search: this.search,
                                start_date: start_date,
                                end_date: end_date,
                            },
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.employeeSchedules = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async getSchedules(employeeId, date) {
                    const resp = await axios.get(`/adms/employee-schedules/get-schedules/${date}/${employeeId}`);
                    if (Object.keys(resp.data).length) {
                        this.schedulesValue = resp.data;
                    } else {
                        this.schedulesValue = {date: date, employee_id: employeeId};
                    }
                    await this.getWorkTimeData();
                    await this.selectedWorkTime();
                },
                addDay() {
                    if (this.dayCount >= 0) {
                        this.dayCount++;
                    }
                },
                subDay() {
                    if (this.dayCount > 0) {
                        this.dayCount--;
                    }
                },
                async getWorkTimeData() {
                    $(`.work-times-select2`).select2({
                        placeholder: "Pilih Jam Kerja",
                        allowClear: true,
                        ajax: {
                            url: '/select2/work-times-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    })
                },
                async selectedWorkTime() {
                    if (!this.schedulesValue.id) return;
                    const selectedWorkTime = $('#selectedWorkTime');
                    const response = await $.ajax({
                        type: 'GET',
                        dataType: "JSON",
                        url: `/select2/selected-work-time/${this.schedulesValue.work_time_id}`,
                    });
                    const option = new Option(response.name, response.id, true, true);
                    selectedWorkTime.append(option).trigger('change').trigger({
                        type: 'select2:select',
                        params: {results: response}
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    const startDate = document.getElementById('start_dates').value;
                    const endDate = document.getElementById('end_date').value;
                    try {
                        await axios.post('/adms/employee-schedules/', new FormData(this.formCreate)).then(async () => {
                            const resp = await axios.get(`${this.employeeSchedules.path}?page=${this.employeeSchedules.current_page}`, {
                                params: {
                                    start_date: startDate,
                                    end_date: endDate,
                                    search: this.search
                                }
                            });
                            this.employeeSchedules = resp.data
                            this.dayCount = 1;
                        })
                        await showAlert('success', 'Data berhasil disimpan')
                        this.modalCreate.hide();
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },

                getTdClass(dates) {
                    // Check for leave, sick, or permission status
                    if (dates.leaves || dates.sick || dates.permission) {
                        return 'text-center border border-black text-black bg-warning p-0';
                    }

                    if (dates.schedules_date?.status === 'L' || dates.is_holiday) {
                        return 'text-center border border-black text-black bg-warning p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Pagi') {
                        return 'text-center border border-black shift-p text-white  p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Pagi (Ramadhan)') {
                        return 'text-center border border-black text-white shift-p bg-info p-0';
                    }


                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Lapangan') {
                        return 'text-center border border-black shift-p text-white  p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Lapangan (Ramadhan)') {
                        return 'text-center border border-black text-white shift-p bg-info p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Duri') {
                        return 'text-center border border-black shift-p text-white  p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Duri (Ramadhan)') {
                        return 'text-center border border-black text-white shift-p bg-info p-0';
                    }


                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Lapangan') {
                        return 'text-center border border-black shift-p text-white  p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Lapangan (Ramadhan)') {
                        return 'text-center border border-black text-white shift-p bg-info p-0';
                    }

                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Sore') {
                        return 'text-center border border-black shift-s p-0';
                    }


                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'Malam') {
                        return 'text-center border border-black shift-m p-0';
                    }


                    if (dates.schedules_date?.status === 'H' && dates.schedules_date.work_time.name === 'KU Malam') {
                        return 'text-center border border-black shift-m p-0';
                    }

                    if (!dates?.work_time_schedules && !dates.is_holiday && !dates.schedules_date) {
                        return 'text-center border border-black shift-p fw-bolder p-0'
                    }

                    return 'text-center border border-black text-white p-0';
                }
            }
        }
    </script>
@endpush
