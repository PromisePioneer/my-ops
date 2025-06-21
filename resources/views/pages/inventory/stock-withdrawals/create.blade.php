@extends('layouts.template')
@section('page-title', 'Form Pengambilan Barang')
@section('content')
    <style>
        .modal-open .select2-container--bootstrap5 .select2-dropdown {
            z-index: 1020 !important;
        }
    </style>

    <div class="d-flex flex-column flex-lg-row" x-data="generateStockWithdrawals">
        <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <div class="card p-2">
                <form id="form" @submit.prevent="save()" enctype="multipart/form-data">
                    <div class="card-body p-5">
                        <div class="row gx-10 mb-5">
                            @if(empty(Auth::user()->branch_id))
                                <div class="col-lg-6">
                                    <div class="form-group row mb-6">
                                        <label
                                            class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Cabang</label>
                                        <div class="col-lg-11 fv-row">
                                            <select name="branch_id" id="branch_id"
                                                    class="form-select form-select-solid branches-select2">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">Karyawan</label>
                                    <div class="col-lg-11 fv-row">
                                        <select name="user_id[]" id="users"
                                                class="form-select form-select-solid users-select2" multiple>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row mb-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Kategori Barang
                                    </label>
                                    <div class="col-lg-11 fv-row">
                                        <select name="category_id"
                                                class="form-select form-select-solid item-categories-select2">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="branchId && itemCategoryId" x-transition x-cloak>
                            <div class="row justify-content-between align-items-start">
                                <div class="d-flex align-items-center position-relative my-1 mb-4">
                                        <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                           <i class="bi bi-search"></i>
                                        </span>
                                    <input type="text" name="search" x-model="search"
                                           @input.debounce="searchItemByCategoryAndBranch()"
                                           class="form-control form-control-solid w-250px ps-14"
                                           placeholder="Search...">
                                </div>
                                <div :class="`${category4 && stockDetail ? 'col-lg-5' : 'col-lg-6'}`">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                                            <thead>
                                            <tr class="text-center">
                                                <th>Nama</th>
                                                <th>Kode</th>
                                                <th>Qty</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <template x-if="isLoading">
                                                <tbody class="fw-bold">
                                                <tr class="text-center">
                                                    <td colspan="4">
                                                        <div style="text-align: center;">
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                            <template x-if="!isLoading && stockList?.data.length === 0">
                                                <tbody class="fw-bold text-center">
                                                <tr>
                                                    <td colspan="4">
                                                        <center>Data Tidak Ditemukan</center>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                            <template x-for="(stock, index) in stockList?.data" :key="index">
                                                <tbody class="text-center">
                                                <tr>
                                                    <td x-text="stock.name"></td>
                                                    <td x-text="stock.code ?? '-'"></td>
                                                    <td x-text="stock.qty"></td>
                                                    <td>
                                                        <template x-if="stock.category_name !== 'Kategori 4'">
                                                            <button type="button" class="btn btn-light-primary btn-sm"
                                                                    @click="selectCheckBox(stock.id, stock.code, stock.qty, stock.stock_id ?? stock.id, stock.name)">
                                                                <x-icons.upload/>
                                                            </button>
                                                        </template>
                                                        <template
                                                            x-if="stock.category_name === 'Kategori 4' && stock.qty > 0">
                                                            <button type="button" class="btn btn-light-primary btn-sm"
                                                                    @click="getStockDetail(stock.id)">
                                                                <x-icons.upload/>
                                                            </button>
                                                        </template>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                        <ul class="pagination float-end mb-4 mt-4">
                                            <template x-for="pagination in stockList.links">
                                                <li :class="`${pagination.active ? 'page-item active' : 'page-item'}`">
                                                    <button type="button" class="page-link btn-sm"
                                                            @click="paginationEndPoint(pagination.url)"
                                                            x-html="pagination.label">
                                                    </button>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                <div :class="`${category4 && stockDetail ? 'col-lg-2' : 'd-none'}`"
                                     x-show="category4 && stockDetail">
                                    <div class="d-flex flex-column">
                                        <input type="text" class="form-control form-control-solid mb-4"
                                               placeholder=" Kuantitas" :value="stockDetail?.available_qty"
                                               x-model="stockQty">
                                        <button type="button" class="btn btn-light-primary btn-sm"
                                                :disabled="!stockDetail"
                                                @click="selectCheckBox(stockDetail.id,null,stockQty, stockDetail.id, stockDetail.transaction?.item?.name ?? stockDetail.initial_inventory_balance?.item?.name)">
                                            <x-icons.arrow-right/>
                                        </button>
                                    </div>
                                </div>
                                <div :class="`${category4 ? 'col-lg-5' : 'col-lg-6'}`">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                                            <thead>
                                            <tr class="text-center">
                                                <th>Nama</th>
                                                <th>Kode</th>
                                                <th>Qty</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <template x-if="stockWithdrawalItemSessionsLoading">
                                                <tbody class="fw-bold text-center">
                                                <tr>
                                                    <td colspan="4">
                                                        <div style="text-align: center;">
                                                            <div class="spinner-border" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                            <template
                                                x-if="!stockWithdrawalItemSessionsLoading && stockWithdrawalItemSessions.length === 0">
                                                <tbody class="fw-bold text-center">
                                                <tr>
                                                    <td colspan="4">
                                                        <center>Data Tidak Ditemukan</center>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                            <template x-for="(stockWithdrawal, index) in stockWithdrawalItemSessions"
                                                      :key="index">
                                                <tbody class="text-center">
                                                <tr>
                                                    <td x-text="stockWithdrawal.item_name"></td>
                                                    <td x-text="stockWithdrawal.code ?? '-'"></td>
                                                    <td x-text="stockWithdrawal.qty"></td>
                                                    <td>
                                                        <button type="button" @click="deleteSessions(index)"
                                                                class="btn btn-light-danger btn-sm mb-4">
                                                            <x-icons.trash/>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="justify-content-end">
                                <template x-if="stockWithdrawalItemSessions.length > 0">
                                    <button type="button" @click="flushSession()"
                                            class="btn btn-light-danger btn-sm mb-4">
                                        <x-icons.trash/>
                                        Reset
                                    </button>
                                </template>
                            </div>
                        </div>


                        <div class="mb-10">
                            <label class="form-label fs-6 fw-bolder text-gray-700">Catatan</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3"
                                      placeholder="cth : Penggunaan untuk maintenance"></textarea>
                        </div>
                    </div>

                    <div class="float-end">
                        <a href="{{ url('/inventory/stock-withdrawals') }}"
                           class="btn btn-sm btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary" :disabled="buttonLoading"
                                x-text="buttonLoading ? 'Loading...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.toast')
    @include('components.select2.script')
