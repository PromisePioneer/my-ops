@extends('layouts.template')
@section('content')

    <div x-data="trialBalanceData">
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
                <div class="card card-flush mb-6 mb-xl-9">
                    <div class="card-body pt-5">
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125 text-center">No</th>
                                        <th class="min-w-125px text-center">Akun</th>
                                        <th class="min-w-125px text-center">Debit</th>
                                        <th class="min-w-125px text-center">Kredit</th>
                                    </tr>
                                    </thead>
                                    <tbody class="fw-bold">
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
                                    <template x-if="!isLoading && trialBalance.trial_balances?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(journal, index) in trialBalance.trial_balances" :key="index">
                                        <tr>
                                            <td class="text-center" x-text="1 + index++"></td>
                                            <td class="text-center" x-text="journal.account_name"></td>
                                            <td class="text-center" x-text="journal.debit"></td>
                                            <td class="text-center" x-text="journal.credit"></td>
                                        </tr>
                                    </template>
                                    </tbody>
                                    <tfoot>
                                    <tr class="fw-bold">
                                        <td colspan="2" class="text-center">Jumlah</td>
                                        <td class="text-center" x-text="trialBalance.total_debit"></td>
                                        <td colspan="2" class="text-center" x-text="trialBalance.total_credit"></td>
                                    </tr>
                                    </tfoot>
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
        function trialBalanceData() {
            return {
                months: [],
                trialBalance: [],
                formFilter: document.getElementById('form-filter'),
                async init() {
                    await this.getTrialBalance();
                    await this.getMonth();
                    await this.getBranchData();
                },
                async getTrialBalance() {

                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/journals/trial-balance/data')
                        this.trialBalance = resp.data;
                    } catch (e) {

                    } finally {
                        this.isLoading = false;
                    }
                },
                async filter() {
                    const year = document.getElementById('year')?.value ?? '';
                    const month = document.getElementById('month')?.value ?? '';
                    const branch_id = $(".branch-select2")?.val();
                    const active = document.getElementById('active')?.value;
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/journals/trial-balance/filter', {
                            params: {
                                month: month,
                                year: year,
                                branch_id: branch_id,
                            }
                        });
                        this.trialBalance = resp.data;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false;
                    }
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
                async getBranchData() {
                    $(".branch-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/journals/trial-balance/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    });
                }
            }
        }
    </script>
@endpush