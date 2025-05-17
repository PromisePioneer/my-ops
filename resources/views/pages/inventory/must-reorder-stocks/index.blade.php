@extends('layouts.template')
@section('content')
    <div x-data="mustReorderStock()">
        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-lg-250px mb-10">
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
                                            name="branch_id" id="branch-id-filter">
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
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex justify-content-end " data-kt-user-table-toolbar="base">
                                <a href="{{ url('/inventory/stocks') }}" class="btn btn-light-danger btn-sm">
                                    <x-icons.back/>
                                    Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                   <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" x-model="search" @input.debounce="searchData()"
                                       class="form-control form-control-solid w-250px ps-14"
                                       placeholder="Search...">
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="py-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-bordered fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                    <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            #
                                        </th>
                                        <th class="min-w-125px text-center">Nama Barang</th>
                                        <th class="min-w-125px text-center">Total Stok</th>
                                    </thead>

                                    <template x-if="isLoading">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="7">
                                                <div style="text-align: center;">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-if="!isLoading && mustReorderStocks.data?.length === 0">
                                        <tbody class="fw-bold">
                                        <tr>
                                            <td colspan="7">
                                                <center>Data Tidak Ditemukan</center>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </template>
                                    <template x-for="(stock, index) in mustReorderStocks?.data" :key="stock.id">
                                        <tbody class="fw-bold text-center">
                                        <tr>
                                            <td x-text="startIndex + index++"></td>
                                            <td x-text="stock.name"></td>
                                            <td x-text="stock.total_stock ?? '-'"></td>
                                        </tr>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                            <ul class="pagination float-end mb-4 mt-4">
                                <template x-for="pagination in mustReorderStocks.links">
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
        </div>

    </div>
@endsection
@push('script')
    <script>
        function mustReorderStock() {
            return {
                isLoading: false,
                mustReorderStocks: [],
                startIndex: null,
                search: '',
                async init() {
                    await this.getMustReorderStocks();
                    await this.getMainBranches();
                },
                async getMustReorderStocks() {
                    try {
                        const resp = await axios.get('/inventory/must-reorder-stocks/data');
                        this.mustReorderStocks = resp.data;
                        this.startIndex = resp.data.from;
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.isLoading = false
                    }
                },
                async searchData() {
                    this.carriedStocks = [];
                    this.isLoading = true;
                    try {
                        const resp = await axios.get('/inventory/stock-withdrawal-items/search', {
                            params: {
                                search: this.search
                            }
                        });
                        this.carriedStocks = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                },
                async getMainBranches() {
                    $(".main-branches-select2").select2({
                        allowClear: true,
                        placeholder: 'Pilih Cabang',
                        ajax: {
                            url: '/select2/main-branches-data',
                            dataType: "json",
                            type: "GET",
                            data: params => ({search: params.term}),
                            processResults: data => ({results: data}),
                            cache: true
                        }
                    });
                },
                async filter() {
                    try {
                        const resp = await axios.get('/inventory/stock-withdrawal-items/filter', {
                            params: {
                                branch_id: $('#branch-id-filter').val()
                            }
                        });

                        this.carriedStocks = resp.data;
                    } catch (e) {
                        console.log(e)
                    }
                }
            }
        }
    </script>
@endpush
