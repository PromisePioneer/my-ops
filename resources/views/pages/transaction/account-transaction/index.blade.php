@extends('layouts.template')
@section('page-title', 'Riwayat Transaksi Akun')
@section('content')

    <div x-data="accountTransactionHistory()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
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
                    <div class="mb-4 float-end">
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Sumber</th>
                                <th class="min-w-125px">Nominal Debit</th>
                                <th class="min-w-125px">Nominal Kredit</th>
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
                            <template x-for="(accountTransaction,index) in accountTransactions?.data" :key="accountTransaction.id">
                                <tr>
                                    <td x-text="accountTransaction.date"></td>
                                    <td x-text="accountTransaction.description"></td>
                                    <td x-text="accountTransaction.debit"></td>
                                    <td x-text="accountTransaction.credit"></td>
                                    <td x-text="accountTransaction.total"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="page-link" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="page-link" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.getElementById('startDate').flatpickr();
        document.getElementById('endDate').flatpickr();

        function accountTransactionHistory() {
            return {
                accountTransactions: [],
                startIndex: 0,
                isLoading: true,
                search: '',
                async init() {
                    const accountTransactions = await axios.get(`/account-master/account-transaction/data`);
                    this.accountTransactions = accountTransactions.data
                    this.startIndex = this.accountTransactions.from;
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/account-master/account-transaction/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.accountTransactions = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
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
            }
        }
    </script>
@endpush
