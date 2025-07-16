@extends('layouts.template')
@section('page-title', 'Buku Besar')
@section('content')
    <div x-data="generalLedgerData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-body py-3">
                <div class="row py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px text-center">Akun</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="2">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && generalLedger.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="2">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="row in generalLedger" :key="index">
                                <tbody class="fw-bold">
                                <tr>
                                    <td class="text-center">
                                        <a :href="`/journals/general-ledger/detail/${row.id}`"
                                           x-text="row.name"></a>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function generalLedgerData() {
            return {
                generalLedger: [],
                startIndex: null,
                async init() {
                    const resp = await axios.get('/journals/general-ledger/data');
                    this.generalLedger = resp.data;
                },
            }
        }
    </script>
@endpush
