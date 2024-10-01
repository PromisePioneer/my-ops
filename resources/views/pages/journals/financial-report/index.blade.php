@extends('layouts.template')
@section('page-title', 'Laporan Keuangan')
@section('content')

    <div x-data="financialReportData()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto mb-10">
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
                                    <select class="form-select form-select-solid branch-select2"
                                            name="branch_id" id="branch_id">
                                    </select>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <input type="number" name="year" id="year" class="form-control form-control-solid"
                                           placeholder="Filter Berdasarkan Tahun">
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <select class="form-select form-select-solid"
                                            name="month" id="month" data-control="select2"
                                            data-placeholder="Pilih Bulan">
                                        <option></option>
                                        <template x-for="month in months" :key="index">
                                            <option :value="month.number" x-text="month.name"></option>
                                        </template>
                                    </select>
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
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6" id="kt_table_users">
                                    <thead>
                                    <tr>
                                        <th class="text-center text-uppercase" colspan="2">
                                            <u>Aset Lancar</u>
                                        </th>
                                    </tr>
                                    <template x-for="(currentAsset, index) in financialReports.current_asset"
                                              :key="index">
                                        <tr>
                                            <th class="text-center w-50" x-text="currentAsset.account_name"></th>
                                            <th class="text-center" x-text="currentAsset.amount"></th>
                                        </tr>
                                    </template>
                                    <tr class="bg-danger">
                                        <th class="text-center text-white">Jumlah Aset Lancar</th>
                                        <th class="text-center text-white"
                                            x-text="financialReports.total_current_asset"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center text-uppercase" colspan="2">
                                            <u>Aset Tetap</u>
                                        </th>
                                    </tr>
                                    <template x-for="(fixedAsset, index) in financialReports.fixed_asset" :key="index">
                                        <tr>
                                            <th class="text-center" x-text="fixedAsset.name"></th>
                                            <th class="text-center" x-text="fixedAsset.amount"></th>
                                        </tr>
                                    </template>
                                    <tr>
                                        <th class="text-center">Akumulasi Penyusutan Aset Tetap</th>
                                        <th class="text-center"
                                            x-text="financialReports.total_depreciation_asset"></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-danger text-center text-white">Jumlah Aset Tetap</th>
                                        <th class="bg-danger text-center text-white"
                                            x-text="financialReports.total_fixed_asset"></th>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark text-center text-white">Total Aktifa</th>
                                        <th class="bg-dark text-center text-white"
                                            x-text="financialReports.total_aktiva"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6" id="kt_table_users">
                                    <thead>
                                    <tr>
                                        <th class="text-center text-uppercase" colspan="2">
                                            <u>Utang Lancar</u>
                                        </th>
                                    </tr>
                                    <template x-for="(fixedDebt, index) in financialReports.current_debt" :key="index">
                                        <tr>
                                            <th class="text-center w-50" x-text="fixedDebt.account_name"></th>
                                            <th class="text-center" x-text="fixedDebt.amount"></th>
                                        </tr>
                                    </template>
                                    <tr class="bg-danger">
                                        <th class="text-center text-white">Jumlah Utang Lancar</th>
                                        <th class="text-center text-white"
                                            x-text="financialReports.total_current_debt"></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@push('script')
    <script>
        function financialReportData() {
            return {
                months: [],
                currentAssets: [],
                isLoading: true,
                startIndex: null,
                financialReports: [],
                async init() {
                    await this.getFinancialReport();
                    this.getMonth();
                    await this.getBranchData();
                },
                getMonth() {
                    this.months.push(
                        {name: "Januari", number: '01'},
                        {name: "Februari", number: '02'},
                        {name: "Maret", number: '3'},
                        {name: "April", number: '04'},
                        {name: "Mei", number: '05'},
                        {name: "Juni", number: '06'},
                        {name: "Juli", number: '07'},
                        {name: "Agustus", number: '08'},
                        {name: "September", number: '09'},
                        {name: "Oktober", number: '10'},
                        {name: "November", number: '11'},
                        {name: "Desember", number: '12'},
                    )
                },
                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".branch-select2")?.val();
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/journals/financial-report/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id,
                            }
                        });
                        this.financialReports = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getFinancialReport() {
                    const resp = await axios.get('/journals/financial-report/data');
                    this.financialReports = resp.data
                },
                formatNumber(val) {
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(val);
                },
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/journals/financial-report/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                },

            }
        }
    </script>

@endpush