@endsection
@push('script')
    <script>
        $("#invoiceDate").flatpickr();
        $("#dueDate").flatpickr();

        function generateStockWithdrawals() {
            return {
                isLoading: false,
                stock: null,
                editVal: '',
                stockWithdrawalItemSessionsLoading: false,
                branchId: null,
                itemCategoryId: null,
                buttonLoading: false,
                stockList: [],
                stockWithdrawalItemSessions: [],
                category4: false,
                form: document.getElementById('form'),
                stockDetail: null,
                stockQty: 0,
                search: '',
                async init() {
                    await this.getBranches();
                    await this.getItemCategories();
                    await select2('.users-select2', 'Pilih Karyawan', '/select2/users-data');
                    await this.getSessions();
                },
                async searchItemByCategory() {

                },
                async getSessions() {
                    this.stockWithdrawalItemSessionsLoading = true;
                    try {
                        const resp = await axios.get('/inventory/stock-withdrawals/get-sessions');
                        this.stockWithdrawalItemSessions = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.stockWithdrawalItemSessionsLoading = false;
                    }
                },
                async paginationEndPoint(url) {
                    if (url) {
                        this.stockList = [];
                        this.isLoading = true;
                        try {
                            const resp = await axios.get(`${url}`, {
                                params: {
                                    search: this.search,
                                    branch_id: this.branchId,
                                    category_id: this.itemCategoryId
                                }
                            });
                            this.stockList = resp.data
                        } catch (e) {
                            console.log(e)
                        } finally {
                            this.isLoading = false
                        }
                    }
                },
                async selectCheckBox(id, code = null, qty, stockId, itemName) {
                    await axios.get('/inventory/stock-withdrawals/session-store', {
                        params: {
                            id: id,
                            category_id: this.itemCategoryId,
                            code: code,
                            qty: qty,
                            stock_id: stockId,
                            item_name: itemName
                        }
                    });
                    this.stockDetail = null;
                    await this.getStockList();
                    await this.getSessions()
                },
                async getBranches() {
                    $(".branches-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Cabang",
                        ajax: {
                            url: '/select2/branches-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    }).on('select2:select', async (e) => {
                        this.branchId = e.params.data.id;
                        await this.getStockList();
                        await this.getSessions();
                    });
                },
                async getItemCategories() {
                    $(".item-categories-select2").select2({
                        allowClear: true,
                        placeholder: "Pilih Kategori Barang",
                        ajax: {
                            url: '/select2/item-categories-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true,
                        },
                    }).on('select2:select', async (e) => {
                        this.itemCategoryId = e.params.data.id;
                        this.category4 = e.params.data.text === 'Kategori 4'
                        console.log(this.category4);
                        await this.getStockList();
                        await this.getSessions();
                    });
                },
                async getStockList() {
                    try {
                        if (this.branchId && this.itemCategoryId) {
                            const resp = await axios.get('/inventory/stocks/data/branch/category', {
                                params: {
                                    branch_id: this.branchId,
                                    category_id: this.itemCategoryId
                                }
                            })
                            this.stockList = resp.data;
                        }
                    } catch (e) {
                        console.log(e)
                    }
                },
                async getUserData() {
                    $(".users-select2").select2({
                        placeholder: "Pilih Karyawan",
                        allowClear: true,
                        ajax: {
                            url: '/select2/user-has-areas-data',
                            dataType: "json",
                            type: "GET",
                            data: (params) => ({search: params.term}),
                            processResults: (data) => ({results: data}),
                            cache: true
                        }
                    });
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        await axios.post(`/inventory/stock-withdrawals/store`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/inventory/stock-withdrawals';
                        });
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
                async flushSession() {
                    await axios.get('/inventory/stock-withdrawals/flush-sessions');
                    await this.getStockList();
                    await this.getSessions();
                },
                async deleteSessions(index) {
                    await axios.get('/inventory/stock-withdrawals/delete-sessions', {
                        params: {
                            index: index
                        }
                    })
                    await this.getSessions();
                    await this.getStockList();
                },
                async getStockDetail(id) {
                    const resp = await axios.get(`/inventory/stocks/get-stock-detail/${id}`);
                    this.stockDetail = resp.data;
                    this.stockQty = resp.data.available_qty
                    console.log(this.stockDetail);
                    await this.getSessions();
                    await this.getStockList();
                },
                async searchItemByCategoryAndBranch() {
                    try {
                        const resp = await axios.get('/inventory/stocks/search/category/branch', {
                            params: {
                                search: this.search ?? "",
                                branch_id: this.branchId,
                                category_id: this.itemCategoryId
                            }
                        });
                        this.stockList = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endpush
