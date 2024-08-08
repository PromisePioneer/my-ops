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
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light-info me-3 btn-sm " data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <span class="svg-icon svg-icon-2">
                                <i class="bi bi-funnel-fill"></i>
                            </span>
                            Filter
                        </button>
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
                                        <option value="0">Pilih Cabang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <a href="{{ url('/income-transactions/bast/create') }}"
                           class="btn btn-primary btn-sm">Tambah</a>
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
                                <th class="w-10px pe-2">
                                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" data-kt-check="true"
                                               data-kt-check-target="#kt_table_products .form-check-input" value="1"/>
                                    </div>
                                </th>
                                <th class="min-w-125px">Nomor BAST</th>
                                <th class="min-w-100px">Pelanggan</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Lampiran</th>
                                <th class="min-w-125px">Tgl Dibuat</th>
                                <th class="min-w-125px">Tgl Diubah</th>
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
                            <template x-if="!isLoading && bastList.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="bast in bastList?.data" :key="bast.id">
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1"/>
                                        </div>
                                    </td>
                                    <td>
                                        <a :href="`/income-transactions/bast/detail/${bast.id}`"
                                           x-text="bast.bast_number"></a>
                                    </td>
                                    <td x-text="bast.contact.full_name"></td>
                                    <template x-if="bast.payment_status === 'Belum Lunas'">
                                        <td>
                                            <button type="button" @click="updatePaymentStatus(bast.id)"
                                                    class="btn btn-sm btn-warning">Belum Lunas
                                            </button>
                                        </td>
                                    </template>
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
                                    <template x-if="bast.payment_status === 'Lunas'">
                                        <td>
                                            <span class="badge bg-warning">Lunas</span>
                                        </td>
                                    </template>
                                    <td>
                                        <a :href="`/income-transactions/bast/view-file/${bast.id}`"
                                           class="btn btn-sm btn-info"><i class="bi bi-file-earmark-break-fill"></i></a>
                                    </td>
                                    <td x-text="formatDate(bast.created_at)"></td>
                                    <td x-text="bast.user.name"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-5">
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
    @include('components.toast')
@endsection
@push('script')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('BastData', () => ({
                bastList: null,
                isLoading: true,
                startIndex: null,
                search: '',
                filterCategory: '',
                async init() {
                    await this.getBastData();
                    await this.filterByBranch();
                },
                async filterData() {
                    this.isLoading = true;
                    this.bastList = await axios.get('/income-transactions/bast/filter-category', {
                        params: {filterCategory: this.filterCategory},
                        headers: {'Content-Type': 'application/json'}
                    });
                    this.isLoading = false;
                },
                async searchData() {
                    this.bastList = await axios.get('/income-transactions/bast/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                },
                async nextPage() {
                    if (this.bastList.next_page_url) {
                        const resp = await axios.get(`${this.bastList.next_page_url}`);
                        this.startIndex = this.bastList.from
                        this.bastList = resp.data
                    }
                },
                async previousPage() {
                    if (this.bastList.prev_page_url) {
                        const resp = await axios.get(`${this.bastList.prev_page_url}`);
                        this.startIndex = this.bastList.from
                        this.bastList = resp.data
                    }
                },
                async updatePaymentStatus(id) {
                    showConfirmModal("Anda yakin?", "Data akan hilang.", "Ya, Hapus!", async () => {
                        try {
                            await axios.post(`/income-transactions/bast/update-payment-status/${id}`);
                            await showAlert('success', 'Data sukses dihapus');
                            await this.init();
                        } catch (error) {
                            console.error(error);
                            await showAlert('error', 'Terjadi kesalahan');
                        }
                    });
                },
                async getBastData() {
                    const bast = await axios.get('/income-transactions/bast/data');
                    this.bastList = bast.data;
                    this.startIndex = this.bastList.from;
                    this.isLoading = false;
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        ajax: {
                            url: '/income-transactions/bast/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/income-transactions/bast/filter/branch/data/${selectedBranch.id}`);
                        self.bastList = response.data;
                    });
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
            }))
        })
    </script>
@endpush
