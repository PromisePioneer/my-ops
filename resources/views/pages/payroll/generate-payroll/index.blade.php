@php use Carbon\Carbon; @endphp
@extends('layouts.template')
@section('page-title', 'Generate Payroll')
@section('content')

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    @endpush


    <div x-data="generatePayroll()">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Generate Payroll</h3>
            </div>
            <form action="" id="generate-payroll" @submit.prevent="saveSetup()">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed table-bordered fs-6 gy-5" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-100px text-center">Nama</th>
                                <th class="min-w-125px text-center">Gaji Pokok</th>
                                <th class="min-w-125px text-center">Tunjangan</th>
                                <th class="min-w-125px text-center">Denda / Potongan</th>
                            </tr>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && userJobInfo.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="user in userJobInfo" :key="user.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <a :href="Number(viewDetailPermission) === 1 ? `/manage-users/users/detail/${user.id}` : '#'"
                                               class="text-gray-800 text-hover-primary mb-1">
                                                <span x-text="user.name"></span>
                                            </a>
                                            <span class="badge badge-info fw-bolder fs-8 mb-2"
                                                  x-text="`Status Jabatan : ${user.roles ?? ''}`">
                                                    </span>
                                            <span class="badge badge-info fw-bolder mb-2 fs-8"
                                                  x-text="`Status Karyawan : ${user.emp_status ?? '-'}`">
                                                    </span>
                                            <span class="badge badge-info fw-bolder fs-8"
                                                  x-text="`Perusahaan : ${user.company ?? '-'}`">
                                                    </span>

                                        </div>
                                    </td>
                                    <td class="text-center" x-text="user.fixed_salary"></td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center flex-column">
                                            <table class="table">
                                                <tr class="d-flex align-items-center justify-content-around">
                                                    <td class="fw-bolder mb-2">
                                                        Lembur
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        :
                                                    </td>
                                                    <td class="fw-bolder mb-2"
                                                        x-text="`${user.overtime_allowance ?? 'N/A'}`">
                                                    </td>
                                                </tr>
                                                <tr class="d-flex align-items-center justify-content-around">
                                                    <td class="fw-bolder mb-2">
                                                        Makan
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        :
                                                    </td>
                                                    <td class="fw-bolder mb-2"
                                                        x-text="user.meal_allowance ?? 'N/A'">
                                                    </td>
                                                </tr>
                                                <tr class="d-flex align-items-center justify-content-around">
                                                    <td class="fw-bolder mb-2">
                                                        Jabatan
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        :
                                                    </td>
                                                    <td class="fw-bolder mb-2"
                                                        x-text="user.position_allowance ?? 'N/A'">
                                                    </td>
                                                </tr>
                                                <tr class="d-flex align-items-center justify-content-around">
                                                    <td class="fw-bolder mb-2">
                                                        Transport
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        :
                                                    </td>
                                                    <td class="fw-bolder mb-2"
                                                        x-text="user.transportation_allowance ?? 'N/A'">
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class="table">
                                                <tr class="d-flex align-items-center justify-content-around border-top">
                                                    <td class="fw-bolder mb-2">
                                                        Total
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        :
                                                    </td>
                                                    <td class="fw-bolder mb-2">
                                                        <span x-text="user.total_allowance"></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-sm btn-light-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        function generatePayroll() {
            return {
                formGenerate: document.getElementById('generate-payroll'),
                userJobInfo: [],
                isLoading: false,
                async init() {
                    await this.getUserJobInfo();
                },
                async getUserJobInfo() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/payroll/generate/user-job-info');
                        this.userJobInfo = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async saveSetup() {
                    this.buttonLoading = true;
                    try {
                        await axios.post('/payroll/generate', new FormData(this.formGenerate))
                        await showAlert('success', 'Data berhasil disimpan')
                        window.location.href = '/payroll/payroll-history/';
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(respError[err][0]))
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>

@endpush
