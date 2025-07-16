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
                        <table class="table align-middle table-row-dashed table-bordered border-black fs-6 gy-5"
                               id="kt_table_users">
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
                                                  x-text="`Jabatan : ${user.roles ?? 'N/A'}`">
                                                    </span>
                                            <span class="badge badge-info fw-bolder mb-2 fs-8"
                                                  x-text="`Status Karyawan : ${user.emp_status ?? 'N/A'}`">
                                                    </span>
                                            <span class="badge badge-info fw-bolder fs-8"
                                                  x-text="`Perusahaan : ${user.company ?? '-'}`">
                                                    </span>

                                        </div>
                                    </td>
                                    <td class="text-center" x-text="user.fixed_salary"></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Jabatan</span>
                                                <span x-text="user.position_allowance"></span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Makan</span>
                                                <span x-text="user.meal_allowance"></span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Lembur</span>
                                                <span x-text="user.overtime_allowance"></span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Transportasi</span>
                                                <span x-text="user.transportation_allowance"></span>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Total Tunjangan</span>
                                                <span x-text="user.total_allowance"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>SLA</span>
                                                <span x-text="user.sla_deduction"></span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>9.15</span>
                                                <span x-text="user.nine_past_fifteen_deduction"></span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-10">
                                                <span>Lainnya</span>
                                                <span x-text="user.additional_deduction"></span>
                                            </div>
                                            <hr>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span>Total Denda</span>
                                                <span x-text="user.total_deduction"></span>
                                            </div>
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
