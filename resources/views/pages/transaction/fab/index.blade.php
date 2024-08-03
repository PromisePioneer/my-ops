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
                        <a href="{{ url('income-transactions/fab/create') }}"
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
                                <th>No</th>
                                <th class="min-w-125px">Kode</th>
                                <th class="min-w-125px">Pelanggan</th>
                                <th class="min-w-100px">Status Berlangganan</th>
                                <th class="min-w-125px">Lampiran</th>
                                <th class="min-w-125px">Tgl Dibuat</th>
                                <th class="min-w-125px">Dibuat Oleh</th>
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
                                           x-text="fab.fab_number"></a>
                                    </td>
                                    <td x-text="fab.contact.full_name"></td>
                                    <td class="text-capitalize" x-text="fab.subscription_status"></td>
                                    <td>
                                        <a :href="`/income-transactions/fab/view-file/${fab.id}`"
                                           class="btn btn-sm btn-info"><i class="bi bi-file-earmark-break-fill"></i></a>
                                    </td>
                                    <td x-text="formatDate(fab.created_at)"></td>
                                    <td x-text="fab.user.name"></td>
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

@endsection
@push('script')
    <script>
        function FABData() {
            return {
                subcriptions: [],
                startIndex: null,
                isLoading: true,
                search: '',
                async init(){
                    await this.getFABData();
                    await this.filterByBranch();
                    this.isLoading = false;
                },
                async searchData(){
                    this.subcriptions = await axios.get('/income-transactions/fab/search', {
                        params: {search: this.search},
                        headers: {'Content-Type': 'application/json'}
                    });
                },
                async nextPage(){
                    if (this.subcriptions.next_page_url) {
                        const resp = await axios.get(`${this.subcriptions.next_page_url}`);
                        this.startIndex = resp.data.from
                        this.subcriptions = resp.data
                    }
                },
                async previousPage(){
                    if (this.subcriptions.prev_page_url) {
                        const resp = await axios.get(`${this.subcriptions.prev_page_url}`);
                        this.startIndex = resp.data.from
                        this.subcriptions = resp.data
                    }
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        ajax: {
                            url: '/income-transactions/fab/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                    $(".filter-branch-select2").on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/income-transactions/fab/filter/branch/data/${selectedBranch.id}`);
                        self.subcriptions = response.data;
                    });
                },
                async getFABData() {
                    const fab = await axios.get('/income-transactions/fab/data');
                    this.subcriptions = fab.data;
                    this.startIndex = this.subcriptions.from;
                },
                formatDate(val){
                    if(val){
                        const date = new Date(val);
                        const formatter = new Intl.DateTimeFormat('en-US', { day: '2-digit', month: '2-digit', year: 'numeric' });
                        return formatter.format(date);
                    }
                },
            }
        }
    </script>
@endpush
