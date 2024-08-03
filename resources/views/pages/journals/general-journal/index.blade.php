@extends('layouts.template')
@section('page-title', 'Jurnal Umum')
@section('content')

    <div x-data="journalPeriod()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
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
                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">No</th>
                                <th class="min-w-125px">Periode</th>
                                <th class="min-w-125px">Actions</th>
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
                            <template x-if="!isLoading && periods.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(period, index) in periods.data" :key="index">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="formatDate(period.waktu)"></td>
                                    <td>
                                        <a :href="`/journals/general-journal/detail/01-${period.waktu}`"
                                           class="btn btn-info btn-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4">
                        <li class="page-item previous">
                            <button class="btn btn-light btn-sm" @click="previousPage">Previous</button>
                        </li>
                        <li class="page-item next">
                            <button class="btn btn-light btn-sm" @click="nextPage">Next</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>
        function journalPeriod() {
            return {
                periods: [],
                startIndex: 0,
                search: '',
                isLoading: false,
                async init() {
                    await this.getGeneralJournalPeriod();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        this.periods = await axios.get('/journals/general-journal/search', {
                            params: {
                                search: this.search
                            },
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        });
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                },
                formatDate(val) {
                    const [month, year] = val.split('-');
                    const date = new Date(year, month - 1, 1);
                    return `${this.getMonthName(date.getMonth())} ${date.getFullYear()}`;
                },
                getMonthName(monthIndex) {
                    const monthNames = [
                        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                        "Juli", "Agustus", "September", "Oktober", "November", "December"
                    ];
                    return monthNames[monthIndex];
                },
                async nextPage() {
                    if (this.periods.next_page_url) {
                        const resp = await axios.get(`${this.periods.next_page_url}`);
                        this.startIndex = this.periods.from
                        this.periods = resp.data
                    }
                },
                async previousPage() {
                    if (this.periods.prev_page_url) {
                        const resp = await axios.get(`${this.periods.prev_page_url}`);
                        this.startIndex = this.periods.from
                        this.periods = resp.data
                    }
                },
                async getGeneralJournalPeriod() {
                    this.isLoading = true;
                    try {
                        const jurnalPeriod = await axios.get('/journals/general-journal/periode');
                        this.periods = jurnalPeriod.data;
                        this.startIndex = this.periods.from;
                    } catch (error) {
                        console.log(error)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
