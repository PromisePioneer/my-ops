@extends('layouts.template')
@section('page-title', 'Berita Acara Aktivasi')
@section('content')
    <div x-data="baaData()">
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
                        <template x-if="Number(createPermission) === 1">
                            <a href="{{ url('/income-transactions/baa/create') }}"
                               class="btn btn-light-primary btn-sm">
                                <i class="ki-duotone ki-message-add fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i> Tambah
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="py-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_products">
                            <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">Nomor BAA</th>
                                <th class="min-w-100px">Tanggal</th>
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
                            <template x-if="!isLoading && baa?.data?.length === 0">
                                <tr>
                                    <td colspan="5">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(activation, index) in baa?.data" :key="activation.id">
                                <tr>
                                    <td x-text="startIndex + index++"></td>
                                    <td>
                                        <a :href="Number(viewDetailPermission) === 1 ? `/income-transactions/baa/detail/${activation.id}` : '#'"
                                           x-text="activation.baa_number"></a>
                                    </td>
                                    <td x-text="activation.date"></td>
                                    <template x-if="activation.status === 0">
                                        <td>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                    </template>
                                    <template x-if="activation.status === 1">
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
                        <template x-for="pagination in baa?.links">
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
        function baaData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data BAA') }}",
                viewDetail: "{{ request()->user()->can('Lihat Detail Data BAA') }}",
                search: '',
                startIndex: null,
                isLoading: false,
                baa: [],
                async init() {
                    await this.getBaaData();
                },
                async searchData() {
                    try {
                        const resp = await axios.get('/income-transactions/baa/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });

                        this.baa = resp.data;
                    } catch (error) {
                        console.log(error);
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.baa = resp.data
                        this.startIndex = this.baa.from
                    }
                },
                async getBaaData() {
                    try {
                        const resp = await axios.get('/income-transactions/baa/data');
                        this.baa = resp.data;
                        this.startIndex = this.baa.from;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
            }
        }
    </script>
@endpush

