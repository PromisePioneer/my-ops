@extends('layouts.template')
@section('page-title', 'FAB Manager')
@section('content')
    <div x-data="FABData()">
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
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <a href="{{ url('/income-transactions/fab/create') }}"
                           class="btn btn-light-primary btn-sm">
                            <i class="ki-duotone ki-message-add fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i> Tambah
                        </a>
                    </div>
                    <div class="d-flex justify-content-end align-items-center d-none"
                         data-kt-product-table-toolbar="selected">
                        <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-product-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-product-table-select="delete_selected">
                            Delete Selected
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_products">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>No</th>
                                <th class="min-w-125px">Nomor</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Pelanggan</th>
                                <th class="min-w-125px">Dibuat Oleh</th>
                            </thead>
                            <tbody class="fw-bold">
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
                            <template x-if="!isLoading && subcriptions.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(fab, index) in subcriptions?.data" :key="fab.id">
                                <tr>
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td>
                                        <a :href="`/income-transactions/fab/detail/${fab.id}`"
                                           x-text="fab.code"></a>
                                    </td>
                                    <td x-text="fab.date"></td>
                                    <td x-text="fab.contact"></td>
                                    <td x-text="fab.created_by"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>


                    </div>
                    <ul class="pagination float-end mb-5">
                        <template x-for="pagination in subcriptions.links">
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

@endsection
@push('script')
    <script>
        function FABData() {
            return {
                subcriptions: [],
                startIndex: null,
                isLoading: true,
                search: '',
                async init() {
                    await this.getFABData();
                    this.isLoading = false;
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/income-transactions/fab/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.subcriptions = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.subcriptions = resp.data
                    }
                },
                async getFABData() {
                    const fab = await axios.get('/income-transactions/fab/data');
                    this.subcriptions = fab.data;
                    this.startIndex = this.subcriptions.from;
                },
                formatDate(val) {
                    if (val) {
                        const date = new Date(val);
                        const formatter = new Intl.DateTimeFormat('en-US', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                        return formatter.format(date);
                    }
                },
            }
        }
    </script>
@endpush
