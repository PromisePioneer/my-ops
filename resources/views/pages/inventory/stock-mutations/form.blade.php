@extends('layouts.template')
@section('page-title', 'Mutasi Barang - Tambah')
@section('content')
    <div x-data="generateStockMutation()">
        <div class="card p-10">
            <div class="card-header border-0 pt-10">
                <a class="btn btn-light-danger btn-sm mb-6" href="{{ url('/inventory/stock-mutations') }}">
                    <x-icons.back/>
                    Kembali
                </a>
            </div>
            <div class="card-body py-3">
                <form id="form" @submit.prevent="save()">
                    @csrf
                    <div class="card-body p-12">
                        <div class="row mb-4">
                            @if(empty(Auth::user()->branch_id))
                                <div class="col-lg-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pilih Cabang Awal
                                    </label>
                                    <select class="form-select form-select-solid branches-select2"
                                            name="from_branch" id="from_branch">
                                    </select>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Pilih Cabang Tujuan
                                </label>
                                <select class="form-select form-select-solid branches-select2"
                                        name="to_branch" id="to_branch">
                                </select>
                            </div>

                            @if(Auth::user()->branch_id)
                                <div class="col-lg-6">
                                    <label
                                        class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                        Pilih Cabang
                                    </label>
                                    <select class="form-select form-select-solid main-branches-select2"
                                            name="branch_id" id="branch-id">
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Kategori Barang
                                </label>
                                <select class="form-select form-select-solid item-categories-select2"
                                        name="item_category_id" id="item_category_id">
                                </select>
                            </div>
                            <div class="col-lg-6" x-show="toBranchId" x-transition x-cloak>
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3 required">
                                    Penerima Barang
                                </label>
                                <select class="form-select form-select-solid user-branches-select2"
                                        name="receiver_id" id="receiver_id">
                                </select>
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
                                            <template x-if="!isLoading && stockList?.data?.length === 0">
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
                                                                    @click="sessionStore(stock.id, stock.code, stock.qty, stock.stock_id ?? stock.id, stock.name)">
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
                                                @click="sessionStore(stockDetail.id,null,stockQty, stockDetail.id, stockDetail.transaction?.item?.name ?? stockDetail.initial_inventory_balance?.item?.name)">
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
                                            <template x-if="stockMutationItemsLoading">
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
                                                x-if="!stockMutationItemsLoading && stockMutationItemSessions.length === 0">
                                                <tbody class="fw-bold text-center">
                                                <tr>
                                                    <td colspan="4">
                                                        <center>Data Tidak Ditemukan</center>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </template>
                                            <template x-for="(stockMutation, index) in stockMutationItemSessions"
                                                      :key="index">
                                                <tbody class="text-center">
                                                <tr>
                                                    <td x-text="stockMutation.item_name"></td>
                                                    <td x-text="stockMutation.code ?? '-'"></td>
                                                    <td x-text="stockMutation.qty"></td>
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
                                <template x-if="stockMutationItemSessions.length > 0">
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


                    <div class="float-end d-flex py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2 btn-sm">Reset</button>
                        <button type="submit" class="btn btn-sm btn-light-primary"
                                :disabled="buttonLoading">
                            <i class="ki-duotone ki-click fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                            <span x-text="buttonLoading ? 'Loading...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function generateStockMutation() {
            return {
                isLoading: false,
                itemWithCode: false,
                itemWithoutCode: false,
                editVal: '',
                stockDetail: null,
                stockQty: 0,
                category4: false,
                branchId: null,
                toBranchId: null,
                itemCategoryId: null,
                buttonLoading: false,
                form: document.getElementById('form'),
                itemWithCodes: [],
                stockMutationItemSessions: [],
                stockMutationItemsLoading: false,
                search: '',
                itemWithoutCodeFields: [{
                    stock_id: '',
                    qty: '',
                }],
                stockList: [],
                toBranchParentId: null,
                async init() {
                    await this.branchOnSelect();
                    await this.itemCategoryOnSelect();
                    await select2('.branches-select2', 'Pilih Cabang', '/select2/branches-data');
                    await select2('.item-categories-select2', 'Pilih Kategori Barang', '/select2/item-categories-data');
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
                                    category_id: this.itemCategoryId,
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
                async branchOnSelect() {
                    $('#from_branch').on('select2:select', async (e) => {
                        this.branchId = e.params.data.id;
                        await this.getStockList();
                        await this.getSessions();
                    });

                    $('#to_branch').on('select2:select', async (e) => {
                        $('.user-branches-select2').val('').trigger('change');
                        this.toBranchId = e.params.data.id;
                        await select2('.user-branches-select2', 'Pilih Penerima', `/select2/user-branches-data/${this.toBranchId}`);
                    });
                },
                async itemCategoryOnSelect() {
                    $('.item-categories-select2').on('select2:select', async (e) => {
                        this.itemCategoryId = e?.params?.data?.id;
                        this.category4 = e.params.data.text === 'Kategori 4'
                        await this.getStockList();
                        await this.getSessions();
                    });
                },
                async getStockDetail(id) {
                    const resp = await axios.get(`/inventory/stocks/get-stock-detail/${id}`);
                    this.stockDetail = resp.data;
                    this.stockQty = resp.data.available_qty
                    await this.getSessions();
                    await this.getStockList();
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
                async getSessions() {
                    this.stockMutationItemsLoading = true;
                    try {
                        const resp = await axios.get('/inventory/stock-mutations/get-sessions');
                        this.stockMutationItemSessions = resp.data;
                    } catch (e) {
                        console.log(e)
                    } finally {
                        this.stockMutationItemsLoading = false;
                    }
                },
                async sessionStore(id, code = null, qty, stockId, itemName) {
                    await axios.get('/inventory/stock-mutations/session-store', {
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
                async flushSession() {
                    await axios.get('/inventory/stock-mutations/flush-sessions');
                    await this.getStockList();
                    await this.getSessions();
                },
                async deleteSessions(index) {
                    await axios.get('/inventory/stock-mutations/delete-sessions', {
                        params: {
                            index: index
                        }
                    })
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
                },
                async save() {
                    this.buttonLoading = true;
                    try {
                        let formData = new FormData(this.form);
                        await axios.post(`/inventory/stock-mutations/`, new FormData(this.form))
                        await showAlert('success', 'Data berhasil disimpan').then(() => {
                            window.location.href = '/inventory/stock-mutations';
                        })
                    } catch (error) {
                        const respError = error.response.data.errors;
                        Object.keys(respError).map(err => toastr.error(`${respError[err][0]}`));
                    } finally {
                        this.buttonLoading = false;
                    }
                },
            }
        }
    </script>
@endpush

