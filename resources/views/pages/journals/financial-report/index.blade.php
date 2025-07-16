@extends('layouts.template')
@section('page-title', 'Laporan Keuangan')
@section('content')

    @push('styles')

        <style>
            .bg-seterah {
                background-color: #d6e3bc;
            }
        </style>
    @endpush

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
                                    <select class="form-select form-select-solid main-branches-select2"
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
                            <div>
                                <template x-for="(report, index) in financialReports" :key="index">
                                    <div>
                                        <p class="p-1 m-0 bg-danger fs-6 fw-bolder text-white text-center text-uppercase mb-2"
                                           x-text="report.name"></p>
                                        <template x-for="(subCategory, index) in report.sub_categories"
                                                  :key="index">
                                            <div class="p-0">
                                                <p class="p-1 text-center text-uppercase bg-info fw-bolder text-white"
                                                   x-text="subCategory.name"></p>
                                                <table class="w-100 table table-bordered">
                                                    <template x-for="(account, index) in subCategory.accounts"
                                                              :key="index">
                                                        <thead>
                                                        <tr class="text-center">
                                                            <th class="w-50" x-text="account.name"></th>
                                                            <th x-text="account.balance"></th>
                                                        </tr>
                                                        </thead>
                                                    </template>
                                                    <tr class="text-center">
                                                        <th>Total</th>
                                                        <th x-text="subCategory.total"></th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </template>
                                        <div>
                                            <div
                                                class="d-flex align-items-center justify-content-around bg-info text-center text-white text-uppercase p-1 fw-bolder mb-2 bg-seterah">
                                                <span class="text-center" x-text="`Total ${report.name}`"></span>
                                                <span class="text-center" x-text="report.total_each_categories"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                isLoading: true,
                startIndex: null,
                financialReports: [],
                async init() {
                    await this.getFinancialReport();
                    this.getMonth();
                    await this.getMainBranches();
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
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/main-branches-data',
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
