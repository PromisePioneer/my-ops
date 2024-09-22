@extends('layouts.template')
@section('page-title', 'Data Invoice')
@section('content')

    <div x-data="invoiceData">
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
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                        <a href="{{ url('/income-transactions/invoice/create') }}"
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
                                    No
                                </th>
                                <th class="min-w-125px">Nomor Invoice</th>
                                <th class="min-w-100px">Pelanggan</th>
                                <th class="min-w-100px">Payment Status</th>
                                <th class="min-w-125px">Lampiran</th>
                                <th class="min-w-125px">Tgl Jatuh Tempo</th>
                                <th class="min-w-125px">Deadline</th>
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
                            <template x-if="!isLoading && invoices.data?.length === 0">
                                <tr>
                                    <td colspan="9">
                                        <center>Data Tidak Ditemukan</center>
                                    </td>
                                </tr>
                            </template>
                            <template x-for="(invoice, index) in invoices?.data" :key="invoice.id">
                                <tr>
                                    <td x-text="startIndex + index++">
                                    </td>
                                    <td>
                                        <a :href="`/income-transactions/invoice/detail/${invoice.id}`"
                                           x-text="invoice.invoice_number"></a>
                                    </td>
                                    <td x-text="invoice.contact"></td>
                                    <template x-if="invoice.payment_status === 'Belum Lunas'">
                                        <td>
                                            <span class="badge bg-warning">Belum Lunas</span>
                                        </td>
                                    </template>
                                    <template x-if="invoice.payment_status === 'Lunas'">
                                        <td>
                                            <span class="badge bg-success">Lunas</span>
                                        </td>
                                    </template>
                                    <td>
                                        <a :href="`/income-transactions/invoice/view-file/${invoice.id}`"
                                           class="btn btn-sm btn-info"><i class="bi bi-file-earmark-break-fill"></i></a>
                                    </td>
                                    <td x-text="invoice.due_date"></td>
                                    <td x-html="differenceBetweenDate(invoice.created_at,invoice.due_date)"></td>
                                    <td x-text="invoice.created_by"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <ul class="pagination float-end mb-4 mt-4">
                        <template x-for="pagination in invoices.links">
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
        function invoiceData() {
            return {
                invoices: null,
                isLoading: true,
                startIndex: null,
                search: '',
                filterCategory: '',
                openPph23Form: false,
                async init() {
                    await this.filterByBranch();
                    await this.getInvoiceData();
                },
                openPphAction() {
                    this.openPph23Form = !this.openPph23Form;
                },
                async filterData() {
                    this.isLoading = true;
                    this.invoices = await axios.get('/income-transactions/invoice/filter-category', {
                        params: {
                            filterCategory: this.filterCategory
                        },
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                    this.isLoading = false;
                },
                async getInvoiceData() {
                    const invoices = await axios.get('/income-transactions/invoice/data');
                    this.invoices = invoices.data;
                    this.startIndex = this.invoices.from;
                    this.isLoading = false;
                },
                async filterByBranch() {
                    const self = this;
                    $(".filter-branch-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/income-transactions/invoice/branch/data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    }).on('change', async function (e) {
                        const selectedBranch = $(this).select2('data')[0];
                        const response = await axios.get(`/income-transactions/invoice/filter/branch/data/${selectedBranch.id}`);
                        self.invoices = response.data;
                    });
                },
                async searchData() {

                    this.isLoading = true;
                    try {
                        const response = await axios.get('/income-transactions/invoice/search', {
                            params: {search: this.search},
                            headers: {'Content-Type': 'application/json'}
                        });
                        this.invoices = response.data;
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                async nextPage() {
                    if (this.invoices.next_page_url) {
                        const resp = await axios.get(`${this.invoices.next_page_url}`);
                        this.startIndex = this.invoices.from
                        this.invoices = resp.data
                    }
                },
                async previousPage() {
                    if (this.invoices.prev_page_url) {
                        const resp = await axios.get(`${this.invoices.prev_page_url}`);
                        this.startIndex = this.invoices.from
                        this.invoices = resp.data

                    }
                },
                formatDate(val) {
                    if (val) {
                        const date = new Date(val);
                        const formatter = new Intl.DateTimeFormat('en-ID', {
                            day: 'numeric',
                            month: 'numeric',
                            year: 'numeric'
                        });
                        return formatter.format(date);
                    }
                },
                formatNumber(curr) {
                    let IDR = new Intl.NumberFormat('en-ID', {
                        style: 'currency',
                        currency: "IDR"
                    });

                    return IDR.format(curr);
                },
                differenceBetweenDate(invoiceDate, dueDate) {

                    if (invoiceDate.charAt(2) === '-' && dueDate.charAt(2) === '-') {
                        invoiceDate = new Date(this.formatDate(invoiceDate));
                        dueDate = new Date(this.formatDate(dueDate));
                    } else {
                        invoiceDate = new Date(invoiceDate);
                        dueDate = new Date(dueDate);
                    }
                    let timeDiff = Math.abs(invoiceDate.getTime() - dueDate.getTime());
                    let diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));


                    if (diffDays > 7) {
                        return `<span class="badge bg-info text-white fs-7">${diffDays} Hari</span>`
                    }

                    return `<span class="badge bg-danger text-white fs-7">${diffDays} Hari</span>`
                },
            }
        }
    </script>
@endpush
