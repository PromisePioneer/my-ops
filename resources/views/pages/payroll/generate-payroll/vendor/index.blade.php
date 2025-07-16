@extends('layouts.template')
@section('content')

    <div x-data="generateVendorPayroll()">
        <div class="row">
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
                                    <th class="min-w-125px text-center">Area</th>
                                    <th class="min-w-125px text-center">Jumlah Penarikan</th>
                                    <th class="min-w-125px text-center">Total Gaji</th>
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
                                <template x-if="!isLoading && vendor.length === 0">
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td colspan="9">
                                            <center>Data Tidak Ditemukan</center>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                                <template x-for="user in vendor" :key="user.id">
                                    <tbody class="fw-bold">
                                    <tr>
                                        <td class="text-center" x-text="user.name"></td>
                                        <td class="text-center" x-text="user.psb"></td>
                                        <td>
                                            <ol class="text-uppercase">
                                                <template x-for="vendor in user.vendors">
                                                    <li x-text="`${vendor.user}  = ${vendor.total_salary}`"></li>
                                                </template>
                                            </ol>
                                        </td>
                                    </tr>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function generateVendorPayroll() {
            return {
                isLoading: false,
                vendor: [],
                psb: [],
                async init() {
                    await this.vendorData();
                },
                async vendorData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/payroll/generate-payroll/vendor/data');
                        this.vendor = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush
