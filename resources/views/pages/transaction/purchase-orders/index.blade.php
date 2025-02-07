@extends('layouts.template')
@section('page-title', 'Pendapatan - Purchase Order')
@section('content')
    <div x-data="purchaseOrderData()">
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
                    <div class="d-flex justify-content-end">
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true" style="">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bolder">Filter</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-bold">Cabang:</label>
                                    <select name="" id=""
                                            class="form-select form-select-solid filter-branch-select2">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <template x-if="Number(createPermission) === 1">
                            <a href="{{ url('/income-transactions/po/create') }}"
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
                        <table class="table align-middle table-bordered fs-6 gy-5">
                            <thead>
                            <tr class="text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">
                                    No
                                </th>
                                <th class="min-w-125px">No.PO</th>
                                <th class="min-w-125px">Subject</th>
                                <th class="min-w-125px">Tanggal</th>
                                <th class="min-w-100px">Klien</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-125px">PIC</th>
                            </thead>
                            <tbody class=" fw-bold">
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
                            <template x-if="!isLoading && purchaseOrders.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(po, index) in purchaseOrders?.data" :key="po.id">
                                <tr class="fw-bold text-center">
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td>
                                        <a :href="`${Number(viewDetailPermission) === 1 ? `/income-transactions/po/detail/${po.id}` : '#'}`"
                                           x-text="po.po_number"></a>
                                    </td>
                                    <td x-text="po.subject"></td>
                                    <td x-text="po.date"></td>
                                    <td x-text="po.contact"></td>
                                    <template x-if="po.status === 0">
                                        <td>
                                            <span class="badge bg-warning">Pending</span>
                                        </td>
                                    </template>
                                    <template x-if="po.status === 1">
                                        <td>
                                            <span class="badge bg-success">Terkonfirmasi</span>
                                        </td>
                                    </template>
                                    <td x-text="po.pic"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-5">
                        <template x-for="pagination in purchaseOrders.links">
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
        function purchaseOrderData() {
            return {
                createPermission: "{{ request()->user()->can('Tambah Data PO') }}",
                viewDetailPermission: "{{ request()->user()->can('Lihat Detail PO') }}",
                isLoading: false,
                startIndex: null,
                search: '',
                purchaseOrders: [],
                async init() {
                    await this.getPurchaseOrdersData();
                },
                async searchData() {
                    this.isLoading = true;
                    try {
                        const response = await axios.get('/income-transactions/po/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.purchaseOrders = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getPurchaseOrdersData() {
                    const resp = await axios.get('/income-transactions/po/data');
                    this.purchaseOrders = resp.data;
                    this.startIndex = this.purchaseOrders.from;
                },
                async paginationEndPoint(url) {
                    if (url) {
                        const resp = await axios.get(`${url}`);
                        this.purchaseOrders = resp.data
                    }
                },
            }
        }
    </script>
@endpush
