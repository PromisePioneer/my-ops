@extends('layouts.template')
@section('page-title', 'Buku Besar')
@section('content')

    <div x-data="bigBookDetail">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <h6>{{ $account->code }} {{ $account->name }}</h6>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row py-5">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped">
                        <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Uraian</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="journal in generalLedger.transactions">
                            <tr>
                                <td x-text="journal.month"></td>
                                <td x-text="`${generalLedger.account}`"></td>
                                <td x-text="formatNumber(journal.total_debit)"></td>
                                <td x-text="formatNumber(journal.total_credit)"></td>
                                <td x-text="`${formatNumber(journal.total_debit - row.total_credit)}`"></td>
                            </tr>
                        </template>
                        </tbody>
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
                generalLedger: [],
                id: "{{ $account->id }}",
                startIndex: null,
                async init() {
                    const generalLedger = await axios.get(`/journals/general-ledger/detail-akun/${this.id}`);
                    this.generalLedger = generalLedger.data;
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
