@extends('layouts.template')
@section('page-title', 'Data BAST')
@section('content')

    <div x-data="BastData()">
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
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <a href="{{ url('/income-transactions/bast/create') }}"
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
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0 text-center">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-125px">Nomor</th>
                                <th class="min-w-100px">Pelanggan</th>
                                <th class="min-w-125px">Status</th>
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
                            <template x-if="!isLoading && bastList?.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(bast, index) in bastList?.data" :key="bast.id">
                                <tr class="text-center">
                                    <td x-text="startIndex + index++"></td>
                                    <td x-text="bast.date"></td>
                                    <td>
                                        <a :href="`/income-transactions/bast/detail/${bast.id}`"
                                           x-text="bast.bast_number"></a>
                                    </td>
                                    <td x-text="bast.contact"></td>
                                    <template x-if="bast.status === 0">
                                        <td>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                    </template>
                                    <template x-if="bast.status === 1">
                                        <td>
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        </td>
                                    </template>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in bastList?.links">
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
        function BastData() {
            return {
                bastList: null,
                isLoading: false,
                search: '',
                filterCategory: '',
                async init() {
                    await this.getBastData();
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.bastList = resp.data
                    }
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/income-transactions/bast/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.bastList = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getBastData() {
                    const bast = await axios.get('/income-transactions/bast/data');
                    this.bastList = bast.data;
                    this.startIndex = this.bastList.from;
                    this.isLoading = false;
                },
            }
        }
    </script>
@endpush
