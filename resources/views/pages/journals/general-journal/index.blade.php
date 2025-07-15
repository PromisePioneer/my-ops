@extends('layouts.template')
@section('page-title', 'Jurnal Umum')
@section('content')

    <div x-data="generalJournals()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto mb-10" x-show="filterButton" x-transition x-cloak>
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
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                    <button type="button" class="btn btn-light-primary btn-sm"
                                            @click="filterButton = !filterButton">
                                        <i class="bi bi-funnel-fill"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <div id="kt_roles_view_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5 mb-0 dataTable no-footer"
                                       id="kt_roles_view_table">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px text-center">Cabang</th>
                                        <th class="min-w-125px text-center">Tanggal</th>
                                        <th class="min-w-125px text-center">Akun</th>
                                        <th class="min-w-125px text-center">Deskripsi</th>
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
                                    <template x-if="!isLoading && generalJournal.data?.length === 0">
                                        <tr>
                                            <td colspan="9">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-for="(journal, index) in generalJournal.data" :key="index">
                                        <tr>
                                            <td class="text-center" x-text="journal.branch_name"></td>
                                            <td class="text-center" x-text="journal.date"></td>
                                            <td x-text="journal.account"></td>
                                            <td x-text="journal.description"></td>
                                            <td class="text-center"
                                                x-text="journal.type === 'debit' ? journal.amount : '-'"></td>
                                            <td class="text-center"
                                                x-text="journal.type === 'credit' ? journal.amount : '-'"></td>
                                        </tr>
                                    </template>
                                    </tbody>
                                </table>
                            </div>
                            <template x-if="generalJournal.last_page >= page">
                                <div class="d-grid gap-2 mt-4">
                                    <button @click="seeMore(generalJournal.path)"
                                            class="btn btn-sm btn-light-info fs-4 fw-bolder text-uppercase">
                                        Lihat Lebih Banyak
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
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
                search: '',
                startIndex: null,
                filterButton: false,
                page: 1,
                months: [],
                async init() {
                    await this.getGeneralJournalData();
                    await this.getMainBranches();
                    this.getMonth();
                },
                async getGeneralJournalData() {
                    this.isLoading = true;
                    try {
                        const resp = await axios.get(`/journals/general-journal/data/`);
                        this.generalJournal = resp.data;
                        this.startIndex = this.generalJournal.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async seeMore(url) {
                    this.page++;
                    try {
                        const resp = await axios.get(`${url}?=page${this.page}`, {
                            params: {
                                month: document.getElementById('month').value,
                                branch_id: $(".main-branches-select2")?.val(),
                            }
                        });
                        this.generalJournal.data.push(...resp.data.data);
                    } catch (e) {
                        console.log(e);
                    }
                },
                async filter() {
                    try {
                        this.generalJournal = []
                        this.isLoading = true;
                        const resp = await axios.get('/journals/general-journal/filter', {
                            params: {
                                month: document.getElementById('month').value,
                                branch_id: $(".main-branches-select2")?.val(),
                            }
                        });
                        this.generalJournal = resp.data;
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
                }
            }
        }
    </script>
@endpush
