@extends('layouts.template')
@section('page-title', 'Detail Jurnal Umum')
@section('content')

    <div x-data="generalJournals()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex my-1 align-items-center">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-responsive-lg align-middle table-row-dashed fs-6 gy-5"
                               id="kt_table_users">
                            <thead class="table-dark">
                            <tr class="text-start fw-bolder fs-7 text-uppercase gs-0">
                                <th class="px-10">Tanggal</th>
                                <th class="min-w-400px">Uraian</th>
                                <th class="min-w-125px text-center">Debit</th>
                                <th class="min-w-125px text-center">Kredit</th>
                            </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-bold">
                            <template x-if="isLoading">
                                <tr class="text-start text-muted border fw-bolder fs-7 text-uppercase gs-0">
                                    <td colspan="9">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && generalJournal?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(journal, index) in generalJournal" :key="index">
                                <tr class="text-black">
                                    <td class="px-10" x-text="journal.tanggal"></td>
                                    <td x-text="journal.description"></td>
                                    <td class="p-0 m-0">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-sm align-middle table-bordered border fs-6 gy-5 table-striped">
                                                <thead class="table-dark">
                                                <tr class="text-start fw-bolder fs-7 text-uppercase gs-0 border">
                                                    <th class="min-w-125px text-center">Akun</th>
                                                    <th class="min-w-125px text-center">Nominal</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <template x-for="(debit, index) in journal.debit" :key="index">
                                                    <tr class="border">
                                                        <td class="min-w-300px text-center"
                                                            x-text="`${debit.code} ${debit.account_name}`"></td>
                                                        <td class="min-w-300px text-center"
                                                            x-text="debit.amount">
                                                        </td>
                                                    </tr>
                                                </template>

                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                    <td class="p-0 m-0">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-sm align-middle table-bordered border fs-6 gy-5 table-striped">
                                                <thead class="table-dark">
                                                <tr class="text-start fw-bolder fs-7 text-uppercase gs-0 border">
                                                    <th class="min-w-125px text-center">Akun</th>
                                                    <th class="min-w-125px text-center">Nominal</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <template x-for="(credit, index) in journal.credit" :key="index">
                                                    <tr class="border">
                                                        <td class="min-w-300px text-center"
                                                            x-text="`${credit.code} ${credit.account_name}`"></td>
                                                        <td class="min-w-300px text-center"
                                                            x-text="credit.amount">
                                                        </td>
                                                    </tr>
                                                </template>

                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage()">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage()">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        function generalJournals() {
            return {
                isLoading: false,
                generalJournal: [],
                time: "{{ $time }}",
                search: '',
                startIndex: null,
                async init() {
                    this.isLoading = true;
                    const journal = await axios.get(`/journals/general-journal/detail/data/${this.time}`);
                    this.generalJournal = journal.data;
                    this.startIndex = this.generalJournal.from;
                    this.isLoading = false;
                },
            }
        }
    </script>
@endpush
