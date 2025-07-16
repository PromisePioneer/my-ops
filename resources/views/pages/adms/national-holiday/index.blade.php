@extends('layouts.template')
@section('content')

    <div x-data="holidayData()">
        <div class="card card-xl-stretch mb-5 mb-xl-8">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                           <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                               class="form-control form-control-solid w-250px ps-14" placeholder="Search...">
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                        <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-light-primary btn-sm" @click="generateHoliday()">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Generate Hari Libur
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table table-bordered fs-6 gy-5 table-striped" id="kt_table_users">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                            </thead>
                            <template x-if="isLoading">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="3">
                                        <div style="text-align: center;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-if="!isLoading && nationalHolidays.data?.length === 0">
                                <tbody class="fw-bold">
                                <tr>
                                    <td colspan="3">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                            <template x-for="(holiday, index) in nationalHolidays?.data" :key="holiday.id">
                                <tbody class="fw-bold">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="holiday.name"></td>
                                    <td x-text="holiday.date"></td>
                                </tr>
                                </tbody>
                            </template>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in nationalHolidays.links">
                            <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                <button class="page-link" @click="paginationEndPoint(pagination.url)"
                                        x-html="pagination.label">
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('components.toast')
@endsection
@push('script')
    <script>
        function holidayData() {
            return {
                buttonLoading: true,
                isLoading: false,
                nationalHolidays: [],
                startIndex: null,
                search: '',
                async init() {
                    await this.getHolidayData();
                },
                async searchData() {
                    const resp = await axios.get('/adms/national-holiday/search',
                        {
                            params: {
                                search: this.search,
                                'content-type': 'application/json'
                            },
                        });
                    this.nationalHolidays = resp.data;
                },
                async getHolidayData() {
                    const resp = await axios.get('/adms/national-holiday/data');
                    this.nationalHolidays = resp.data;
                    this.startIndex = this.nationalHolidays.from
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.startIndex = resp.data.from
                        this.nationalHolidays = resp.data
                    }
                },
                async generateHoliday() {
                    showConfirmModal("Generate Data?", "Anda yakin ? ", "Ya", async () => {
                        try {
                            await axios.post(`/adms/national-holiday`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                }
            }
        }
    </script>
@endpush
