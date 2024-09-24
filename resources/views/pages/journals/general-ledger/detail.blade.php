@extends('layouts.template')
@section('page-title', 'Buku Besar')
@section('content')

    <div x-data="bigBookDetail">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h4>Akun : ({{ $account->code  }}) {{ $account->name }}</h4>
                </div>
                <div class="card-toolbar">
                    <select class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Pilih Periode">
                        <option></option>
                        <template x-for="(period,index) in periods" :key="index">
                            <option :value="period.value" x-text="period.name"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row table-responsive">
                    <table class="table align-middle table-bordered fs-6 gy-5 table-striped">
                        <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2 text-center">#</th>
                            <th class="min-w-125px text-center">Tgl. Transaksi</th>
                            <th class="min-w-125px text-center">Keterangan</th>
                            <th class="min-w-125px text-center">Debit</th>
                            <th class="min-w-125px text-center">Kredit</th>
                        </tr>
                        </thead>
                        <template x-if="isLoading">
                            <tbody class="fw-bold">
                            <tr>
                                <td colspan="5">
                                    <div style="text-align: center;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                        <template x-if="!isLoading && generalLedger.account_transaction?.length === 0">
                            <tbody class="fw-bold">
                            <tr>
                                <td colspan="5">
                                    <center>Data Tidak Ditemukan</center>
                                </td>
                            </tr>
                            </tbody>
                        </template>
                        <template x-for="(journal, index) in generalLedger.account_transaction" :key="index">
                            <tbody class="fw-bold">
                            <tr>
                                <td x-text="1 + index++"></td>
                                <td class="text-center" x-text="journal.date"></td>
                                <td class="text-center" x-text="journal.description"></td>
                                <td class="text-center" x-text="`${journal.debit[0]?.amount ?? '-'}`"></td>
                                <td class="text-center" x-text="`${journal.credit[0]?.amount ?? '-'}`"></td>
                            </tr>
                            </tbody>
                        </template>
                        <tfoot style="border-collapse: collapse;">
                        <tr class="fw-bold">
                            <td colspan="3" class="text-center">Jumlah</td>
                            <td class="text-center" x-text="generalLedger.total_debit"></td>
                            <td class="text-center" x-text="generalLedger.total_credit "></td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-center">Saldo</td>
                            <td colspan="2" class="text-center" x-text="generalLedger.total_balance"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function bigBookDetail() {
            return {
                isLoading: false,
                generalLedger: [],
                id: "{{ $account->id }}",
                startIndex: null,
                periods: [],
                async init() {
                    await this.getPeriod();
                    await this.getGeneralLedgerDetailData();
                },
                async getGeneralLedgerDetailData() {
                    const resp = await axios.get(`/journals/general-ledger/detail-akun/${this.id}`);
                    this.generalLedger = resp.data;
                },
                getPeriod() {
                    this.periods.push(
                        {value: 1, name: "Januari"},
                        {value: 2, name: "Februari"},
                        {value: 3, name: "Maret"},
                        {value: 4, name: "April"},
                        {value: 5, name: "Mei"},
                        {value: 6, name: "Juni"},
                        {value: 7, name: "Juli"},
                        {value: 8, name: "Agustus"},
                        {value: 9, name: "September"},
                        {value: 10, name: "Oktober"},
                        {value: 11, name: "November"},
                        {value: 12, name: "Desember"},
                    )
                },
                formatDate(val) {
                    return `${this.getMonthName(val - 1)}`;
                },
                getMonthName(monthIndex) {
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "December"
                    ];
                    return monthNames[monthIndex];
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
            }
        }
    </script>
@endpush
