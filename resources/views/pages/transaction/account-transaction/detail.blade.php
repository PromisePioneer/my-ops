@extends('layouts.template')
@section('page-title', 'Transaksi Account')
@section('content')
    <div x-data="accountTransactions">
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex my-1 align-items-center">
                        <div class="mb-4">
                            <input type="date" class="form-control form-control-solid" placeholder="Tanggal awal"
                                   id="startDate"/>
                        </div>
                        <div class="mb-4 ms-5">
                            <input type="date" class="form-control form-control-solid" placeholder="Tanggal akhir"
                                   id="endDate"/>
                        </div>
                        <div class="mb-4 ms-5">
                            <button class="btn btn-primary btn-sm">Filter</button>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <h6>({{ $account->code }}) {{ $account->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-user-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete
                            Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="">No</th>
                                <th class="min-w-125px">Uraian</th>
                                <th class="min-w-125px">Sumber</th>
                                <th class="min-w-125px">Pelanggan</th>
                                <th class="min-w-125px">Debit</th>
                                <th class="min-w-125px">Kredit</th>
                                <th class="min-w-125px">Total</th>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && accountTransactions.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(row,index) in accountTransactions?.data" :key="row.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td>
                                        <a :href="`/transaction/invoice/preview/${row.invoice_id}`"
                                           x-text="row.description"></a>
                                    </td>
                                    <td x-text="formatNumber(row.debit)"></td>
                                    <td x-text="formatNumber(row.credit)"></td>
                                    <td x-text="formatNumber(row.total_saldo)"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="page-link" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="page-link" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script defer>
        document.getElementById('startDate').flatpickr();
        document.getElementById('endDate').flatpickr();

        function accountTransactions() {
            return {
                accountTransactions: null,
                isLoading: true,
                startIndex: null,
                search: '',
                accountId: "{{ $account->id }}",
                async init() {
                    const accountTransactions = await axios.get(`/account-master/account-transaction/detail/data/${this.accountId}`);
                    this.accountTransactions = accountTransactions.data
                    this.startIndex = this.accountTransactions.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    this.accountTransactions = await axios.get('/master/cabang/search', {
                        params: {
                            search: this.search
                        },
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });

                    this.isLoading = false;
                },
                async nextPage() {
                    if (this.accountTransactions.next_page_url) {
                        const resp = await axios.get(`${this.accountTransactions.next_page_url}`);
                        this.startIndex = this.accountTransactions.from
                        this.accountTransactions = resp.data
                    }
                },
                async previousPage() {
                    if (this.accountTransactions.prev_page_url) {
                        const resp = await axios.get(`${this.accountTransactions.prev_page_url}`);
                        this.startIndex = this.accountTransactions.from
                        this.accountTransactions = resp.data
                    }
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                }
            }
        }
    </script>
@endpush
